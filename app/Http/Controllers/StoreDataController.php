<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ( ! $this->request->filled('addon')) {
            return false;
        }

        $addon = $this->request->input('addon');
        DB::table('addon')->whereRaw("addon LIKE '%{$addon}%'")->delete();
        DB::table('license')->whereRaw("data LIKE '%{$addon}%'")->delete();

        return true;
    }

    /**
     * Handle license update
     *
     * @return bool
     */
    private function handleLicenseUpdate()
    {
        if ( ! $this->request->filled('license')) {
            return false;
        }

        DB::table('license')->where('license', trim($this->request->input('license')))->delete();

        return true;
    }

    /**
     * Handle subscription update
     *
     * @return bool
     */
    private function handleSubscriptionUpdate()
    {
        if ( ! $this->request->filled('license')) {
            return false;
        }

        DB::table('license')->where('license', $this->request->input('license'))->delete();

        return true;
    }
}
