<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FormController extends Controller
{
    public function create()
    {
        return view('createUser');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        if ($request->ajax()) {
            return response()->json([
                'success' => 'User created successfully!'
            ]);
        }

        return back()->with('success', 'User created!');
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(!$exists);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => 'User updated successfully!'
        ]);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted');
    }

    public function list(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->get();

        return view('usersList', compact('users'));
    }
}