<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'O%)EV5X.lT89B}#zj_:38:[CQS:G/iPN8p-UiMujwMEBizx_pp*(91:&ey=sk3SS' );
define( 'SECURE_AUTH_KEY',  's#AkX[Y<#KeW!obCx?j5*Aw<{icJ3~ngFD9q;eQ]4^(PM~p6yc76){Rz=*=G372j' );
define( 'LOGGED_IN_KEY',    '5e[!$0G2sX%i[xPL l*M#}F=-<e+`yf-Xi+9aV;^FhIP2%Z`^>+6om]uqo=E&)+g' );
define( 'NONCE_KEY',        '>I1*#BdlDn;!FPM[fNr=;H;p)*polv!5?uv8c7RgJbNnq[a;[F0~icgQ}Eu~;0,f' );
define( 'AUTH_SALT',        'Epj6JDsWD=;Fa~G8c<%<t$!U}rX.3yKuVEqgG7Md J&tCg8@W~5?;bvKd.JD)<W_' );
define( 'SECURE_AUTH_SALT', 'UeO{L]/RZP8TY9|O&tvv7ot6LPF$Fu1#X-Gdg{HyaiVJCM*Z|IUOtyjFWBclz)ey' );
define( 'LOGGED_IN_SALT',   'cDum,h>H{IK^WzOejcJQjU;9~_paOnV~3XD(NoTN?%9cK@]N>Uz6AR_2{%<1|U>g' );
define( 'NONCE_SALT',       'pDt)RbhV-<p1J88WrqF=(Hr%*Nwt~(s%(Ac6!T6KIQ&l2[Oayv01M8?,EUd=+4{S' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */


define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true );
define('SUBDOMAIN_INSTALL', false );
define('DOMAIN_CURRENT_SITE', 'localhost:8000' );
define('PATH_CURRENT_SITE', '/wordpress/' );
define('SITE_ID_CURRENT_SITE', 1 );
define('BLOG_ID_CURRENT_SITE', 1 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
