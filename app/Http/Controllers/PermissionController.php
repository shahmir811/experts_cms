<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:delete permission', ['only'=> ['destroy']]);
        $this->middleware('permission:update permission', ['only'=> ['edit','update']]);
        $this->middleware('permission:create permission', ['only'=> ['create','store']]);
        $this->middleware('permission:view permission', ['only'=> ['index']]); // can add show here
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $permissions = Permission::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10); // 10 permissions per page
        
        return view('pages.role-permission.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.role-permission.permission.create');
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
                        'unique:permissions,name',
                    ],
                ],
                [
                    'name.unique' => 'This permission name already exists.',
                ]
            );

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('status', 'Permission created successfully');
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
    public function edit(Permission $permission)
    {
        return view('pages.role-permission.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,' . $permission->id    
            ],
        ], [
            'name.unique' => 'This permission name already exists.',
        ]);
        $permission->update($request->all());
        return redirect()->route('permissions.index')->with('status', 'Permission updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('status', 'Permission deleted successfully');
    }
}
