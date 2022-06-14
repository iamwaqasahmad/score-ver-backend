<?php

namespace App\Http\Controllers\API\v1;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\v1\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use AppHttpControllersController;
use IlluminateFoundationAuthThrottlesLogins;
use IlluminateFoundationAuthAuthenticatesAndRegistersUsers;
use IlluminateHttpRequest;
use DB;
use Mail;
use App\Mail\VerifyEmail;


class AuthController extends BaseController
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $user = $this->create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        
        $this->sendEmail($user);
        return $this->sendResponse($success, 'User register successfully.');
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

   
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 

            if(auth()->user()->is_activated == '0'){
                return $this->sendError('notActivited',[
                    'token' => $success['token'],
                    'msg'=>'Please, Activite your account'], 409);
            }
            
            $success['name'] =  $user->name;
            $success['user_id'] =  $user->id;
            $success['role'] =  $user->is_admin == 1 ? 'admin' : 'user';
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();    

        return $this->sendResponse('check', 'logout');
    }

    public function userActivation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $token = $request->code;
        $user_id = Auth::id();
        $check = DB::table('user_activations')->where('token','=', $token)->where('user_id', '=', $user_id)->first();
        if(!is_null($check)){
            $user = User::find($user_id);
            if($user->is_activated == 1){
                return $this->sendResponse('User already activated.');
            }
            $user->is_activated = '1';
            $user->save();
            DB::table('user_activations')->where('token',$token)->delete();
            
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            $success['user_id'] =  $user->id;
            $success['role'] =  $user->is_admin == 1 ? 'admin' : 'user';
   
            return $this->sendResponse($success, 'User activated successfully.');
        }

        return $this->sendError('Unauthorised.', ['error'=>'invalid token']);

    }

    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $token = $request->email;

        $user_id = Auth::id();
        $check = DB::table('user_activations')->where('token','=', $token)->where('user_id', '=', $user_id)->first();
        if(!is_null($check)){
            $user = User::find($user_id);
            if($user->is_activated == 1){
                return $this->sendResponse('User already activated.');
            }
            $user->is_activated = '1';
            $user->save();
            DB::table('user_activations')->where('token',$token)->delete();
            
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            $success['user_id'] =  $user->id;
            $success['role'] =  $user->is_admin == 1 ? 'admin' : 'user';
   
            return $this->sendResponse($success, 'User activated successfully.');
        }

        return $this->sendError('Unauthorised.', ['error'=>'invalid token']);

    }


    public function resendVerificationCode()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        return $this->sendEmail($user);
    }

    private function sendEmail($userObj)
    {
        $user = $userObj->toArray();
        $user['link'] =  Str::random(5);
        DB::table('user_activations')->updateOrInsert(
            ['user_id'=>$user['id']],
            ['token'=>$user['link']]
        );

        // Mail::send('emails.verification', $user, function($message) use ($user) {
        //     $message->to('a93797fe21-db2ae0@inbox.mailtrap.io');
        //     $message->subject('Site - Activation Code');
        // });


        $options = array(
        'unsubscribe_url'   => 'http://mysite.com/unsub',
        'play_url'          => 'http://google-play.com/myapp',
        'ios_url'           => 'http://apple-store.com/myapp',
        'sendfriend_url'    => 'http://mysite.com/send_friend',
        'webview_url'       => 'http://mysite.com/webview_url',
        'verify_url' => $user['link']
        );
                
        Mail::to($userObj)->queue(new VerifyEmail($userObj, $options));
        
        return $this->sendResponse(['code' => $user['link'] ], 'Please, check your email.');
    }

    public function getMe()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        return $this->sendResponse($user);
    }
    
}
