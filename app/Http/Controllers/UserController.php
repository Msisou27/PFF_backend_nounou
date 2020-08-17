<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    private $sucess_status = 200;
    // --------------[ userall ]---------------
    public function index()
    {
        return User::all();
    }

    // --------------[ user Sign Up ]---------------
    public function createUser(Request $request){
        
        $validator =  Validator::make($request->all(),
         [
            "firstname"         =>     "required|string|min:3|max:250",
            "surname"           =>     "required|string|min:3|max:250",
            "email"             =>     "required|email|min:8|max:50",
            "phone"             =>     "required|numeric",
            "number_adress"     =>     "required|numeric",
            "adress"            =>     "required|string|min:8|max:250",
            "location"          =>     "required|numeric",
            "town"              =>     "required|string|min:8|max:250",
            "password"          =>     "required|alpha_num|min:5"
         ]
        );

        if($validator->fails()) {
            return response()->json(["validation_error" => $validator->errors()]);
        }
        $dataArray  =  array(
            "firstname"         =>      $request->firstname,
            "surname"           =>      $request->surname,
            "email"             =>      $request->email,
            "phone"             =>      $request->phone,
            "number_adress"     =>      $request->number_adress,
            "adress"            =>      $request->adress,
            "location"          =>      $request->location,
            "town"              =>      $request->town,
            "password"          =>      bcrypt($request->password)
        );

        $user = User::create($dataArray);

        if(!is_null($user)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $user, "message" => "user created"]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! user not created. please try again."]);
        }

    }
    // -------------- [ User Update ] ---------------
    public function update(Request $request){
        
        $validator =  Validator::make($request->all(),
         [
            "firstname"         =>      "required|string|min:3|max:250",
            "surname"           =>      "required|string|min:3|max:250",
            "email"             =>      "required|email|min:8|max:50",
            "phone"             =>      "required|numeric",
            "number_adress"     =>      "required|numeric",
            "adress"            =>      "required|string|min:3|max:250",
            "location"          =>      "required|numeric",
            "town"              =>      "required|string|min:8|max:250",
            // "password"          =>      "alpha_num|min:5"
         ]
        );

        if($validator->fails()) {
            return response()->json(["validation_error" => $validator->errors()]);
        }
            $user = User::find($request->id);

            $user->firstname     = $request->firstname;
            $user->surname       = $request->surname;
            $user->email         = $request->email;
            $user->phone         = $request->phone;
            $user->number_adress = $request->number_adress;
            $user->adress        = $request->adress;
            $user->location      = $request->location;
            $user->town          = $request->town;
            // $user->password      = bcrypt($request->password);
        
        // $user  =  Auth::user()->id;
        $verif = $user->save();

        if($verif) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $user, "message" => "user updated"]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! user not udpated. please try again."]);
        }
    }

    // ---------------- [ User Login ] ------------------
    public function userLogin(Request $request) {

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user       =       Auth::user();
            $token      =       $user->createToken('token')->accessToken;

            return response()->json(["status" => $this->sucess_status, "success" => true, "login" => true, "token" => $token, "data" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! invalid email or password"]);
        }
    }

    // ---------------- [ User Detail ] -------------------
    public function userDetail() {
        $user           =       Auth::user();
        if(!is_null($user)) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "user" => $user]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no user found"]);
        }
    }

    // ------------------- [ User LogOut]--------------------
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    // ------------------- [ User delete ] --------------------
    public function delete(User $user) {
        $user->delete();
        if(!is_null($user)) {
        return response()->json(["status" => $this->sucess_status,"success" => true,"message" => "succefully deleted user"]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no user deleted"]);
        }
    }
    // -------------- [ UserAdmin Update ] ---------------
    public function Updateadmin(Request $request){
        
        $validator =  Validator::make($request->all(),
         
        [   "is_admin"         =>      "required|numeric",]);

        if($validator->fails()) {
            return response()->json(["validation_error" => $validator->errors()]);
        }
            $user = User::find($request->id);

            $user->is_admin     =  $request->is_admin;
            
            $verif = $user->save();

        if($verif) {
            return response()->json(["status" => $this->sucess_status, "success" => true, "data" => $user, "message" => "user updated"]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! user not udpated. please try again."]);
        }
    }
    
}
