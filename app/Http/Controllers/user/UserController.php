<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function showProfile()
    {
        
        return view('user.profile');
    }

    public function updateProfile(Request $request)
        {
            $user = Auth::user();
            $successMessages = [];

            // update name
            if ($request->filled('name') && $request->name !== $user->name) {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                ]);
                $user->name = $request->name;
                $successMessages[] = 'Name updated successfully!';
            }

            // update email
            if ($request->filled('email') && $request->email !== $user->email) {
                $request->validate([
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                ]);

                $user->email = $request->email;
                
                $user->email_verified_at = null;
                
                $successMessages[] = 'Email updated successfully! Dont forget to verify your email.';
            }

            // update Password
            if ($request->filled('new_password')) {
                $request->validate([
                    'current_password' => ['required', 'current_password'],
                    'new_password' => ['required', Password::defaults(), 'confirmed'],
                ]);

                $user->password = Hash::make($request->new_password);
                $successMessages[] = 'Password changed successfully!';
            }

            if ($user->isDirty()) {
                $user->save();

                if ($user->wasChanged('email') && $user instanceof MustVerifyEmail) {
                    $user->sendEmailVerificationNotification();
                }

                if (count($successMessages) > 0) {
                    $notification = implode(' ', $successMessages);
                    return redirect()->route('profile')->with('success', $notification);
                }
            }
        return redirect()->route('profile');
    }
}
