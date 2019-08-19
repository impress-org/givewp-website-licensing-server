<?php
namespace Tests\Helpers;

if (! function_exists('getLicenseData')) {
    /**
     * Get license data with pre-filled values
     *
     * @param  array  $license_data
     *
     * @return array
     */

    function getLicenseData($license_data)
    {
        $default_license_data = [
            'success'            => true,
            'license'            => 'valid',
            'item_id'            => false,
            'item_name'          => 'Form Field Manager',
            'checksum'           => '6ecb1f8025cf50e6d42bb0f3c484e2f2',
            'expires'            => date('Y-m-d H:i:s', strtotime('+1 year')),
            'payment_id'         => 49577,
            'customer_name'      => 'GiveWP',
            'customer_email'     => 'givewp@test.com',
            'license_limit'      => 5,
            'site_count'         => 0,
            'activations_left'   => 5,
            'price_id'           => '1',
            'license_key'        => 'f05fda4b9bce58c63b62bda6d2f7409d',
            'license_id'         => 98088,
            'download'           => 'http://staging.givewp.com/index.php?eddfile=49577%3A243%3A0%3A1&ttl=1566199147&file=0&token=f31c47f53be960eed485636046fb0b30e599cb88a2279daefde4f73160b452d1',
            'is_all_access_pass' => false,
            'current_version'    => '1.4.3',
            'readme'             => 'http://staging.givewp.com//downloads/plugins/give-form-field-manager/readme.txt',
            'plugin_slug'        => 'give-form-field-manager',
        ];

        $license_data = array_merge($default_license_data, $license_data);

        return $license_data;
    }
}
