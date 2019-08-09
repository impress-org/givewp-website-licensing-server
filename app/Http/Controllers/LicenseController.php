<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\License;
use App\Models\Subscription;
use App\Repositories\Addons;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client as Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
    private $api_url;
    private $logger;
    private $request;

    /**
     * LicenseController constructor.
     *
     * @param  Request  $request
     *
     * @throws \Exception
     */
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->logger  = new Logger('License');
        $this->api_url = env('GIVEWP_LICENSE_ENDPOINT', 'http://givewp.test/edd-sl-api');

        $this->logger->pushHandler(new StreamHandler('../logs/license.log'));
    }

    /**
     * Handle edd-sl-api endpoint request
     *
     * @return string
     * @throws GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     * @since 0.1
     */
    public function handle()
    {
        $this->validate($this->request, ['edd_action' => 'required|string']);

        $response = array();

        switch ($this->request->input('edd_action')) {
            case 'activate_license':
            case 'deactivate_license':
                $this->validate($this->request, ['license' => 'required|string', 'item_name' => 'required|string']);

                // Delete stored license data result.
                app(Licenses::class)->delete(trim($this->request->input('license')));

                $response = $this->getResultFromGiveWP();
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
     * Get rest API results from GiveWP
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getResultFromGiveWP()
    {
        $response = array();

        try {
            $client = new Client();

            $query_param = $this->request->all();

            if ('check_license' === $this->request->input('edd_action')) {
                $query_param['edd_action'] = 'check_licenses';
                $query_param['licenses']   = $query_param['license'];

                unset($query_param['license']);
            }

            // Url must be set before sending query to GiveWP otherwise a;; url will set to this proxy server.
            if ( ! $this->request->filled('url')) {
                // Attempt to grab the URL from the user agent if no URL is specified
                $domain = array_map('trim', explode(';', $_SERVER['HTTP_USER_AGENT']));

                try {
                    $query_param['url'] = trim($domain[1]);
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }

            $response = $client->request(
                'POST',
                $this->api_url,
                array(
                    'form_params' => $query_param,
                    'timeout'     => 15,
                )
            );

            $response = json_decode($response->getBody(), true);
            $this->saveResult(
                $response,
                $this->request->input('edd_action')
            );
        } catch (\Exception $e) {
            $this->logger->warning($e->getMessage());
        }

        return $response;
    }

    /**
     * Save GiveWP result into database
     *
     * @param  array  $dataFromGiveWP
     * @param  string  $type
     *
     * @return bool
     */
    private function saveResult($dataFromGiveWP, $type)
    {
        if (empty($dataFromGiveWP)) {
            return false;
        }

        switch ($type) {
            case 'get_version':
                if ( ! empty($dataFromGiveWP['new_version'])) {
                    Addon::store($dataFromGiveWP['name'], $dataFromGiveWP);
                }

                break;

            case 'check_subscription':
                if ( ! empty($dataFromGiveWP['subscription_key'])) {
                    Subscription::store($dataFromGiveWP['license_key'], $dataFromGiveWP);
                }

                break;
            case 'check_license':
            case 'check_licenses':
                foreach ($dataFromGiveWP as $license_key => $data) {
                    if ( ! empty($data['check_license']) && ! empty($data['check_license']['license_key'])) {
                        License::store($license_key, $data);
                    }

                    if ( ! empty($data['get_version']) && ! empty($data['get_version']['new_version'])) {
                        Addon::store($data['get_version']['name'], $data['get_version']);
                    } elseif ( ! empty($data['get_versions'])) {
                        foreach ($data['get_versions'] as $addon) {
                            if ( ! empty($addon['newer_version'])) {
                                Addon::store($addon['name'], $addon);
                            }
                        }
                    }
                }
                break;
        }

        return true;
    }


    /**
     * Check request when edd_action set to check_licenses
     *
     * @return array
     */
    private function handleCheckLicenses()
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
            try {
                $response = $this->getResultFromGiveWP();
            } catch (\Exception $e) {
            } catch (GuzzleException $e) {
            }
        }

        return $response;
    }

    /**
     * Check request when edd_action set to check_license
     *
     * @return array
     */
    private function handleCheckLicense()
    {
        $response    = array();
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
            try {
                $response = $this->getResultFromGiveWP();
                $response = current($response)['check_license']; // Return only license result for backward compatibility.
            } catch (\Exception $e) {
            } catch (GuzzleException $e) {
            }
        }

        return $response;
    }

    /**
     * Check request when edd_action set to get_version
     *
     * @return array
     */
    private function handleGetVersion()
    {
        $response   = array();
        $addon_name = strtoupper(trim($this->request->input('item_name')));
        $addon      = app(Addons::class)->get($addon_name);

        if ($addon instanceof Addon) {
            $response = $addon->data;
        } else {
            try {
                $response = $this->getResultFromGiveWP();
            } catch (\Exception $e) {
            } catch (GuzzleException $e) {
            }
        }

        return $response;
    }

    /**
     * Check request when edd_action set to check_subscription
     *
     * @return array
     */
    private function handleCheckSubscription()
    {
        $response     = array();
        $license_key  = trim($this->request->input('license'));
        $subscription = app(Subscriptions::class)->get($license_key);

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         */
        if ($subscription instanceof Subscription) {
            $response = $subscription->data;
        } else {
            try {
                $response = $this->getResultFromGiveWP();
            } catch (\Exception $e) {
            } catch (GuzzleException $e) {
            }
        }

        return $response;
    }
}

// @todo: check if we are setting correct url for GiveWP request.
// @todo: check if license expired or not before sending license result.
// @todo: discuss with jason about refactoring code
