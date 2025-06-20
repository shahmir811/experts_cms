<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:delete role', ['only'=> ['destroy']]);
        $this->middleware('permission:update role', ['only'=> ['edit','update', 'addPermissionsToRole', 'savePermissionsToRole']]);
        $this->middleware('permission:create role', ['only'=> ['create','store']]);
        $this->middleware('permission:view role', ['only'=> ['index']]); // can add show here
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $roles = Role::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10); // 10 roles per page
        
        return view('pages.role-permission.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.role-permission.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate(
                [
                    'name' => [
                        'required',
                        'string',
                        'unique:roles,name',
                    ],
                ],
                [
                    'name.unique' => 'This role name already exists.',
                ]
            );

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('status', 'Role created successfully');
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
    public function edit(Role $role)
    {
        return view('pages.role-permission.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $role->id
            ],
        ], [
            'name.unique' => 'This role name already exists.',
        ]);
        $role->update($request->all());
        return redirect()->route('roles.index')->with('status', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('status', 'Role deleted successfully');
    }  

    public function addPermissionsToRole($slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail(); 
        $permissions = Permission::all();

        return view('pages.role-permission.role.add-permissions', compact('role', 'permissions'));
    }

    public function savePermissionsToRole(Request $request, $slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();

        $request->validate([
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,slug',
        ], [
            'permissions.required' => 'You must assign at least one permission.',
            'permissions.min' => 'You must select at least one permission.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ]);

        $permissions = Permission::whereIn('slug', $request->permissions)->get();

        $role->syncPermissions($permissions);

        return redirect()->back()->with('status', 'Permissions given successfully');
    }

    
}
