<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List users with pagination and sorting
    public function index(Request $request)
    {
        $sortField = $request->get('sort_by', 'id');
        $sortOrder = $request->get('order', 'asc');

        $allowedFields = ['id', 'name', 'email', 'is_admin', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }

        $users = User::orderBy($sortField, $sortOrder)->get();

        return view('admin.users.index', compact('users', 'sortField', 'sortOrder'));
    }


    // Show user edit form
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user (e.g. admin status)
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'is_admin' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'email', 'is_admin']);

        // Only update password if it was provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();


        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
