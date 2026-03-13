<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', ''));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    if (ctype_digit($search)) {
                        $q->orWhere('id', (int) $search);
                    }

                    $q->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role !== '', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(\Illuminate\Http\Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:admin,user',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}
