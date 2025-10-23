<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $role = Role::create([
            'name' => $request->name
        ]);

        if($permissions = $request->input('permissions')) {
            foreach($permissions as $permission) {
                $role->permissions()->attach($permission);
            }
        }
        return response()->json($role, Response::HTTP_CREATED);
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
        $role->update($request->only(['name']));
        DB::table('role_permission')->where('role_id', $role->id)->delete();
        if($permissions = $request->input('permissions')) {
            foreach($permissions as $permission) {
                $role->permissions()->attach($permission);
            }
        }
        return response()->json($role, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::table('role_permission')->where('role_id', $role->id)->delete();
        $role->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
