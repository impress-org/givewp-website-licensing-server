<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
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
    private $api_url = 'http://staging.givewp.com/edd-sl-api';
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

        $this->logger->pushHandler(new StreamHandler('../logs/license.log'));
    }

    /**
     * Handle edd-sl-api endpoint request
     *
     * @return string
     * @throws GuzzleException
     * @since 0.1
     */
    public function handle()
    {
        $error = \response()->json(array(
            'msg' => 'Rhis is ot a valid query.',
        ), 400);

        if ( ! $this->request->filled('edd_action')) {
            return $error;
        }

        $response = array();

        switch ($this->request->input('edd_action')) {
            case 'activate_license':
            case 'deactivate_license':
                if ( ! $this->request->filled('license', 'item_name')) {
                    return $error;
                }

                $response = $this->getResultFromGiveWP();
                break;

            case 'check_license':
                if ( ! $this->request->filled('license')) {
                    return $error;
                }

                $response = $this->handleCheckLicense();
                break;

            case 'check_licenses':
                if ( ! $this->request->filled('licenses')) {
                    return $error;
                }

                $response = $this->handleCheckLicenses();
                break;

            case 'get_version':
                if ( ! $this->request->filled('item_name')) {
                    return $error;
                }

                $response = $this->handleGetVersion();
                break;

            case 'check_subscription':
                if ( ! $this->request->filled('license', 'item_name')) {
                    return $error;
                }

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
                DB::table('addon')->updateOrInsert(
                    array('addon' => $dataFromGiveWP['name']),
                    array(
                        'data'       => serialize($dataFromGiveWP),
                        'created_at' => date('Y:m:d H:i:s'),
                        'updated_at' => date('Y:m:d H:i:s'),
                    )
                );

                break;

            case 'check_subscription':
                DB::table('subscription')->updateOrInsert(
                    array('license' => $dataFromGiveWP['license_key']),
                    array(
                        'data'       => serialize($dataFromGiveWP),
                        'created_at' => date('Y:m:d H:i:s'),
                        'updated_at' => date('Y:m:d H:i:s'),
                    )
                );

                break;
            case 'check_license':
            case 'check_licenses':
                foreach ($dataFromGiveWP as $license_key => $data) {
                    if ( ! empty($data['check_license'])) {
                        DB::table('license')->updateOrInsert(
                            array('license' => $license_key),
                            array(
                                'data'       => serialize($data),
                                'created_at' => date('Y:m:d H:i:s'),
                                'updated_at' => date('Y:m:d H:i:s'),
                            )
                        );
                    }

                    if ( ! empty($data['get_version'])) {
                        DB::table('addon')->updateOrInsert(
                            array('addon' => $data['get_version']['name']),
                            array(
                                'data'       => serialize($data['get_version']),
                                'created_at' => date('Y:m:d H:i:s'),
                                'updated_at' => date('Y:m:d H:i:s'),
                            )
                        );

                    } elseif ( ! empty($data['get_versions'])) {
                        foreach ($data['get_versions'] as $addon) {
                            DB::table('addon')->updateOrInsert(
                                array('addon' => $addon['name']),
                                array(
                                    'data'       => serialize($addon),
                                    'created_at' => date('Y:m:d H:i:s'),
                                    'updated_at' => date('Y:m:d H:i:s'),
                                )
                            );

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
        $licensesFromDB = DB::table('license')->whereIn('license', $licenses)->select('license',
            'data')->get()->toArray();

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         *  2. license count is same
         */
        if (
            ! empty($licensesFromDB)
            && count($licensesFromDB) === count($licenses)
        ) {
            foreach ($licensesFromDB as $license) {
                $response[$license->license] = unserialize($license->data);
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
        $response       = array();
        $license        = trim($this->request->input('license'));
        $licensesFromDB = DB::table('license')->where('license', $license)->select('license', 'data')->get()->toArray();

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         */
        if ( ! empty($licensesFromDB)) {
            foreach ($licensesFromDB as $license) {
                $response[$license->license] = unserialize($license->data);
            }
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

        try {
            $addonFromDB = DB::table('addon')->whereRaw("UPPER(addon) LIKE '%{$addon_name}%'")->select('addon',
                'data')->get()->first();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return $response;
        }

        if ( ! empty($addonFromDB)) {
            $response = unserialize($addonFromDB->data);
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
        $response       = array();
        $license        = trim($this->request->input('license'));
        $licensesFromDB = DB::table('subscription')->where('license', $license)->select('license',
            'data')->get()->toArray();

        /*
         * A result set will be count as successful on if:
         *  1. result from database is not empty
         */
        if ( ! empty($licensesFromDB)) {
            foreach ($licensesFromDB as $license) {
                $response[$license->license] = unserialize($license->data);
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
}

// @todo: check if we are setting correct url for GiveWP request.
// @todo: check if license expired or not before sending license result.
// @todo: discuss with jason how to handle requests other then registered routes
// @todo: discuss with jason about refactoring code
