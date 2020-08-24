<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\User;
use DB;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::search($request->search)->latest()->paginate(10);
        return UserResource::collection( $users );
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
            'fullname' => 'required',
            'name' => "required|unique:users,name,NULL,id,deleted_at,NULL",
            'email' => "required|email|unique:users,email,NULL,id,deleted_at,NULL",
            'password' => 'required|min:6',
            'photo' => 'sometimes',
            'roles' => 'required|array',
            'school_code' => 'required|integer',
            'school_name' => 'required',
        ]);

        $user = new User;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->school_code = $request->school_code;
        $user->school_name = $request->school_name;
        $user->password = Hash::make($request->password);

        DB::beginTransaction();
        try{

            if( $request->file('photo') ){
                $pathPhoto = Storage::disk('public')->put('images/users', $request->photo);
                $filename = explode('/', $pathPhoto)[2];
                $user->photo = $filename;
            }

            $user->save();

            if( $request->roles ){
                $roles = Role::whereIn('id', $request->roles )->pluck('name')->toArray();
                $user->assignRole($roles);
            }
            DB::commit();
            
            return response()->json($roles);

        }catch (\Exception $e) {
            DB::rollback();
            $error = "Error[{$e->getCode()}]: {$e->getMessage()}";
            return response()->json( $error, 500 );
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
        return new UserResource( User::findOrFail($id) );
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
        $user = User::findOrFail($id);

        if (trim($request->password)=='' ){
            unset( $request['password'] );
        }

        $this->validate($request, [
            'fullname' => 'required',
            'name' => "required|unique:users,name,{$id},id,deleted_at,NULL",
            'email' => "required|email|unique:users,email,{$id},id,deleted_at,NULL",
            'password' => 'sometimes|min:6',
            'photo' => 'sometimes',
            'roles' => 'sometimes|array',
            'school_code' => 'required|integer',
            'school_name' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $user->fullname = $request->fullname;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->school_code = $request->school_code;
            $user->school_name = $request->school_name;


            if( $request->file('photo') ){
                if( Storage::disk('public')->exists("images/users/$user->photo") ){
                    $remove = Storage::disk('public')->delete("images/users/$user->photo");
                }
                $pathPhoto = Storage::disk('public')->put('images/users', $request->photo);
                $filename = explode('/', $pathPhoto)[2];
                $user->photo = $filename;
            }

            if( $request->password ){
                $user->password = Hash::make($request->password);
            }
        
            $user->save();

            if( $request->roles ){
                $roles = Role::whereIn('id', $request->roles )->pluck('name')->toArray();
                $user->syncRoles($roles);
            }

            DB::commit();

            return response()->json($user);

        }catch (\Exception $e) {
            DB::rollback();
            $error = "Error[{$e->getCode()}]: {$e->getMessage()}";
            return response()->json( $error, 500 );
        }
    }

    public function updateProfile(Request $request)
    {
        $user = \Auth::user(); 

        $this->validate($request, [
            'fullname' => 'required',
            'name' => "required|unique:users,name,{$user->id},id,deleted_at,NULL",
            'email' => "required|email|unique:users,email,{$user->id},id,deleted_at,NULL",
            'password' => 'sometimes|min:6',
            'photo' => 'sometimes',
        ]);

        $user->fullname = $request->fullname;
        $user->name = $request->name;
        $user->email = $request->email;

        if( $request->password ) $user->password = $request->password;
        if( $request->photo ) $user->photo = $request->photo;


        if( $request->photo ){
            if( Storage::disk('public')->exists("images/users/$user->photo") ){
                $remove = Storage::disk('public')->delete("images/users/$user->photo");
            }
            $pathPhoto = Storage::disk('public')->put('images/users', $request->photo);
            $filename = explode('/', $pathPhoto)[2];
            $user->photo = $filename;
        }

        $user->save();

        return response()->json(\Auth::user() , 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if( Storage::disk('public')->exists("images/users/$user->photo") ){
            Storage::disk('public')->delete("images/users/$user->photo");
        }

        $user->delete();
        return $user;
    }
}
