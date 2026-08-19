<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function approve(User $user): RedirectResponse
    {
        $user->forceFill(['approved_at' => now()])->save();

        return back()->with('success', 'Пользователь подтверждён.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'Пользователь обновлён.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors(['user' => 'Нельзя удалить собственный аккаунт.']);
        }

        $user->delete();

        return back()->with('success', 'Пользователь удалён.');
    }
}
