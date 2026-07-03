<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/features', function () {
    return view('features');
});

Route::get('/how-it-works', function () {
    return view('how-it-works');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [AIController::class, 'dashboard'])->name('dashboard');
});

Route::get('/ai-chat', function () {
    return view('chat');
});

Route::get('/pricing', function () {
    return view('pricing');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', [ContactController::class, 'storeCitizenMessage'])->name('contact.store');

Route::get('/login', function () {
    return view('login', ['hideFooter' => true]);
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'role' => ['required', 'in:citizen,staff,officer,admin'],
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ], $request->boolean('remember'))) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role !== $request->role) {
            \Illuminate\Support\Facades\Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'The credentials belong to a different role. Please select the correct role.'
            ], 403);
        }
        $request->session()->regenerate();
        return response()->json(['success' => true]);
    }

    return response()->json([
        'success' => false,
        'message' => 'The provided credentials do not match our records.'
    ], 401);
});

Route::get('/register', function () {
    return view('register', ['hideFooter' => true]);
});

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'phone' => 'required|string|max:20',
        'territory' => 'required|string|max:255',
        'zone' => 'required|string|max:255',
        'ward' => 'required|string|max:255',
        'area' => 'required|string|max:255',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:admin,officer,staff,citizen'
    ];
    
    $role = $request->input('role');
    if (in_array($role, ['officer', 'staff'])) {
        $rules['employee_id'] = 'required|string|max:100';
        $rules['department'] = 'required|string|max:255';
        $rules['government_id'] = 'required|file|mimes:jpeg,jpg,png,pdf|max:10240';
        $rules['profile_photo'] = 'required|image|max:5120';
    }
    
    $data = $request->validate($rules);
    
    $govIdPath = null;
    $profilePhotoPath = null;
    
    if ($request->hasFile('government_id')) {
        $govIdPath = $request->file('government_id')->store('uploads/ids', 'public');
    }
    
    if ($request->hasFile('profile_photo')) {
        $profilePhotoPath = $request->file('profile_photo')->store('uploads/profiles', 'public');
    }
    
    $status = in_array($role, ['officer', 'staff']) ? 'pending' : 'approved';
    
    $user = \App\Models\User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        'role' => $role,
        'phone' => $data['phone'],
        'territory' => $data['territory'],
        'zone' => $data['zone'],
        'ward' => $data['ward'],
        'area' => $data['area'],
        'employee_id' => $data['employee_id'] ?? null,
        'department' => $data['department'] ?? null,
        'government_id_path' => $govIdPath,
        'profile_photo_path' => $profilePhotoPath,
        'status' => $status,
    ]);
    
    // Auto-login registered user
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Trigger notification if officer or staff is pending
    if ($status === 'pending') {
        // We'll notify admins (for officers) or officers (for staff) in the next steps
    }
    
    return response()->json(['success' => true]);
});

Route::get('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// ─── AI Analysis Routes ───────────────────────────────────────────────────────
Route::post('/analyze',              [AIController::class, 'analyze'])->name('analyze');
Route::post('/reports/{id}/register', [AIController::class, 'registerProblem'])->name('reports.register');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/upload',                [AIController::class, 'upload'])->name('upload');
    Route::post('/chat',                 [AIController::class, 'chat'])->name('chat');
    Route::get('/reports',               [AIController::class, 'reports'])->name('reports');
    Route::get('/reports/{id}',          [AIController::class, 'show'])->name('reports.show');
    Route::delete('/reports/{id}',       [AIController::class, 'destroy'])->name('reports.destroy');
    Route::get('/dashboard/report-export', function () { return view('report-export'); })->name('report.export');
    Route::get('/network',               [AIController::class, 'network'])->name('network');
    Route::get('/notifications',         [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markRead');
    
    Route::get('/dashboard/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::post('/dashboard/maintenance/assign', [MaintenanceController::class, 'assign'])->name('maintenance.assign');
    Route::post('/dashboard/approve-user/{id}', [MaintenanceController::class, 'approveUser'])->name('approve-user');
    Route::post('/dashboard/maintenance/update-status/{id}', [MaintenanceController::class, 'updateStatus'])->name('maintenance.update-status');
    Route::post('/dashboard/maintenance/progress/{id}', [MaintenanceController::class, 'addProgressUpdate'])->name('maintenance.progress');
    Route::post('/dashboard/maintenance/verify/{id}', [MaintenanceController::class, 'verifyTask'])->name('maintenance.verify');
    
    // Contact & Inter-Municipal routes
    Route::post('/dashboard/contact/officer', [ContactController::class, 'storeOfficerMessage'])->name('contact.officer.store');
    Route::post('/dashboard/contact/reply/{id}', [ContactController::class, 'replyMessage'])->name('contact.reply');

    // Settings routes
    Route::get('/settings/confirm', [SettingsController::class, 'showConfirmForm'])->name('settings.confirm');
    Route::post('/settings/confirm', [SettingsController::class, 'confirmPassword']);
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/pending-verification', function () {
        return view('pending-verification');
    })->name('pending-verification');
});

Route::redirect('/maintenance', '/dashboard/maintenance');


