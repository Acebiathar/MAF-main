<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Render the account configuration form page.
     */
    public function editSettings()
    {
        // Fetch the currently authenticated user session data
        $user = Auth::user();

        // Pass the user object to the view to populate form inputs
        return view('profile.settings', compact('user'));
    }

    /**
     * Validate and save the updated username or security credentials.
     */
    public function updateSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Enforce strict structural rules on incoming data
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password'     => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        // 2. Update the public display username tracking info
        $user->name = $request->input('name');

        // 3. Process password security adjustments only if fields are filled out
        if ($request->filled('new_password')) {
            
            // Check if the provided "Current Password" matches what's stored in the database
            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors([
                    'current_password' => 'The provided password does not match our records.'
                ]);
            }

            // Encrypt and assign the safe newly generated string
            $user->password = Hash::make($request->input('new_password'));
        }

        // Save mutations safely to the database
        $user->save();

        // Redirect back with a status token to show a success notification card
        return redirect()->route('profile.settings')->with('status', 'account-updated');
    }
}