<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Level;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('level')->latest()->paginate(10);
        return view('master.users.index', compact('users'));
    }

    public function create()
    {
        $levels = Level::all();
        return view('master.users.create', compact('levels'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        User::create($data);
        return redirect()->route('master.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $levels = Level::all();
        return view('master.users.edit', compact('user', 'levels'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('master.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('master.users.index')->with('success', 'User deleted successfully.');
    }
}
