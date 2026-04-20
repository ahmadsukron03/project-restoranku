<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('role_name', '!=', 'customer');
        })->orderBy('created_at', 'desc')->get();

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('role_name', '!=', 'customer')->get();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'fullname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'phone' => 'nullable|string|max:20',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'fullname.required' => 'Full name is required.',
                'fullname.string' => 'Full name must be a string.',
                'fullname.max' => 'Full name must not exceed 255 characters.',

                'username.required' => 'Username is required.',
                'username.string' => 'Username must be a string.',
                'username.max' => 'Username must not exceed 255 characters.',
                'username.unique' => 'Username is already taken.',

                'phone.string' => 'Phone number must be a string.',
                'phone.max' => 'Phone number must not exceed 20 characters.',

                'email.required' => 'Email is required.',
                'email.email' => 'Email format is not valid.',
                'email.unique' => 'Email is already registered.',

                'password.required' => 'Password is required.',
                'password.string' => 'Password must be a string.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Password confirmation does not match.',

                'role_id.required' => 'Role is required.',
                'role_id.exists' => 'Selected role is invalid.',
            ]
        );

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User Created Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('role_name', '!=', 'customer')->get();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate(
            [
                'fullname' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users', 'username')->ignore($user->id),
                ],
                'phone' => 'nullable|string|max:20',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'confirmed',
                    function ($attribute, $value, $fail) use ($user) {
                        if ($value && Hash::check($value, $user->password)) {
                            $fail('Password baru tidak boleh sama dengan password lama');
                        }
                    }
                ],
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'fullname.required' => 'Full name is required.',
                'fullname.string' => 'Full name must be a string.',
                'fullname.max' => 'Full name must not exceed 255 characters.',

                'username.required' => 'Username is required.',
                'username.string' => 'Username must be a string.',
                'username.max' => 'Username must not exceed 255 characters.',
                'username.unique' => 'Username is already taken.',

                'phone.string' => 'Phone number must be a string.',
                'phone.max' => 'Phone number must not exceed 20 characters.',

                'email.required' => 'Email is required.',
                'email.email' => 'Email format is not valid.',
                'email.unique' => 'Email is already registered.',

                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Password confirmation does not match.',

                'role_id.required' => 'Role is required.',
                'role_id.exists' => 'Selected role is invalid.',
            ]
        );

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User Deleted successfully.');
    }
}
