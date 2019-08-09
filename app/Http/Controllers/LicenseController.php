<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\Subscription;
use App\Repositories\Addons;
use App\Repositories\GiveWP;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class LicenseController
 *
 * Handles the Team (Organization) endpoints
 *
 * @package App\Http\Controllers
 * @since   0.1
 */
class LicenseController extends BaseController
{
    private $request;

    /**
     * LicenseController constructor.
     *
     * @param  Request  $request
     *
     * @throws \Exception
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle edd-sl-api endpoint request
     *
     * @return string
     * @throws ValidationException
     * @since 0.1
     */
    public function handle(): string
    {
        $this->validate($this->request, ['edd_action' => 'required|string']);

        $response = array();

        switch ($this->request->input('edd_action')) {
            case 'activate_license':
            case 'deactivate_license':
                $this->validate($this->request, ['license' => 'required|string', 'item_name' => 'required|string']);

                // Delete stored license data result.
                app(Licenses::class)->delete(trim($this->request->input('license')));

                $response = app(GiveWP::class)->get($this->request);
                break;

            case 'check_license':
                $this->validate($this->request, ['license' => 'required|string']);

                $response = $this->handleCheckLicense();
                break;

            case 'check_licenses':
                $this->validate($this->request, ['licenses' => 'required|string']);

                $response = $this->handleCheckLicenses();
                break;

            case 'get_version':
                $this->validate($this->request, ['item_name' => 'required|string']);

                $response = $this->handleGetVersion();
                break;

            case 'check_subscription':
                $this->validate($this->request, ['license' => 'required|string', 'item_name' => 'required|string']);

                $response = $this->handleCheckSubscription();
                break;
        }

        return \response()->json($response);
    }


    /**
     * Check request when edd_action set to check_licenses
     *
     * @return array
     */
    private function handleCheckLicenses(): array
    {
        $response       = array();
        $licenses       = array_map('trim', explode(',', $this->request->input('licenses')));
        $licensesFromDB = app(Licenses::class)->get($licenses);


        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         *  2. license count is same
         */
        if (
            $licensesFromDB instanceOf Collection
            && $licensesFromDB->isNotEmpty()
            && count($licensesFromDB) === count($licenses)
        ) {
            foreach ($licensesFromDB as $license) {
                $response[$license->license] = $license->data;
            }
        } else {
            $response = app(GiveWP::class)->get($this->request);
        }

        return $response;
    }

    /**
     * Check request when edd_action set to check_license
     *
     * @return array
     */
    private function handleCheckLicense(): array
    {
        $license_key = trim($this->request->input('license'));
        $license     = app(Licenses::class)->get($license_key);

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         */
        if ($license instanceof License) {
            $response = $license->data;
            $response = $response['check_license'];
        } else {
            $response = app(GiveWP::class)->get($this->request);
            $response = current($response)['check_license']; // Return only license result for backward compatibility.
        }

        return $response;
    }

    /**
     * Check request when edd_action set to get_version
     *
     * @return array
     */
    private function handleGetVersion(): array
    {
        $addon_name = strtoupper(trim($this->request->input('item_name')));
        $addon      = app(Addons::class)->get($addon_name);

        if ($addon instanceof Addon) {
            $response = $addon->data;
        } else {
            $response = app(GiveWP::class)->get($this->request);
        }

        return $response;
    }

    /**
     * Check request when edd_action set to check_subscription
     *
     * @return array
     */
    private function handleCheckSubscription(): array
    {
        $license_key  = trim($this->request->input('license'));
        $subscription = app(Subscriptions::class)->get($license_key);

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         */
        if ($subscription instanceof Subscription) {
            $response = $subscription->data;
        } else {
            $response = app(GiveWP::class)->get($this->request);
        }

        return $response;
    }
}

// @todo: check if we are setting correct url for GiveWP request.
// @todo: check if license expired or not before sending license result.
// @todo: discuss with jason about refactoring code
