<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => [],
            ];
        } else {
            try {
                if (
                    !Hash::check(
                        request('old_password'),
                        Auth::user()->password
                    )
                ) {
                    $arr = [
                        'status' => 400,
                        'message' => 'Check your old password.',
                        'data' => [],
                    ];
                } elseif (
                    Hash::check(request('new_password'), Auth::user()->password)
                ) {
                    $arr = [
                        'status' => 400,
                        'message' =>
                            'Please enter a password which is not similar then current password.',
                        'data' => [],
                    ];
                } else {
                    User::where('id', $userid)->update([
                        'password' => Hash::make($input['new_password']),
                    ]);
                    $arr = [
                        'status' => 200,
                        'message' => 'Password updated successfully.',
                        'data' => [],
                    ];
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = ['status' => 400, 'message' => $msg, 'data' => []];
            }
        }
        return response()->json($arr);
    }
}
