<?php

namespace App\Http\Controllers\API\V1\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


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
            'email' => 'required|email',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin;
        $user->status = $request->status;
        $user->save();
    }

    
    
}
