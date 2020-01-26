<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{

    // public function store(Request $request)
    // {
    //     $acceptHeader = $request->header('Accept');
    //     if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
    //         $contentTypeHeader = $request->header('Content-Type');
    //         if ($contentTypeHeader === 'application/json') {
    //         	$input = $request->all();
    //         	$user = User::create($input);
    //         	return response()->json($user, 200);
    //         }else{
    //             return response('Unsupported Media Type', 415);
    //         }
    //     }else{
    //         return response('Not acceptable', 406);
    //     }
    // }

    public function store(Request $request)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('read-users')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $input = $request->all();
            $validationRules = [
                'username'      => 'required|min:5',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:5|confirmed',
                'role'      => 'required|in:admin,user',
            ];
            $validator = \Validator::make($input, $validationRules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $user = new User;
            $user->username  = $request->input('username');
            $user->email = $request->input('email');
            $planPassword    = $request->input('password');
            $user->password = app('hash')->make($planPassword);
            $user->role = $request->input('role');

            $user->save();
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    // public function index(Request $request)
    // {
    //     $acceptHeader = $request->header('Accept');
    //     if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
    //         $user = User::OrderBy("id", "DESC")->paginate(5);
    //         if ($acceptHeader === 'application/json') {
    //             return response()->json($user, 200);
    //         }else{
    //             $xml = new \SimpleXMLElement('<users/>');
    //             foreach ($user->items('data') as $item) {
    //                 $xmlItem = $xml->addChild('user');
    //                 $xmlItem->addChild('id', $item->id);
    //                 $xmlItem->addChild('name', $item->name);
    //                 $xmlItem->addChild('email', $item->email);
    //                 $xmlItem->addChild('created_at', $item->created_at);
    //                 $xmlItem->addChild('updated_at', $item->updated_at);
    //             }
    //             return $xml->asXML();
    //         }
    //     }else{
    //         return response('Not acceptable', 406);
    //     }
    // }

    public function index(Request $request)
    {

        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('read-users')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::OrderBy("id", "DESC")->paginate(5)->toArray();
            $response = [
                "total_count" => $user["total"], 
                "limit"       => $user["per_page"],
                "pagination"  => [
                    "next_page"    => $user["next_page_url"], 
                    "current_page" => $user["current_page"],
                ],
                "data" => $user["data"],
            ];
            return response()->json($response, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    public function show(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('read-users')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);   
        }
    }   

    // public function show(Request $request, $id)
    // {
    //     $acceptHeader = $request->header('Accept');
    //     if ($acceptHeader === 'application/json') {
    //         $admin = Admin::find($id);
    //         if (!$admin) {
    //             abort(404);
    //         }
    //         return response()->json($admin, 200);
    //     }else{
    //         return response('Not acceptable', 406);
    //     }
    // }


    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('read-users')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $input = $request->all();       
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            $user->fill($input);
            $user->save();
            return response()->json($user, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $acceptHeader = $request->header('Accept');
    //     if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
    //         $contentTypeHeader = $request->header('Content-Type');
    //         if ($contentTypeHeader === 'application/json') {
    //             $input = $request->all();
    //             $validationRules = [
    //                 'username'   => 'required|min:5|unique:admin',
    //                 'password'  => 'required|min:8',
    //                 'nama_admin' => 'required|min:5|max:16',
    //             ];
    //             $validator = \Validator::make($input, $validationRules);
    //             if ($validator->fails()) {
    //                 return response()->json($validator->errors(), 400);
    //             }
    //             $admin = Admin::find($id);
    //             if (!$admin) {
    //                 abort(404);
    //             }
    //             $admin->fill($input);
    //             $admin->save();
    //             return response()->json($admin, 200);
    //         }else{
    //             return response('Unsupported Media Type', 415);
    //         }
    //     }else{
    //         return response('Not acceptable', 406);
    //     }
    // }

    // public function destroy(Request $request, $id)
    // {
    //     $acceptHeader = $request->header('Accept');
    //     if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
    //         $admin = Admin::find($id);
    //         if (!$admin) {
    //             abort(404);
    //         }
    //         $admin->delete();
    //         $message = ['message' => 'Deleted successfully', 'admin_id' => $id];
    //         return response()->json($message, 200);
    //     }else{
    //         return response('Not acceptable', 406);
    //     }
    // }

    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            if (Gate::denies('read-users')) {
                return response()->json([
                    'success' => false,
                    'status'  => 403,
                    'message' => 'You are unauthorized',
                ], 403);
            }
            $user = User::find($id);
            if (!$user) {
                abort(404);
            }
            $user->delete();
            $message = ['message' => 'Deleted successfully', 'user_id' => $id];
            return response()->json($message, 200);
        }else{
            return response('Not acceptable', 406);
        }
    }

}

