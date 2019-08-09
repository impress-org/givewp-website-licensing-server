<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Models\License;
use App\Models\Subscription;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

/**
 * Class Licenses
 *
 * Repository for handling licenses.
 *
 * @since   0.1.0
 *
 * @package App\Repositories
 */
class GiveWP
{
    /**
     * Returns the option for the given key
     *
     * @param  Request  $request
     *
     * @return array
     * @throws GuzzleException
     */
    public function get(Request $request): array
    {
        $api_url = env('GIVEWP_LICENSE_ENDPOINT', 'http://givewp.test/edd-sl-api');

        $client = new Client();

        $query_param = $request->all();

        if ('check_license' === $request->input('edd_action')) {
            $query_param['edd_action'] = 'check_licenses';
            $query_param['licenses']   = $query_param['license'];

            unset($query_param['license']);
        }

        // Url must be set before sending query to GiveWP otherwise a;; url will set to this proxy server.
        if ( ! $request->filled('url')) {
            // Attempt to grab the URL from the user agent if no URL is specified
            $domain             = array_map('trim', explode(';', $_SERVER['HTTP_USER_AGENT']));
            $query_param['url'] = ! empty($domain[1]) ? trim($domain[1]) : '';
        }

        // Make request to GiveWP.com
        $response = $client->request(
            'POST',
            $api_url,
            array(
                'form_params' => $query_param,
                'timeout'     => 15,
            )
        );

        $response = json_decode($response->getBody(), true);

        $this->saveResult(
            $response,
            $request->input('edd_action')
        );

        return $response;
    }

    /**
     * Save GiveWP result into database
     *
     * @param  array  $dataFromGiveWP
     * @param  string  $type
     */
    private function saveResult($dataFromGiveWP, $type): void
    {
        switch ($type) {
            case 'get_version':
                if ( ! empty($dataFromGiveWP['new_version'])) {
                    Addon::store(strtolower($dataFromGiveWP['name']), $dataFromGiveWP);
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
    }
}
