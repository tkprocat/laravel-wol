<?php

namespace LaravelWOL\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LaravelWOL\User;
use LaravelWOL\API\Transformers\UserTransformer;

class UserController extends BaseController
{
    public function __construct()
    {
       // $this->middleware('jwt.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->collection(User::all(), new UserTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['username' => 'required|max:255',
                        'email' => 'required|email|max:255|unique:users',
                        'password' => 'required|confirmed|min:8']);
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->item(User::findOrFail($id), new UserTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['username' => 'required|max:255', 
                         'email' => 'required|email|max:255|unique:users,id'.$id]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();
        return $user;
    }

    /**
     * Change password for the given user.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $id)
    {
        $this->validate($request, ['old_password' => 'required',
            'password' => 'required|max:255|confirmed']);

        $user = User::findOrFail($id);
        //Check if the old password matches what we have registered.
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return $user;
        } else {
            return response()->json(['error' => 'Old password mismatch.'], 422);
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
        return User::destroy($id);
    }
}
