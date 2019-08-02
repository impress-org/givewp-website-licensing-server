<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var Request $request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  Request  $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new token.
     *
     * @param  \App\User  $user
     *
     * @return string
     */
    protected function jwt(User $user)
    {
        $payload = [
            'iss' => 'lumen-jwt', // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 10 * 60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate user.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->validate($this->request, array(
            'email'    => 'required|email',
            'password' => 'required',
        ));

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if ( ! $user) {
            return response()->json(array(
                'msg' => 'Email does not exist.',
            ), 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json(array(
                'token' => $this->jwt($user),
            ), 200);
        }

        // Bad Request response
        return response()->json(array(
            'mag' => 'Email or password is wrong.',
        ), 400);
    }
}
