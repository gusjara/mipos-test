<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function register(Request $request){
        

        $rules = [
            'name'      => 'required|string',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string',//|confirmed',
            
        ];
        $messages = [
            'email.unique' => 'El email ya existe.',

        ];
        
        $this->validate($request, $rules, $messages);     

        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();


        return response()->json(['message' => 'User Created'], 201);
        // return response()->json(['message' => 'Not Found!'], 404);
    }


    public function login(Request $request){
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);        

        $credentials = request(['email', 'password']);
        
        if (!Auth::attempt($credentials)) { //check if use passport
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }        
        
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        // $user = Auth::guard('client')->user();
		// $success['token'] =  $user->createToken('Personal Access Token')->accessToken;
        
        $token = $tokenResult->token;        
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }        
        $token->save();        
        return response()->json([
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse(
                    $tokenResult->token->expires_at)
                        ->toDateTimeString(),
                'user' => $user,
            ],
            'message' => 'Login succes'
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();        

        return response()->json(['message' => 
            'Successfully logged out']);
    }

    public function user(Request $request){
        
        $user = $request->user();
        // dd($storeUsers);
        // return response()->json($request->user());
        return response()->json([
            'data'=> [
                'user' => $user            
            ]
        ]);
    }

    /*public function forgot_password(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                    case Password::INVALID_USER:
                        return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
                }
            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }*/

    /*public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => array());
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }*/
}
