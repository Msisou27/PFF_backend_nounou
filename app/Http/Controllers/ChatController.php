<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ChatController extends Controller
{
    private $success_status     =   200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            
        return Chat::orderBy('created_at', 'DESC')
                     ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user       =   Auth::user();
        $validator  =   Validator::make($request->all(),
        [
            "message"       => "required|string",
            
            
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }

        $chatDataArray   =   array (
            "message"       =>  $request->message,
            "author"        =>  $user->firstname,
            // "user_recep"    =>  $request->user_recep,  
            // "id_product"    =>  $request->id_product,
        );
        $chat           =           Chat::create($chatDataArray);
        if(!is_null($chat)) {
            return response()->json(["data" => $chat,"status" => $this->success_status, "success" => true, "message" => "chat message created successfully"]);
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! failed to create chat message"]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
