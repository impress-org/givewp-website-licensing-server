<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class StoreDataController
 * @package App\Http\Controllers
 */
class StoreDataController extends BaseController
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
     * Handle store data requests
     *
     * @return string
     */
    protected function handle()
    {
    }
}
