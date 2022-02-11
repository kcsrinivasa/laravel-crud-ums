<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;

use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user')->withUsers(User::orderBy('id','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {

        $doj=$dol=$profile = null;
        $stillWorking = $request->stillWorking;
        if(!$stillWorking){$stillWorking = false;}

        if($request->doj){
            $doj = Carbon::parse($request->doj)->format('Y-m-d');
        }
        if($request->dol){
            $dol = Carbon::parse($request->dol)->format('Y-m-d');
            $stillWorking = false;
        }



        //if image uploaded
        if($request->hasFile('profile')){
            $file = $request->profile; 
            $fileOriginalName = $file->getClientOriginalName(); 
            $extension = $file->extension(); 
            $size = $file->getSize(); 
            $fileName = \Str::slug(substr($fileOriginalName,0,200)).time().'.'.$extension; 
            $filePath = 'uploads/profiles/'; 
            $file->move(public_path($filePath), $fileName); 
            $profile = $filePath.$fileName;
        }

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'doj'=>$doj,
            'dol'=>$dol,
            'stillWorking'=>$stillWorking,
            'profile'=>$profile
        ]);

        if($request->ajax()){
            return response(['message'=>'Successfully added user'],201);
        }

        session()->flash('success','Successfully added user');
        return redirect(route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response(['message'=>'user details','user'=>$user],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, StoreUserRequest $request)
    {
        $user = User::find($user_id);
        if(!$user){ return response(['error'=>'user not found'],404); }
        // dd($user);

        $doj=$dol= null;
        $profile = $user->profile;
        $stillWorking = $request->stillWorking;
        if(!$stillWorking){$stillWorking = false;}

        if($request->doj){
            $doj = Carbon::parse($request->doj)->format('Y-m-d');
        }
        if($request->dol){
            $dol = Carbon::parse($request->dol)->format('Y-m-d');
            $stillWorking = false;
        }


        //if image uploaded
        if($request->hasFile('profile')){
            // remove old profile
             if($user->profile){unlink(public_path($user->profile));}

            $file = $request->profile; 
            $fileOriginalName = $file->getClientOriginalName(); 
            $extension = $file->extension(); 
            $size = $file->getSize(); 
            $fileName = \Str::slug(substr($fileOriginalName,0,200)).time().'.'.$extension; 
            $filePath = 'uploads/profiles/'; 
            $file->move(public_path($filePath), $fileName); 
            $profile = $filePath.$fileName;
        }

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'doj'=>$doj,
            'dol'=>$dol,
            'stillWorking'=>$stillWorking,
            'profile'=>$profile
        ]);

        if($request->ajax()){
            return response(['message'=>'Successfully updated user'],200);
        }

        session()->flash('success','Successfully updated user');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        if($user->profile){unlink(public_path($user->profile));}
        $user->delete();

        session()->flash('success','Successfully deleted user');
        return redirect(route('user.index'));
    }
}
