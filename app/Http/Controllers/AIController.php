<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\RoadAnalysis;
use App\Models\User;
use App\Models\MaintenanceTask;
use App\Models\TaskActivity;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewRoadAnalysisNotification;
use App\Models\ContactMessage;

class AIController extends Controller
{
    /**
     * Show the upload form.
     */
    public function upload()
    {
        return view('upload');
    }

    /**
     * Handle image upload, call Python AI API, save result to DB.
     */
    public function analyze(Request $request)
    {
        try {
            $request->validate([
                'road_image' => 'required|image|max:10240', // max 10MB
                'location'   => 'nullable|string|max:255',
                'latitude'   => 'nullable|numeric',
                'longitude'  => 'nullable|numeric',
            ]);

            $file     = $request->file('road_image');
            $location = $request->input('location', 'Unknown Location');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            // Save image to storage
            $path = $file->store('road_images', 'public');

            // Call Python AI API
            $response = Http::timeout(30)->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post('http://127.0.0.1:8001/analyze');

            if (!$response->successful()) {
                \Log::error('Python AI Service returned non-successful response: ' . $response->status() . ' - ' . $response->body());
                if ($request->wantsJson() || $request->input('json') == 1) {
                    return response()->json(['error' => 'AI service error: ' . $response->body()], 500);
                }
                return back()->withErrors(['ai' => 'AI service returned an error. Make sure the Python API is running.']);
            }

            $result = $response->json();

            // Generate a unique scan ID
            $year = date('Y');
            $latestAnalysis = RoadAnalysis::where('scan_id', 'like', "#RH-{$year}-%")
                ->orderBy('id', 'desc')
                ->first();

            $nextNum = 1;
            if ($latestAnalysis) {
                $parts = explode('-', $latestAnalysis->scan_id);
                if (count($parts) === 3) {
                    $nextNum = ((int) $parts[2]) + 1;
                }
            }

            do {
                $scanId = '#RH-' . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                $exists = RoadAnalysis::where('scan_id', $scanId)->exists();
                if ($exists) {
                    $nextNum++;
                }
            } while ($exists);

            $user = auth()->user();

            // Save to database
            $analysis = RoadAnalysis::create([
                'scan_id'           => $scanId,
                'image_path'        => $path,
                'original_filename' => $file->getClientOriginalName(),
                'location'          => $location,
                'latitude'          => $latitude,
                'longitude'         => $longitude,
                'condition'         => $result['condition']         ?? 'Unknown',
                'pci_score'         => $result['pci_score']         ?? 0,
                'severity'          => $result['severity']          ?? 'Unknown',
                'recommended_action'=> $result['recommended_action'] ?? '',
                'total_defects'     => $result['total_defects']     ?? 0,
                'detections'        => $result['detections']        ?? [],
                'api_mode'          => $result['mode']              ?? 'demo',
                'user_id'           => $user ? $user->id : null,
                'territory'         => $user ? $user->territory : null,
                'zone'              => $user ? $user->zone : null,
                'ward'              => $user ? $user->ward : null,
                'area'              => $user ? $user->area : null,
            ]);

            if ($request->wantsJson() || $request->input('json') == 1) {
                $analysis->detections_decoded = $result['detections'] ?? [];
                return response()->json($analysis);
            }

            return view('analysis-result', compact('analysis'));

        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Validation failed for analyze route: ' . json_encode($ve->errors()));
            if ($request->wantsJson() || $request->input('json') == 1) {
                return response()->json(['error' => 'Validation failed', 'messages' => $ve->errors()], 422);
            }
            throw $ve;
        } catch (\Exception $e) {
            \Log::error('AI Controller Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            if ($request->wantsJson() || $request->input('json') == 1) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors([
                'ai' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show analysis history (reports).
     */
    public function reports(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        if ($request->wantsJson() || $request->input('json') == 1) {
            if ($request->input('all') == 1) {
                if ($user->role === 'admin') {
                    $all = RoadAnalysis::latest()->get();
                } elseif ($user->role === 'citizen') {
                    $all = RoadAnalysis::where('user_id', $user->id)->latest()->get();
                } else {
                    $all = RoadAnalysis::where('territory', $user->territory)->latest()->get();
                }
                foreach ($all as $item) {
                    $item->detections_decoded = $item->detections;
                    $item->image_url = $item->image_path ? asset('storage/' . $item->image_path) : null;
                }
                return response()->json($all);
            }

            if ($user->role === 'admin') {
                $latest = RoadAnalysis::latest()->first();
            } elseif ($user->role === 'citizen') {
                $latest = RoadAnalysis::where('user_id', $user->id)->latest()->first();
            } else {
                $latest = RoadAnalysis::where('territory', $user->territory)->latest()->first();
            }
            if ($latest) {
                $latest->detections_decoded = $latest->detections;
                $latest->image_url = asset('storage/' . $latest->image_path);
            }
            return response()->json($latest);
        }

        if ($user->role === 'admin') {
            $baseQuery = RoadAnalysis::query();
        } elseif ($user->role === 'citizen') {
            $baseQuery = RoadAnalysis::where('user_id', $user->id);
        } else {
            $baseQuery = RoadAnalysis::where('territory', $user->territory);
        }

        // Fetch paginated analyses
        $analyses = (clone $baseQuery)->with('user')->latest()->paginate(10);

        // Calculate dynamic dashboard stats
        $totalAnalyses = (clone $baseQuery)->count();
        $avgPciScore = round((clone $baseQuery)->avg('pci_score') ?? 0);
        
        $worstRecord = (clone $baseQuery)->orderBy('pci_score', 'asc')->first();
        $worstCondition = $worstRecord ? strtoupper($worstRecord->condition) : 'POOR';
        $worstDate = $worstRecord ? $worstRecord->created_at->format('d M Y') : date('d M Y');

        $criticalAlerts = (clone $baseQuery)->where('pci_score', '<', 55)->count();

        // Condition distribution counts (matching UI categories)
        $goodCount = (clone $baseQuery)->where('pci_score', '>=', 75)->count();
        $fairCount = (clone $baseQuery)->where('pci_score', '>=', 55)->where('pci_score', '<', 75)->count();
        $poorCount = (clone $baseQuery)->where('pci_score', '>=', 35)->where('pci_score', '<', 55)->count();
        $criticalCount = (clone $baseQuery)->where('pci_score', '<', 35)->count();

        // Recent activity items (latest 3)
        $recentActivities = (clone $baseQuery)->latest()->take(3)->get();

        return view('reports', compact(
            'analyses', 
            'totalAnalyses', 
            'avgPciScore', 
            'worstCondition', 
            'worstDate', 
            'criticalAlerts',
            'goodCount',
            'fairCount',
            'poorCount',
            'criticalCount',
            'recentActivities'
        ));
    }

    /**
     * Show the Road Network map.
     */
    public function network()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $analyses = RoadAnalysis::whereNotNull('latitude')->whereNotNull('longitude')->get();
        } elseif ($user->role === 'citizen') {
            $analyses = RoadAnalysis::where('user_id', $user->id)->whereNotNull('latitude')->whereNotNull('longitude')->get();
        } else {
            $analyses = RoadAnalysis::where('territory', $user->territory)->whereNotNull('latitude')->whereNotNull('longitude')->get();
        }
        return view('network', compact('analyses'));
    }

    /**
     * View a single report.
     */
    public function show(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $analysis = RoadAnalysis::with(['maintenanceTask.taskActivities.user', 'maintenanceTask.assignedStaff'])->findOrFail($id);
        } elseif ($user->role === 'citizen') {
            $analysis = RoadAnalysis::where('user_id', $user->id)->with(['maintenanceTask.taskActivities.user', 'maintenanceTask.assignedStaff'])->findOrFail($id);
        } else {
            $analysis = RoadAnalysis::where('territory', $user->territory)->with(['maintenanceTask.taskActivities.user', 'maintenanceTask.assignedStaff'])->findOrFail($id);
        }
        if ($request->wantsJson() || $request->input('json') == 1) {
            $analysis->detections_decoded = $analysis->detections;
            $analysis->image_url = asset('storage/' . $analysis->image_path);
            return response()->json($analysis);
        }
        return view('analysis-result', compact('analysis'));
    }

