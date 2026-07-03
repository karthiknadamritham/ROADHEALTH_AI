<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Show password confirmation form.
     */
    public function showConfirmForm()
    {
        return view('settings-confirm');
    }

    /**
     * Confirm user's password before entering settings.
     */
    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (Hash::check($request->password, auth()->user()->password)) {
            session(['settings_verified_at' => now()->timestamp]);
            return redirect()->route('settings');
        }

        return back()->withErrors(['password' => 'The password you entered is incorrect.']);
    }

    /**
     * Display settings page.
     */
    public function index()
    {
        // Check if session has valid verification time (15 minutes = 900 seconds)
        $verifiedAt = session('settings_verified_at');
        if (!$verifiedAt || (now()->timestamp - $verifiedAt > 900)) {
            return redirect()->route('settings.confirm');
        }

        $user = auth()->user();
        return view('settings', compact('user'));
    }

    /**
     * Update user details and/or password.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Check if session is verified
        $verifiedAt = session('settings_verified_at');
        if (!$verifiedAt || (now()->timestamp - $verifiedAt > 900)) {
            return redirect()->route('settings.confirm');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'required|string|max:20',
            'territory' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|max:5120',
        ];

        if (in_array($user->role, ['officer', 'staff'])) {
            $rules['employee_id'] = 'required|string|max:100';
            $rules['department'] = 'required|string|max:255';
            $rules['government_id'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240';
        }

        // Only validate password update if new password fields are filled
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }

        $data = $request->validate($rules);

        // Verify current password if updating password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle profile photo update
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $request->file('profile_photo')->store('uploads/profiles', 'public');
        }

        // Handle government id update
        if ($request->hasFile('government_id')) {
            if ($user->government_id_path) {
                Storage::disk('public')->delete($user->government_id_path);
            }
            $user->government_id_path = $request->file('government_id')->store('uploads/ids', 'public');
        }

        // Update other attributes
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->territory = $data['territory'];
        $user->zone = $data['zone'];
        $user->ward = $data['ward'];
        $user->area = $data['area'];

        if (in_array($user->role, ['officer', 'staff'])) {
            $user->employee_id = $data['employee_id'];
            $user->department = $data['department'];
        }

        $user->save();

        // Refresh settings verified time
        session(['settings_verified_at' => now()->timestamp]);

        return redirect()->route('settings')->with('success', 'Profile and settings updated successfully.');
    }
}
