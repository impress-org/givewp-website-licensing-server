<?php

namespace App\Repositories;

use App\Models\Addon;
use App\Models\License;
use App\Models\Subscription;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use function App\Helpers\getClientWebsiteURLFromUserAgent;

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

        // Url must be set before sending query to GiveWP otherwise site url will set to this proxy server.
        if (! $request->filled('url')) {
            $query_param['url'] = getClientWebsiteURLFromUserAgent();
        }

        // Make request to GiveWP.com
        $response = $client->request(
            'POST',
            $api_url,
            array(
                'form_params' => $query_param,
                'timeout'     => 30,
                'headers'     => [
                    'User-Agent' => env('APP_URL').'/'.config('app.version'),
                ],
            )
        );

        $response = json_decode($response->getBody(), true);

        $this->saveResult(
            $request->input('edd_action'),
            $query_param['url'],
            $response
        );

        return $response;
    }

    /**
     * Save GiveWP result into database
     *
     * @param  string  $type
     * @param  string  $website_url
     * @param  array  $dataFromGiveWP
     *
     */
    private function saveResult($type, $website_url, $dataFromGiveWP): void
    {
        switch ($type) {
            case 'get_version':
                if (! empty($dataFromGiveWP['new_version'])) {
                    Addon::store($dataFromGiveWP['name'], $dataFromGiveWP);
                }

                break;

            case 'check_subscription':
                if (! empty($dataFromGiveWP['subscription_key'])) {
                    Subscription::store($dataFromGiveWP['license_key'], $dataFromGiveWP);
                }

                break;
            case 'check_license':
            case 'check_licenses':
                foreach ($dataFromGiveWP as $license_key => $data) {
                    if (! empty($data['check_license']) && ! empty($data['check_license']['license_key'])) {
                        License::store($license_key, $data);
                    }

                    if (! empty($data['get_version']) && ! empty($data['get_version']['new_version'])) {
                        Addon::store($data['get_version']['name'], $data['get_version']);
                    } elseif (! empty($data['get_versions'])) {
                        foreach ($data['get_versions'] as $addon) {
                            if (! empty($addon['newer_version'])) {
                                Addon::store($addon['name'], $addon);
                            }
                        }
                    }
                }
                break;
        }
    }
}
