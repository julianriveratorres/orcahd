<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'P<h^q[Oyn3]U&Ud|I?=92zfxS;z]p3:#Y;1j5i{PAE,lS+[f,3foKB6lVL^4ABwt' );
define( 'SECURE_AUTH_KEY',   'dxPoAGVQB*?F:}WbS^jZJ`g3W`Z2]#p ~Cyr$XTH|L-.{O+T tZntN&VMr!VvKN}' );
define( 'LOGGED_IN_KEY',     'agBmG9/Tv.[PERJlV$;lB8/$Dx}P0n7uW>j6p-JW!o_Ja0t!HaS][~}<+B/y{9Z1' );
define( 'NONCE_KEY',         '2hL.zV<Xs~7!f8^|]p7JznTj*h?Al4^vo=#V2GfXRlb?Jqv!pn7CyN]jEU<NB}!R' );
define( 'AUTH_SALT',         '8gONp18Vlz`AKw9gKF+Mwb=FBy]/eC.Q7?0StB`bL^tGmzPib4T(l_oeFcqdhqpR' );
define( 'SECURE_AUTH_SALT',  'o~~c|Qv5~157;RyRws%-N{1Fh?R7*L^_v59O-W@&_!}JPm_=MLhs7Ux!*uLF~ L]' );
define( 'LOGGED_IN_SALT',    'yR~@.FhNBVFyfE=7lkTzBws$z>FEa@SDVl-=iW%t*&=ktn~oMU>j/oq>q|G$i&ls' );
define( 'NONCE_SALT',        '=I&_H$+p*jhA6i%M3T&1IfNF|E%pQ{c=mJ%9=A_oOUe%?x2Simi1h{-]+jr@+rDW' );
define( 'WP_CACHE_KEY_SALT', 'L<_%K5ZEy`v<6x>As@6S}_gni6$t`#~qyNC{W}%Ok/.a1uYCFdW)p-rSIE?knonQ' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
