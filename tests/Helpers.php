<?php

namespace Tests\Helpers;

if (!function_exists('getLicenseData')) {
    /**
     * Get license data with pre-filled values
     *
     * @param  array  $licenseData
     *
     * @return array
     */
    function getLicenseData($licenseData)
    {
        // @codingStandardsIgnoreStart
        $defaultLicenseData = [
            'success' => true,
            'license' => 'valid',
            'item_id' => false,
            'item_name' => 'Form Field Manager',
            'checksum' => '6ecb1f8025cf50e6d42bb0f3c484e2f2',
            'expires' => date('Y-m-d H:i:s', strtotime('+1 year')),
            'payment_id' => 49577,
            'customer_name' => 'GiveWP',
            'customer_email' => 'givewp@test.com',
            'license_limit' => 5,
            'site_count' => 0,
            'activations_left' => 5,
            'price_id' => '1',
            'license_key' => 'f05fda4b9bce58c63b62bda6d2f7409d',
            'license_id' => 98088,
            'download' =>
                'http://staging.givewp.com/index.php?eddfile=49577%3A243%3A0%3A1&ttl=1566199147&file=0&token=f31c47f53be960eed485636046fb0b30e599cb88a2279daefde4f73160b452d1',
            'is_all_access_pass' => false,
            'current_version' => '1.4.3',
            'readme' => 'http://staging.givewp.com//downloads/plugins/give-form-field-manager/readme.txt',
            'plugin_slug' => 'give-form-field-manager'
        ];
        // @codingStandardsIgnoreEnd

        $licenseData = array_merge($defaultLicenseData, $licenseData);
        $licenseData = ['check_license' => $licenseData];

        return $licenseData;
    }
}

if (!function_exists('getSubscriptionData')) {
    /**
     * Get subscription data with pre-filled values
     *
     * @param  array  $subscriptionData
     *
     * @return array
     */
    function getSubscriptionData($subscriptionData)
    {
        // @codingStandardsIgnoreStart
        $defaultSubscriptionData = [
            'success' => false,
            'id' => 669,
            'license_key' => '3e10e7a70ca7e8ecbe8d82a0d3fea0d8',
            'subscription_key' => 'f2ef3cbdee94833faa23dad0539e3ff8',
            'status' => 'expired',
            'expires' => date('Y-m-d H:i:s', strtotime('+1 year')),
            'payment_id' => 49577,
            'invoice_url' =>
                'http%3A%2F%2Fstaging.givewp.com%2Fpurchase-confirmation%2F%3Fpayment_key%3D2da1e1b542c298b806b89a14cd7b57ab'
        ];
        // @codingStandardsIgnoreEnd

        return array_merge($defaultSubscriptionData, $subscriptionData);
    }
}

