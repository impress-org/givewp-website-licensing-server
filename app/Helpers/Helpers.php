<?php

namespace App\Helpers;

if (! function_exists('getLicenseIdentifier')) {
    /**
     * Get unique identifier to identify license in licenses table
     *
     * @param  string  $license_key  License Key.
     * @param  string  $website_url
     *
     * @return string
     */
    function getLicenseIdentifier($license_key, $website_url = '')
    {
        $website_url = $website_url ?: getClientWebsiteURLFromUserAgent();
        return substr(md5("{$license_key}|{$website_url}"), 0, 15);
    }
}

if (! function_exists('getClientWebsiteURLFromUserAgent')) {
    /**
     * Get client website URL from user agent
     * @todo: https://github.com/impress-org/give-website-licensing-server/pull/7#discussion_r315290745
     *
     * @return string
     */
    function getClientWebsiteURLFromUserAgent()
    {
        $domain = filter_var(app('request')->input('url'), FILTER_VALIDATE_URL);
        if (! $domain) {
            $domain = isset($_SERVER['HTTP_USER_AGENT']) ?
                array_map('trim', explode(';', $_SERVER['HTTP_USER_AGENT']))
                : '';
            $domain = ! empty($domain[1]) ? trim($domain[1]) : '';
        }

        return $domain;
    }
}