    /**
     * Delete an analysis report.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $analysis = RoadAnalysis::findOrFail($id);
        } elseif ($user->role === 'citizen') {
            $analysis = RoadAnalysis::where('user_id', $user->id)->findOrFail($id);
        } else {
            $analysis = RoadAnalysis::where('territory', $user->territory)->findOrFail($id);
        }
        if ($analysis->image_path) {
            Storage::disk('public')->delete($analysis->image_path);
        }
        $analysis->delete();
        return redirect()->route('reports')->with('success', 'Analysis report deleted successfully.');
    }

    /**
     * Register a road analysis problem.
     */
    public function registerProblem(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user && $user->role === 'citizen') {
                $analysis = RoadAnalysis::where('user_id', $user->id)->findOrFail($id);
            } else {
                $analysis = RoadAnalysis::findOrFail($id);
            }

            $data = $request->validate([
                'title'     => 'required|string|max:255',
                'location'  => 'required|string|max:255',
                'landmark'  => 'nullable|string|max:255',
                'remarks'   => 'nullable|string',
                'territory' => 'required|string|max:255',
            ]);

            $analysis->update([
                'title'         => $data['title'],
                'location'      => $data['location'],
                'landmark'      => $data['landmark'],
                'remarks'       => $data['remarks'],
                'territory'     => $data['territory'],
                'is_registered' => true,
            ]);

