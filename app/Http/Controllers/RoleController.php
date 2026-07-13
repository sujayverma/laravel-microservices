<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        if ($permissions = $request->input('permissions')) {
            $role->permissions()->sync($permissions);
        }
        // if ($permissions = $request->input('permissions')) {
        //     foreach ($permissions as $permissionId) {
        //         DB::table('role_permissions')->insert([
        //             'role_id' => $role->id,
        //             'permission_id' => $permissionId
        //         ]); 
        //     }
        // }
        return response()->json(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        return response()->json(new RoleResource(Role::find($id)), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        
        DB::table('role_permissions')->where('role_id', $role->id)->delete();
        if ($permissions = $request->input('permissions')) {
            $role->permissions()->sync($permissions);
        }
        $role->update($request->only(['name']));
        return response()->json(new RoleResource($role), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
        DB::table('role_permissions')->where('role_id', $role->id)->delete();
        $role->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
