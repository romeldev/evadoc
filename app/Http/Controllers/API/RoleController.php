<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\RoleResource;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::where('name', 'like', "%$request->search%")->latest()->paginate(10);
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,NULL,id',
            'permissions' => 'required|array',
        ]);

        DB::beginTransaction();
        try{
            $role = new Role;
            $role->name = $request->name;
            $role->save();

            if( $request->permissions ){
                $permissions = Permission::whereIn('id', $request->permissions )->pluck('name')->toArray();
                $role->givePermissionTo($permissions);
            }

            DB::commit();

            return response()->json($role);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Role::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$id,
        ]);

        DB::beginTransaction();
        try{
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();
            if( $request->permissions ){
                $permissions = Permission::whereIn('id', $request->permissions )->pluck('name')->toArray();
                $role->syncPermissions($permissions);
            }
            DB::commit();

            return response()->json($role);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $role = role::findOrFail($id);
            $role->permissions()->delete();
            $role->delete();
            DB::commit();

            return response()->json($role);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json( $e->getMessage(), 500 );
        }
    }
}