            // Notify officers and admins in the same territory
            try {
                $territory = $analysis->territory ?: ($user ? $user->territory : null);
                if ($territory) {
                    $officers = User::whereIn('role', ['officer', 'admin'])
                        ->where('territory', $territory)
                        ->get();
                } else {
                    $officers = User::whereIn('role', ['officer', 'admin'])->get();
                }
                if ($officers->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($officers, new \App\Notifications\NewRoadAnalysisNotification($analysis));
                }
            } catch (\Exception $ne) {
                \Log::error('Failed to send notification on register: ' . $ne->getMessage());
            }

            if ($request->wantsJson() || $request->input('json') == 1) {
                return response()->json(['success' => true, 'analysis' => $analysis]);
            }

            return redirect()->route('reports.show', $analysis->id)->with('success', 'Problem registered and forwarded to municipal officers successfully.');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            if ($request->wantsJson() || $request->input('json') == 1) {
                return response()->json(['error' => 'Validation failed', 'messages' => $ve->errors()], 422);
            }
            throw $ve;
        } catch (\Exception $e) {
            \Log::error('AI Controller Register Exception: ' . $e->getMessage());
            if ($request->wantsJson() || $request->input('json') == 1) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle chat assistant requests.
     */
    public function chat(Request $request)
    {
        $user = auth()->user();
        $query = $request->input('query');
        if (!$query) {
            return response()->json(['response' => 'Please provide a query.']);
        }

        if ($user->role === 'citizen') {
            $baseQuery = RoadAnalysis::where('user_id', $user->id);
        } elseif ($user->role === 'admin') {
            $baseQuery = RoadAnalysis::query();
        } else {
            $baseQuery = RoadAnalysis::where('territory', $user->territory);
        }

        // Build context from recent data
        $totalAnalyses = (clone $baseQuery)->count();
        $avgPciScore = round((clone $baseQuery)->where('condition', '!=', 'Invalid')->avg('pci_score') ?? 0);
        $worstRecord = (clone $baseQuery)->where('condition', '!=', 'Invalid')->orderBy('pci_score', 'asc')->first();
        $worstRoadStr = $worstRecord ? "{$worstRecord->location} (PCI: {$worstRecord->pci_score}, {$worstRecord->condition})" : "N/A";
        
        $criticalAlerts = (clone $baseQuery)->where('pci_score', '<', 55)->where('condition', '!=', 'Invalid')->count();
        
        $context = "Total Inspections: {$totalAnalyses}\n";
        $context .= "Average PCI Score: {$avgPciScore}\n";
        $context .= "Lowest PCI Road: {$worstRoadStr}\n";
        $context .= "Critical Issues Count: {$criticalAlerts}\n";
        
        // Proxy to Python API
        try {
            $response = Http::timeout(30)->post('http://127.0.0.1:8001/chat', [
                'query' => $query,
                'context' => $context
            ]);

            if ($response->successful()) {
                return response()->json(['response' => $response->json()['response'] ?? 'No response.']);
            }
            return response()->json(['response' => 'Error communicating with AI service.']);
        } catch (\Exception $e) {
            return response()->json(['response' => 'Cannot connect to AI service. Ensure Python API is running.']);
        }
    }

    /**
     * Show the main dashboard with live data.
     */
    public function dashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect('/login');
        }

        // Territory-based scoping
        if ($user->role === 'admin') {
            $baseQuery = RoadAnalysis::query();
        } elseif ($user->role === 'citizen') {
            $baseQuery = RoadAnalysis::where('user_id', $user->id);
        } else {
            $baseQuery = RoadAnalysis::where('territory', $user->territory)->where('is_registered', true);
        }

        $totalAnalyses = (clone $baseQuery)->count();
        $avgPciScore = round((clone $baseQuery)->avg('pci_score') ?? 0);
        $highRiskCount = (clone $baseQuery)->where('pci_score', '<', 55)->count();
        $recentAnalyses = (clone $baseQuery)->with('user')->latest()->take(5)->get();
        
        $goodCount = (clone $baseQuery)->where('pci_score', '>=', 75)->count();
        $fairCount = (clone $baseQuery)->where('pci_score', '>=', 55)->where('pci_score', '<', 75)->count();
        $poorCount = (clone $baseQuery)->where('pci_score', '<', 55)->count();
        
        $goodPercent = $totalAnalyses > 0 ? round(($goodCount / $totalAnalyses) * 100) : 0;
        $fairPercent = $totalAnalyses > 0 ? round(($fairCount / $totalAnalyses) * 100) : 0;
        $poorPercent = $totalAnalyses > 0 ? round(($poorCount / $totalAnalyses) * 100) : 0;

        // Role-specific data payloads
        $payload = compact(
            'totalAnalyses', 
            'avgPciScore', 
            'highRiskCount', 
            'recentAnalyses',
            'goodPercent',
            'fairPercent',
            'poorPercent'
        );

        if ($user->role === 'admin') {
            $payload['pendingOfficers'] = User::where('role', 'officer')
                ->where('status', 'pending')
                ->get();
            
            $payload['pendingUsers'] = User::whereIn('role', ['officer', 'staff'])
                ->where('status', 'pending')
                ->latest()
                ->get();

            $payload['processedUsers'] = User::whereIn('role', ['officer', 'staff'])
                ->whereIn('status', ['approved', 'rejected'])
                ->latest('updated_at')
                ->get();
            
            $payload['allOfficers'] = User::where('role', 'officer')->latest()->get();
            $payload['allStaff'] = User::where('role', 'staff')->latest()->get();

            $payload['escalatedTasks'] = MaintenanceTask::whereNotIn('status', ['completed', 'approved'])
              ->whereNotNull('deadline')
              ->where('deadline', '<', now())
              ->get();

            $payload['zoneCounts'] = RoadAnalysis::select('zone', \DB::raw('count(*) as total'))
                ->groupBy('zone')
                ->get();

            $payload['recentActivities'] = TaskActivity::latest()->take(10)->get();

            // Contact & Inter-Municipal Communications (Admin bypass gets all)
            $payload['incomingCitizenMessages'] = ContactMessage::where('type', 'citizen_to_officer')
                ->latest()
                ->get();
            $payload['incomingOfficerMessages'] = ContactMessage::where('type', 'officer_to_officer')
                ->latest()
                ->get();
            $payload['sentOfficerMessages'] = ContactMessage::where('sender_id', $user->id)
                ->latest()
                ->get();
            $payload['activeTasks'] = MaintenanceTask::whereIn('status', ['assigned', 'started', 'paused', 'correction'])
                ->with(['roadAnalysis.user', 'assignedStaff', 'taskActivities.user'])
                ->latest()
                ->get();
        }

        if ($user->role === 'officer') {
            $payload['pendingStaff'] = User::where('role', 'staff')
                ->where('status', 'pending')
                ->where('territory', $user->territory)
                ->get();

            $payload['pendingUsers'] = User::where('role', 'staff')
                ->where('status', 'pending')
                ->where('territory', $user->territory)
                ->latest()
                ->get();

            $payload['processedUsers'] = User::where('role', 'staff')
                ->where('territory', $user->territory)
                ->whereIn('status', ['approved', 'rejected'])
                ->latest('updated_at')
                ->get();

            $payload['activeStaff'] = User::where('role', 'staff')
                ->where('status', 'approved')
                ->where('territory', $user->territory)
                ->get();

            $payload['unassignedComplaints'] = RoadAnalysis::where('territory', $user->territory)
                ->where(function($query) {
                    $query->where('is_registered', true)
                          ->orWhere(function($q) {
                              $q->whereNotNull('user_id')
                                ->where('pci_score', '<', 75)
                                ->whereNotIn('condition', ['Invalid', 'invalid']);
                          });
                })
                ->with('user')
                ->whereNotExists(function($q){
                    $q->select(\DB::raw(1))->from('maintenance_tasks')->whereRaw('maintenance_tasks.road_analysis_id = road_analyses.id');
                })->latest()->get();

            $payload['completedTasks'] = MaintenanceTask::whereHas('roadAnalysis', function($q) use($user){
                $q->where('territory', $user->territory);
            })->where('status', 'completed')->latest()->get();

            $payload['activeTasks'] = MaintenanceTask::whereHas('roadAnalysis', function($q) use($user){
                $q->where('territory', $user->territory);
            })->whereIn('status', ['assigned', 'started', 'paused', 'correction'])
                ->with(['roadAnalysis.user', 'assignedStaff', 'taskActivities.user'])
                ->latest()
                ->get();

            // Contact & Inter-Municipal Communications (Territory restricted)
            $payload['incomingCitizenMessages'] = ContactMessage::where('type', 'citizen_to_officer')
                ->where('territory', $user->territory)
                ->latest()
                ->get();
            $payload['incomingOfficerMessages'] = ContactMessage::where('type', 'officer_to_officer')
                ->where('territory', $user->territory)
                ->latest()
                ->get();
            $payload['sentOfficerMessages'] = ContactMessage::where('sender_id', $user->id)
                ->latest()
                ->get();
        }

        if ($user->role === 'staff') {
            $payload['myTasks'] = MaintenanceTask::where('assigned_to', $user->id)
                ->with(['roadAnalysis.user', 'taskActivities.user'])
                ->orderBy('priority', 'asc')
                ->get();
        }

        if ($user->role === 'citizen') {
            $payload['myReports'] = RoadAnalysis::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('dashboard', $payload);
    }
}
