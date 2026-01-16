<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'profile');

        return view('settings.index', [
            'user' => Auth::user(),
            'active' => $tab,
        ]);
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.Auth::id()],
        ]);

        Auth::user()->update($validated);

        flash()->success('Perfil atualizado com sucesso!');

        return redirect()->route('settings.index', ['tab' => 'profile']);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        flash()->success('Senha atualizada com sucesso!');

        return redirect()->route('settings.index', ['tab' => 'password']);
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        // Logout before deleting
        Auth::logout();

        // Delete user (cascade deletes related clients)
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
