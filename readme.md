## Status

Develop: [![CircleCI](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/develop.svg?style=svg)](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/develop)

Staging: [![CircleCI](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/staging.svg?style=svg)](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/staging)


Master: [![CircleCI](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/master.svg?style=svg)](https://circleci.com/gh/impress-org/givewp-website-licensing-server/tree/master) 

## Local Development

### Prerequisites

- Git
- Composer
- yarn

### Instructions

1. Set up an empty website in Local Lightning.
    - Create a new local site:
        - Local site name: `lumen.test`
        - Local site domain: `lumen.test`
        - Local site path: `~/PATH_TO_LOCAL/lumen.test`
            - Do *NOT* include spaces in your path.
        - Environment: Custom (not Preferred)
        - PHP Version: 7.2
        - Web Server: nginx
        - MySQL Version: 5.6
        - Enter any WordPress credentials as they will not be used.
        - Trust the certificate in the SSL tab of Local Lightning.
    - Change to the site's root directory: `cd ~/PATH_TO_LOCAL/lumen.test`
    - Clear out the WordPress files from the `app` directory: `rm -rf app/*`
    - Now is a good time to create an "empty" blueprint in Local to save time in the future.
2. Clone the project locally.
    - From the site's root directory, clone the project into the empty `app` directory: `git clone https://github.com/impress-org/givewp-website-licensing-server.git app`
3. Open the `lumen.test` directory in your code editor.
4. Configure the environment.
    - Rename `.env.example` to `.env`.
    - Set `APP_URL` to `https://lumen.test`.
    - Set `APP_KEY` any random encrypted string. You can use https://randomkeygen.com/ to get encrypted password
    - Set `GIVEWP_LICENSE_ENDPOINT` to local copy for givewp.com, so can be `https://givewp.test`
    - Set `GIVEWP_USER` any random user email
    - Set `GIVEWP_PASSKEY` encrypted password string. You can use https://passwordsgenerator.net/ to get encrypted password.
    - Set `JWT_SECRET` any random encrypted string. You can use same site as mentioned for `APP_KEY`.
4. Install PHP dependencies.
    - In `lumen.test/app`, run: `composer install`
7. Install JS dependencies.
    - In `lumen.test/app`, run: `yarn && yarn dev`
8. Set up the database.
    - To wipe the database and start fresh, run: `php artisan migrate:fresh`
9. Set Local  to force HTTPS.
    - From the site's root directory, open `confg/nginx/site.cnf`.
    - Add the following lines below `root /app/public/;`:

        ```
        if ($http_x_forwarded_proto != "https") {
            rewrite ^(.*)$ https://lumen.test$1 permanent;
        }
        ```
    - Finally, restart the site in Local.
10. Open the site at `https://lumen.test`.

### Running Migrations

The database in Laravel is set up through a series of migrations found in `database/migrations`. To run these migrations
bring up the command line and run `php artisan migrate`. This will run all migrations that have not been run yet.

To start from scratch with a clean and empty database, re-running all migrations, use `php artisan migrate:fresh`
instead.

### Running PHPUnit Tests

Running PHPUnit tests requires an additional `.env.testing` file. You may use a different database or the same, just keep
in mind that unit tests will overwrite the database. You will also want to add the following to avoid errors:

```
TELESCOPE_ENABLED=false
```

1. SSH login to `https://lumen.test`
2. run `vendor/bin/phpunit` in `~/app` project root folder
