<?php

namespace App\Http\Controllers;

use App\Repositories\Addons;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class StoreDataController
 * @package App\Http\Controllers
 */
class UpdateDataController extends BaseController
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
     * @return JsonResponse
     * @throws ValidationException
     */
    public function handle()
    {
        switch ($this->request->path()) {
            case 'update/license':
                $this->handleLicenseUpdate();
                break;

            case 'update/addon':
                $this->handleAddonUpdate();
                break;

            case 'update/subscription':
                $this->handleSubscriptionUpdate();
                break;
        }

        return \response()->json(array('success' => true));
    }

    /**
     * Handle add-on update
     *
     * @todo: Add-on update will cause of deletion of all access pass licenses database entry. Need to figure out a way to tackle this.
     *
     * @return bool
     * @throws ValidationException
     */
    private function handleAddonUpdate(): bool
    {
        $this->validate($this->request, ['addon' => 'required|string']);

        $addon = $this->request->input('addon');

        app(Addons::class)->delete($addon);
        app(Licenses::class)->deleteByAddon($addon);

        return true;
    }

    /**
     * Handle license update
     *
     * @return bool
     * @throws ValidationException
     */
    private function handleLicenseUpdate(): bool
    {
        $this->validate($this->request, ['license' => 'required|string']);

        $license = array_map('trim', explode(',', $this->request->input('license')));
        app(Licenses::class)->deleteAll($license);

        return true;
    }

    /**
     * Handle subscription update
     *
     * @return bool
     * @throws ValidationException
     */
    private function handleSubscriptionUpdate(): bool
    {
        $this->validate($this->request, ['subscription' => 'required|string']);

        app(Subscriptions::class)->deleteBySubscriptionID(trim($this->request->input('subscription')));

        return true;
    }
}
