<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
     * User passkey
     * @var string
     */
    private $passkey;

    /**
     * User
     * @var string
     */
    private $user;

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
        $this->user    = env('GIVEWP_USER');
        $this->passkey = env('GIVEWP_PASSKEY');
    }

    /**
     * Create a new token.
     *
     * @return string
     */
    private function jwt(): string
    {
        $payload = [
            'iss' => 'lumen-jwt', // Issuer of the token
            'sub' => $this->user, // Subject of the token
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function authenticate(): JsonResponse
    {
        $this->validate($this->request, array(
            'email'    => 'required|email',
            'password' => 'required',
        ));

        if ($this->user !== $this->request->input('email')) {
            return response()->json(array(
                'msg' => 'User does not exist.',
            ), 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $this->passkey)) {
            return response()->json(array(
                'token' => $this->jwt(),
            ));
        }

        // Bad Request response
        return response()->json(array(
            'msg' => 'Email or password is wrong.',
        ), 400);
    }
}
