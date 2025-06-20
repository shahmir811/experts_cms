<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:delete user', ['only'=> ['destroy']]);
        $this->middleware('permission:update user', ['only'=> ['edit','update']]);
        $this->middleware('permission:create user', ['only'=> ['create','store']]);
        $this->middleware('permission:view user', ['only'=> ['index']]); // can add show here
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::withTrashed()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name', 'ASC')
            ->paginate(10); // 10 users per page
        
        return view("pages.role-permission.user.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view("pages.role-permission.user.create", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|string|min:6",
            "role" => "required",
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $user->syncRoles($request->role);

        return redirect()->route("users.index")->with("status", "User created successfully");
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
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        // Permissions inherited from roles
        $rolePermissions = $user->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique();

        // Permissions assigned directly
        $directPermissions = $user->getDirectPermissions()->pluck('name');

        return view("pages.role-permission.user.edit", compact(
            "user", "roles", "permissions", "rolePermissions", "directPermissions"
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "password" => "nullable|string|min:6",
            "role" => "required",
        ]);

        $data = ["name" => $request->name];

        if (!empty($request->password)) {
            $data["password"] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles($request->role);

        // Get permissions attached to role
        $rolePermissions = $user->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique();

        // Remove inherited ones before syncing
        $directPermissions = collect($request->permissions)->filter(function ($perm) use ($rolePermissions) {
            return !$rolePermissions->contains($perm);
        });

        $user->syncPermissions($directPermissions);

        return redirect()->route("users.index")->with("status", "User updated successfully");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->roles()->detach();
        $user->delete();
        return redirect()->route("users.index")->with("status", "User deleted successfully");
    }

    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            $status = 'activated';
        } else {
            $user->delete();
            $status = 'deactivated';
        }

        return redirect()->route('users.index')->with('status', "User {$status} successfully.");
    }

}