if (!function_exists('getAddonData')) {
    /**
     * Get addon data with pre-filled values
     *
     * @param $addonData
     *
     * @return array
     */
    function getAddonData($addonData)
    {
        // @codingStandardsIgnoreStart
        $defaultAddonData = [
            'new_version' => '1.8.13',
            'stable_version' => '1.8.13',
            'name' => 'Recurring Donations',
            'slug' => 'give-recurring',
            'url' => 'http://givewp.test/downloads/recurring-donations/?changelog=1',
            'last_updated' => '2019-08-14 04:13:45',
            'homepage' => 'http://givewp.test/downloads/recurring-donations/',
            'package' =>
                'http://givewp.test/edd-sl/package_download/MTU2NjI3NTY5NzozZTEwZTdhNzBjYTdlOGVjYmU4ZDgyYTBkM2ZlYTBkODozNTQ6MWIyNmZlODZiNDY4NTcxNDkxNWRlNjljZmJhMjkyOWU6OjA=',
            'download_link' =>
                'http://givewp.test/edd-sl/package_download/MTU2NjI3NTY5NzozZTEwZTdhNzBjYTdlOGVjYmU4ZDgyYTBkM2ZlYTBkODozNTQ6MWIyNmZlODZiNDY4NTcxNDkxNWRlNjljZmJhMjkyOWU6OjA=',
            'sections' =>
                "a:2:{s:11:\"description\";s:14501:\"<a href=\"http://givewp.test\">\n            </a>\n\n        \n            MENUMENU<ul><li><a href=\"http://givewp.test/features/\">Features</a>\n<ul>\n<li><ul><li><a href=\"http://givewp.test/features/\">Give Features</a>\n<ul>\n    <li><a href=\"http://givewp.test/features/\">Features Overview</a></li>\n    <li><a href=\"http://givewp.test/features/donation-forms/\">Donation Forms</a></li>\n    <li><a href=\"http://givewp.test/features/donation-reports/\">Donation Reports</a></li>\n    <li><a href=\"http://givewp.test/features/donor-management/\">Donor Management</a></li>\n</ul>\n</li>\n</ul></li><li>          <a href=\"http://givewp.test//demos/\" title=\"View Give Demos\"></a>\n\n<h3>Give Demos</h3>\n\n<p>Try Give's donation forms out for yourself.</p>\n\n<a href=\"http://givewp.test//demos/\">Learn more </a>\n        </li></ul>\n</li><li><a href=\"http://givewp.test/downloads/\">Add-ons</a>\n<ul>\n<li><ul><li><a href=\"http://givewp.test/downloads/\">Give Add-ons</a>\n<ul>\n    <li><a title=\"View our most popular Add-on: Recurring Donations for Give\" href=\"http://givewp.test/downloads/recurring-donations/\">Recurring Donations</a></li>\n    <li><a title=\"View all the Payment Gateway Add-ons for Give\" href=\"http://givewp.test/downloads/category/payment-gateways/\">Payment Gateways</a></li>\n    <li><a title=\"Marketing Add-ons for Give\" href=\"http://givewp.test/downloads/category/marketing/\">Marketing Add-ons</a></li>\n    <li><a title=\"View all Give Must Have Add-ons\" href=\"http://givewp.test/downloads/category/must-have/\">Must Have Add-ons</a></li>\n    <li><a title=\"View all Give Add-ons\" href=\"http://givewp.test/downloads/\">View All Add-ons</a></li>\n    <li><a title=\"Purchase a bundle and get access to the Add-ons you need.\" href=\"http://givewp.test/addon-bundles/\">Bundle and Save</a></li>\n</ul>\n</li>\n</ul></li><li>            <a href=\"http://givewp.test//addons/annual-receipts/\" title=\"View Annual Receipts\"></a>\n\n<h3>Annual Receipts</h3>\n\n<p>Provide your donors with an annual overview of their donations.</p>\n\n<a href=\"http://givewp.test//addons/annual-receipts/\">Learn more </a>\n        </li></ul>\n</li><li><a href=\"http://givewp.test/about-give/\">About</a>\n<ul>\n<li><ul><li><a href=\"http://givewp.test/about-give/\">About Give</a>\n<ul>\n    <li><a href=\"http://givewp.test/about-give/\">How Give Started</a></li>\n    <li><a href=\"http://givewp.test/category/case-studies/\">Case Studies</a></li>\n    <li><a href=\"http://givewp.test/category/news/\">Give News</a></li>\n    <li><a href=\"http://givewp.test/careers/\">Careers at Give</a></li>\n    <li><a href=\"http://givewp.test/become-an-affiliate/\">Become an Affiliate</a></li>\n    <li><a href=\"http://givewp.test/contact-us/\">Contact Us</a></li>\n</ul>\n</li>\n</ul></li><li>            \n\n<h3>Rate Give 5 Stars</h3>\n\n<p>Give has 300+ 5 star ratings. Do you love Give? Why not rate it?</p>\n\n<a href=\"https://wordpress.org/support/view/plugin-reviews/give?filter=5#postform\" rel=\"noopener noreferrer\">Rate Give Now </a>\n        </li></ul>\n</li><li><a href=\"http://givewp.test/support/\">Support</a>\n<ul>\n<li>            \n\n<h3>Priority Support</h3>\n\n<p>All active license holders receive priority support with their purchase.</p>\n\n<a href=\"http://givewp.test//support/\">Learn more </a>\n        </li><li><ul><li><a href=\"http://givewp.test/support/\">Give Support</a>\n<ul>\n    <li><a href=\"http://givewp.test//priority-support/\">Priority Support</a></li>\n    <li><a href=\"http://givewp.test/support/\">How Support Works</a></li>\n    <li><a href=\"http://givewp.test/contact-us/\">Contact Us</a></li>\n    <li><a href=\"https://wordpress.org/support/plugin/give\">WordPress.org Support</a></li>\n    <li><a href=\"http://givewp.test/my-account/\">My Account</a></li>\n    <li><a href=\"http://givewp.test/my-downloads/\">My Downloads</a></li>\n    <li><a href=\"http://givewp.test//my-account/#tab_licenses\">My Licenses</a></li>\n    <li><a href=\"http://givewp.test/affiliate-area/\">Affiliate Dashboard</a></li>\n</ul>\n</li>\n</ul></li></ul>\n</li><li><a href=\"http://givewp.test/blog/\">Blog</a>\n<ul>\n<li><ul><li><a href=\"http://givewp.test/blog/\">Blog</a>\n<ul>\n    <li><a href=\"http://givewp.test/category/fundraising/\">Fundraising</a></li>\n    <li><a href=\"http://givewp.test/category/giving-tuesday/\">Giving Tuesday</a></li>\n    <li><a href=\"http://givewp.test/category/nonprofit-101/\">Nonprofit 101</a></li>\n    <li><a href=\"http://givewp.test/category/tips-tutorials/\">Tips &#038; Tutorials</a></li>\n    <li><a href=\"http://givewp.test/category/gutenberg/\">Gutenberg</a></li>\n    <li><a href=\"http://givewp.test/category/give-stories/\">Give Stories</a></li>\n    <li><a href=\"http://givewp.test/category/case-studies/\">Case Studies</a></li>\n</ul>\n</li>\n</ul></li></ul>\n</li><li><a title=\"View your Give Account\" href=\"http://givewp.test/my-account/\">My Account</a></li><li><a title=\"Login to your Give Account (Customers Only)\" href=\"http://givewp.test//wp-login.php\">Login</a></li><li><a title=\"Click here to view the available Give add-on bundles\" href=\"http://givewp.test//addon-bundles/\">Buy Now</a></li></ul>     \n    \n\n\n\n    \n        Not Found  \n\n\n    \n        \n            \n                \n                    \n                \n                \n                    \n                        <h3>Aw shucks, 404 Not Found</h3>\n\n                        <p>It looks like something went wrong and you ended up on our 404 page. Perhaps it was a bad link or a typo? Let's get you back on track.</p>\n                    \n                    \n                        <a href=\"http://givewp.test/downloads/plugins/give-recurring/readme.txt?edd-flush=1\">Go Back </a>\n                        <a href=\"http://givewp.test\">Go Home </a>\n                    \n                \n            \n            <!-- .give-message-wrap -->\n        \n\n        \n\n            \n                Helpful Resources\n\n                <p>Looking for assistance with Give? Try reading the documentation for general Give instructions, Add-on and theme configuration resources, and much more.</p>\n\n                <p><a href=\"/documentation/\">Give Documentation</a></p>\n\n                <p>Still have unanswered questions? No worries. We love our customers and we're always glad to help you out if you have any problems with the plugin.</p>\n\n                <p><a href=\"/support/\">Open a support ticket</a></p>\n            \n        \n\n        \n\n            \n                            \n\n        \n        \n            \n                              Latest Blog Posts      <ul>\n                                            <li>\n                    <a href=\"http://givewp.test/wordpress-fundraising/\">Why People Use WordPress for Online Fundraising</a>\n                                    </li>\n                                            <li>\n                    <a href=\"http://givewp.test/10-donation-page-best-practices/\">10 Donation Page Best Practices For Your Website and Beyond</a>\n                                    </li>\n                                            <li>\n                    <a href=\"http://givewp.test/turn-volunteers-into-donors/\">How Do You Turn Volunteers Into Donors?</a>\n                                    </li>\n                                            <li>\n                    <a href=\"http://givewp.test/instagram-nonprofit-storytelling/\">How to Use Instagram for Nonprofit Storytelling with 3 Examples</a>\n                                    </li>\n                                            <li>\n                    <a href=\"http://givewp.test/cybersecurity-nonprofit-hacker-target/\">Why Hackers Target Nonprofit Websites and How to Defend Against It</a>\n                                    </li>\n                    </ul>\n                  \n        \n    \n<!--/.wrap -->\n\n\n    \n        \n            <h3>Featured in:</h3>\n            <ul>\n                                    <li>\n                        \n                    </li>\n                                    <li>\n                        \n                    </li>\n                                    <li>\n                        \n                    </li>\n                                    <li>\n                        \n                    </li>\n                                    <li>\n                        \n                    </li>\n                            </ul>\n        \n    \n\n\n    \n        \n            \n\n\n    <ul><li><a title=\"View Give Add-ons\" href=\"http://givewp.test/downloads/\">Add-ons</a>\n<ul>\n    <li><a href=\"http://givewp.test/downloads/\">View All Add-ons</a></li>\n    <li><a href=\"http://givewp.test/addon-bundles/\">Add-on Bundles</a></li>\n    <li><a href=\"http://givewp.test/downloads/recurring-donations/\">Recurring Donations</a></li>\n    <li><a href=\"http://givewp.test/downloads/category/must-have/\">Must Have Add-ons</a></li>\n    <li><a href=\"http://givewp.test/downloads/category/payment-gateways/\">Payment Gateways</a></li>\n    <li><a href=\"http://givewp.test/downloads/category/marketing/\">Marketing Add-ons</a></li>\n</ul>\n</li>\n<li><a href=\"http://givewp.test/about-give/\">About Give</a>\n<ul>\n    <li><a href=\"http://givewp.test/about-give/\">The Give Story</a></li>\n    <li><a href=\"http://givewp.test/category/case-studies/\">Case Studies</a></li>\n    <li><a href=\"http://givewp.test/careers/\">Careers at Give</a></li>\n    <li><a href=\"http://givewp.test/features/\">Features</a></li>\n    <li><a href=\"http://givewp.test/become-an-affiliate/\">Become an Affiliate</a></li>\n    <li><a href=\"http://givewp.test/contact-us/\">Contact Us</a></li>\n</ul>\n</li>\n<li><a href=\"http://givewp.test/support/\">Support</a>\n<ul>\n    <li><a href=\"http://givewp.test/support/\">How Support Works</a></li>\n    <li><a href=\"https://wordpress.org/support/plugin/give\">WordPress.org Support</a></li>\n    <li><a href=\"http://givewp.test//login\">Login to Account</a></li>\n    <li><a href=\"http://givewp.test/priority-support/\">Priority Support</a></li>\n    <li><a href=\"http://givewp.test/affiliate-area/\">Affiliate Area</a></li>\n    <li><a>My Account</a></li>\n    <li><a href=\"http://givewp.test/my-downloads/\">My Downloads</a></li>\n    <li><a href=\"http://givewp.test/subscriptions/\">My Subscriptions</a></li>\n    <li><a href=\"http://givewp.test/licenses/\">My Licenses</a></li>\n</ul>\n</li>\n<li><a href=\"http://givewp.test/blog/\">Blog</a>\n<ul>\n    <li><a href=\"http://givewp.test/category/news/\">Give News</a></li>\n    <li><a href=\"http://givewp.test/category/tips-tutorials/\">Tips &#038; Tutorials</a></li>\n    <li><a href=\"http://givewp.test/category/giving-tuesday/\">Giving Tuesday</a></li>\n    <li><a href=\"http://givewp.test/category/give-stories/\">Give Stories</a></li>\n    <li><a href=\"http://givewp.test/category/nonprofit-101/\">Nonprofit 101</a></li>\n    <li><a href=\"http://givewp.test/category/give-live/\">Give LIVE!</a></li>\n</ul>\n</li>\n</ul>\n\n\n    \n        \n    \n    \n        \n            \n        \n        \n            <a href=\"https://twitter.com/GiveWP\">Follow @GiveWP</a>\n        \n    \n    \n        <ul>\n            <li>\n                <a href=\"https://github.com/impress-org/give\">\n                    \n                    GitHub\n                </a>\n            </li>\n            <li>\n                <a href=\"https://wordpress.org/plugins/give/\">\n                    \n                    WordPress\n                </a>\n            </li>\n            <li>\n                <a href=\"https://www.facebook.com/wpgive/\">\n                    \n                    Facebook\n                </a>\n            </li>\n            <li>\n                <a href=\"https://twitter.com/GiveWP\">\n                    \n                    Twitter\n                </a>\n            </li>\n            <li>\n                <a href=\"https://www.youtube.com/channel/UCyyvEqgKPNsErzGrrULPmeQ\">\n                    \n                    YouTube\n                </a>\n            </li>\n        </ul>\n    \n    \n                <ul>\n            <li><a href=\"http://givewp.test/refund-policy\">Refund Policy</a></li>\n            <li><a href=\"http://givewp.test/terms\">Terms of Use</a></li>\n            <li><a href=\"http://givewp.test/privacy-policy\">Privacy</a></li>\n        </ul>\n    \n    \n        &copy; 2019 Impress.org, LLC. All rights reserved.\n    \n\n            \n        \n    \n    \n        \n            \n                                <p>Made with  in San Diego, CA.</p>\n            \n        \n    \n\n\n<!-- Facebook Conversion Code for Leads - Givewp Pixel -->\n(function () {\n        var _fbq = window._fbq || (window._fbq = []);\n        if ( !_fbq.loaded ) {\n            var fbds = document.createElement( 'script' );\n            fbds.async = true;\n            fbds.src = '//connect.facebook.net/en_US/fbds.js';\n            var s = document.getElementsByTagName( 'script' )[0];\n            s.parentNode.insertBefore( fbds, s );\n            _fbq.loaded = true;\n        }\n    })();\n    window._fbq = window._fbq || [];\n    window._fbq.push( ['track', '6020596486293', {'value': '0.00', 'currency': 'USD'}] );\n\n\n    \n\n\n<!-- Facebook Like Button -->\n\n(function(d, s, id) {\n        var js, fjs = d.getElementsByTagName(s)[0];\n        if (d.getElementById(id)) return;\n        js = d.createElement(s); js.id = id;\n        js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v2.8\";\n        fjs.parentNode.insertBefore(js, fjs);\n    }(document, 'script', 'facebook-jssdk'));\n\n    <!--Ouibounce / Magnific Popup -->\n    \n\n        <p>Support Your Cause With Give</p>\n        [caldera_form id=\"CF563a884b5e8c8\"]\n    \n\n    \n        (function ( $ ) {\n\n            $( document ).on( 'ready', function () {\n                //Exit Intent Popup\n                ouibounce( document.getElementById( 'ouibounce-modal' ), {\n                    sitewide    : true,\n                    cookieExpire: 7,\n                    timer       : 0,\n                    callback    : function () {\n                        // Open directly via API\n                        $.magnificPopup.open( {\n                            items: {\n                                src : $( '#ouibounce-modal' ), // can be a HTML string, jQuery object, or CSS selector\n                                type: 'inline'\n                            }\n                        } );\n                    }\n                } );\n            } );\n\n        })( jQuery )\n    \n\n                \n                    jQuery(document).ready(function($) {\n                        $('.edd-coming-soon-vote-btn').on('click', function() {\n                            $(this).text('Voting...');\n                        });\n                    });\n                \n\n            \n/*  */\n\n\n\n\n\n\n/*  */\";s:9:\"changelog\";s:0:\"\";}",
            'banners' =>
                "a:2:{s:4:\"high\";s:82:\"http://givewp.test//wp-content/uploads/2019/06/givewp-wpadmin-plugin-banner-HD.png\";s:3:\"low\";s:79:\"http://givewp.test//wp-content/uploads/2019/06/givewp-wpadmin-plugin-banner.png\";}",
            'icons' =>
                "a:2:{s:2:\"1x\";s:68:\"http://givewp.test/wp-content/uploads/2019/06/icon-givewp-square.png\";s:2:\"2x\";s:68:\"http://givewp.test/wp-content/uploads/2019/06/icon-givewp-square.png\";}"
        ];
        // @codingStandardsIgnoreEnd

        return array_merge($defaultAddonData, $addonData);
    }
}
