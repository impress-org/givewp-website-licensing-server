<?php

namespace App\Http\Controllers;

use App\Repositories\Addons;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use Illuminate\Http\Request;
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
     * @return bool
     */
    private function handleAddonUpdate()
    {
        if (! $this->request->filled('addon')) {
            return false;
        }

        $addon = $this->request->input('addon');

        app(Addons::class)->delete($addon);
        app(Licenses::class)->deleteByAddon($addon);

        return true;
    }

    /**
     * Handle license update
     *
     * @return bool
     */
    private function handleLicenseUpdate()
    {
        if (! $this->request->filled('license')) {
            return false;
        }

        app(Licenses::class)->delete(trim($this->request->input('license')));

        return true;
    }

    /**
     * Handle subscription update
     *
     * @return bool
     */
    private function handleSubscriptionUpdate()
    {
        if (! $this->request->filled('license')) {
            return false;
        }

        app(Subscriptions::class)->delete(trim($this->request->input('license')));

        return true;
    }
}
