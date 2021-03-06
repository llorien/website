<?php
    /**
     * The base configurations of the WordPress.
     *
     * This file has the following configurations: MySQL settings, Table Prefix,
     * Secret Keys, WordPress Language, and ABSPATH. You can find more information
     * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
     * wp-config.php} Codex page. You can get the MySQL settings from your web host.
     *
     * This file is used by the wp-config.php creation script during the
     * installation. You don't have to use the web site, you can just copy this file
     * to "wp-config.php" and fill in the values.
     *
     * @package WordPress
     */

    // Required for batcache use
    // define('WP_CACHE', true);
    // configures batcache
    // $batcache = [
    //   'seconds'=>0,
    //   'max_age'=>30*60, // 30 minutes
    //   'debug'=>false
    // ];

    $appengine_app_ids = array(
        'prod' => 'brithon-prod',
        'dev' => 'brithon-dev',
        'local' => 'brithon-local'
    );

    use \google\appengine\api\app_identity\AppIdentityService;
    // running on appengine
    if (isset($_SERVER['APPLICATION_ID'])) {
        $application_id = AppIdentityService::getApplicationId();

        // online GAE
        switch ($application_id) {
            case $appengine_app_ids['prod']:
                /** Live environment Cloud SQL login info */
                define('DB_NAME', 'brithon_www');
                define('DB_HOST', ':/cloudsql/brithon-prod:brithon-db');
                define('DB_USER', 'root');
                define('DB_PASSWORD', '');

                define('WP_DEBUG', false);
                define('DISABLE_WP_CRON', true);
                break;
            case $appengine_app_ids['dev']:
                define('DB_NAME', 'brithon_www');
                define('DB_HOST', ':/cloudsql/brithon-dev:brithon-db');
                define('DB_USER', 'root');
                define('DB_PASSWORD', '');

                define('WP_DEBUG', true);
                define('DISABLE_WP_CRON', true);
                break;
            case $appengine_app_ids['local']:
                // local GAE
                define('DB_NAME', 'brithon_www');
                define('DB_HOST', '127.0.0.1');
                define('DB_USER', 'root');
                define('DB_PASSWORD', '');

                define('WP_DEBUG', true);
                define('DISABLE_WP_CRON', true);
                break;
            default:
                die('Unrecognized application_id: ' . $application_id);
        }
    } else {
        // running without GAE
        define('DB_NAME', 'brithon_www');
        define('DB_HOST', '127.0.0.1');
        define('DB_USER', 'root');
        define('DB_PASSWORD', '');

        define('WP_DEBUG', true);
    }

    // Determine HTTP or HTTPS, then set WP_SITEURL and WP_HOME
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
    {
        $protocol_to_use = 'https://';
    } else {
        $protocol_to_use = 'http://';
    }
    define( 'WP_SITEURL', $protocol_to_use . $_SERVER['HTTP_HOST']);
    define( 'WP_HOME', $protocol_to_use . $_SERVER['HTTP_HOST']);

    /** Database Charset to use in creating database tables. */
    define('DB_CHARSET', 'utf8');

    /** The Database Collate type. Don't change this if in doubt. */
    define('DB_COLLATE', '');

    /**#@+
     * Authentication Unique Keys and Salts.
     *
     * Change these to different unique phrases!
     * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
     * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
     *
     * @since 2.6.0
     */
    define('AUTH_KEY',         'Rbtr#3L;IH@]3qR1k+[U,$tudX^We]-+u-gnErz+gfAYLh_3mz#:1JR~3 }EH*2^');
    define('SECURE_AUTH_KEY',  'KMF?2DShnRv#AR*f[wVAgEH0[=~&eu%-yLDRSo>kwv:-Sow+v{Pcv[SUlLG*VQ@7');
    define('LOGGED_IN_KEY',    'd$Ako:)%RG9|`BeP{-+gfOS,{Li,U3/Lz-+-y.fb/+Zwc(^F *3LKupTrkzvi[x<');
    define('NONCE_KEY',        '$FLt&Ci?b^9*++wgk!b~u+?.M2S5ZrUs3x7=<!4s[USBL9iE}Tym,Z2+6`OZi=]|');
    define('AUTH_SALT',        '+Tk6Q7e.Q(-W>f#1~sr.H^>)to~;j`Sl|qsNd%8byF!<]b6^+t,0 9rhrd2XW]:N');
    define('SECURE_AUTH_SALT', 'hj]>PE&nEK8>zGFfXyf=L%+_d:)WHo.R=9oI7;XEc&3m=6E2~pQzYVCb*.p}52Ka');
    define('LOGGED_IN_SALT',   'cY2/I9iP^CDOyM7{m>,bC!B%|j!Wf =Y=S=+`oS`{8I3q-cnpDAR&p`9iIbBY<ws');
    define('NONCE_SALT',       '9^ Gn Z=(-<km|.A<8+!]ZE~s5q[_$9iaIsQE[jU6(Z?Pd+H0HV[Td!;[q/(:PTk');
    define('WP_CACHE_KEY_SALT','nA?:u:xergUg-XJl0H05i^I*%H-5-_V+^4LZ?^/{Yiip&DxaU&l?Kq8|tM&u9P_;');

    /**#@-*/

    /**
     * WordPress Database Table prefix.
     *
     * You can have multiple installations in one database if you give each a unique
     * prefix. Only numbers, letters, and underscores please!
     */
    $table_prefix  = 'wp_';

    /**
     * WordPress Localized Language, defaults to English.
     *
     * Change this to localize WordPress. A corresponding MO file for the chosen
     * language must be installed to wp-content/languages. For example, install
     * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
     * language support.
     */
    define('WPLANG', '');

    /**
     * For developers: WordPress debugging mode.
     *
     * Change this to true to enable the display of notices during development.
     * It is strongly recommended that plugin and theme developers use WP_DEBUG
     * in their development environments.
     */
     // <brithon> please set it for different envs.

    /**
     * Disable default wp-cron in favor of a real cron job
     */
     // <brithon> please set it for different envs.
    
    /* That's all, stop editing! Happy blogging. */

    /** Absolute path to the WordPress directory. */
    if ( !defined('ABSPATH') )
        define('ABSPATH', dirname(__FILE__) . '/wordpress/');

    /** Sets up WordPress vars and included files. */
    require_once(ABSPATH . 'wp-settings.php');


