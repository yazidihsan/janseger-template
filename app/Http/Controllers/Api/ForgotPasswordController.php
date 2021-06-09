<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgot_password(Request $request)
    {
        $input = $request->user();
        $rules = $request->validate([
            'email' => 'required|email',
        ]);
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => [],
            ];
        } else {
            try {
                $response = Password::sendResetLink(
                    $request->only('email'),
                    function (Message $message) {
                        $message->subject($this->getEmailSubject());
                    }
                );
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return response()->json([
                            'status' => 200,
                            'message' => trans($response),
                            'data' => [],
                        ]);
                    case Password::INVALID_USER:
                        return response()->json([
                            'status' => 400,
                            'message' => trans($response),
                            'data' => [],
                        ]);
                    default:
                        return false;
                }
            } catch (\Swift_TransportException $ex) {
                $arr = [
                    'status' => 400,
                    'message' => $ex->getMessage(),
                    'data' => [],
                ];
            } catch (Exception $ex) {
                $arr = [
                    'status' => 400,
                    'message' => $ex->getMessage(),
                    'data' => [],
                ];
            }
        }
        return response()->json($arr);
    }
}
