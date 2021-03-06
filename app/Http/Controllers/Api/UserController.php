<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use PasswordValidationRules;
    public function login(Request $request)
    {
        try {
            //validasi input
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);

            //check credentials (login)
            // $credentials = request(['email', 'password']);
            // if (!Auth::attempt($credentials)) {
            //     return ResponseFormatter::error([
            //         'message' => 'Unauthorized'
            //     ], 'Authentication Failed', 500);
            // }
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (password_verify($request->password, $user->password)) {
                    //jika sesuai maka loginkan
                    $tokenResult = $user->createToken('authToken')
                        ->plainTextToken;
                    return ResponseFormatter::success(
                        [
                            'success' => true,
                            'access_token' => $tokenResult,
                            'token_type' => 'Bearer',
                            'user' => $user,
                        ],
                        'Authenticated'
                    );
                }
                return ResponseFormatter::error(
                    [
                        'message' => 'Password Salah',
                        'success' => false,
                    ],
                    'Authentication Failed',
                    500
                );
            } else {
                return ResponseFormatter::error(
                    [
                        'message' => 'Email tidak ditemukan',
                        'success' => false,
                    ],
                    'Authentication Failed',
                    500
                );
            }
            //check hash tidak sesuai beri eror
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'Authentication Failed',
                500
            );
        }
    }
    public function register(Request $request)
    {
           $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users',
                ],
                'password' => 'required|string|confirmed',
            ]);

           $user =  User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'phoneNumber' => $request->phoneNumber,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]);



            $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function logout(Request $request)
    {
        $token = $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }
    public function fetch(Request $request)
    {
        return ResponseFormatter::success(
            $request->user(),
            'Data Profile berhasil diambil'
        );
    }
    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');
    }
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Upload photo fails',
                401
            );
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            //Simpan foto ke database (urlnya)
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success(
                [$file],
                'File successfully uploaded'
            );
        }
    }
}
