<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\Subscription;
use App\Repositories\Addons;
use App\Repositories\GiveWP;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use Illuminate\Http\JsonResponse;
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
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle edd-sl-api endpoint request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function handle(): JsonResponse
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
                // We need minimum one param to process request.
                if (! $this->request->filled('licenses') && ! $this->request->filled('unlicensed')) {
                    return \response()->json(array(
                        'licenses' => [
                            'validation.required'
                        ],
                        'unlicensed' => [
                            'validation.required'
                        ]
                    ), 422);
                }

                $response = $this->handleCheckLicenses();
                break;

            case 'get_version':
                $this->validate($this->request, ['item_name' => 'required|string']);

                $response = $this->handleGetVersion();
                break;

            case 'check_subscription':
                $this->validate($this->request, ['license' => 'required|string']);

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
        $license_keys   = array_map('trim', explode(',', $this->request->input('licenses')));
        $unlicensed     = array_map('trim', explode(',', $this->request->input('unlicensed')));

        if (! empty($license_keys)) {
            foreach ($license_keys as $key => $license_key) {
                $licenseFromDB = app(Licenses::class)->get($license_key);

                if ($licenseFromDB instanceof License) {
                    $response[$license_key] = $licenseFromDB->data;
                    unset($license_keys[$key]);
                }
            }
        }

        if (! empty($unlicensed)) {
            foreach ($unlicensed as $key => $addon_name) {
                $addon = app(Addons::class)->get($addon_name);

                if ($addon instanceof Addon) {
                    $response[$addon_name] = $addon->data;
                    unset($unlicensed[$key]);
                }
            }
        }

        // Remove empty values.
        $license_keys = array_filter($license_keys);
        $unlicensed = array_filter($unlicensed);

        // Fetch remain results from GiveWP
        if (! empty($license_keys) || ! empty($unlicensed)) {
            $this->request->offsetSet('licenses', implode(',', $license_keys));
            $this->request->offsetSet('unlicensed', implode(',', $unlicensed));

            $temp_response = app(GiveWP::class)->get($this->request);

            $response = array_merge($response, $temp_response);
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
