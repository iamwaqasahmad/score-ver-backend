<?php

namespace App\Http\Controllers\API\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;


class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(User::all());
    }

    public function getUserById($id)
    {
        return $this->sendResponse(User::find($id));

    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'is_admin' => 'required | numeric'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = User::find($id);
        $user->name = $request->name;
        //$user->email = $request->email; // email should not be changed
        $user->is_admin = $request->is_admin;
        $user->is_activated = $request->is_activated;
        $user->save();
        return $this->sendResponse($user->id);
    }

    
    
}
