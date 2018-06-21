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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'expressdbfinal');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define( 'SCRIPT_DEBUG', true );define('WPCF7_LOAD_CSS', false);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'gziabfCdmKwiv=K/wdUq^lkREgC_P3c5vQrrb4vWh3uO9/od2yKbZUSAsTDvAmgv');
define('SECURE_AUTH_KEY',  'dgIlxuqT(s#rzyvE!4Am#5ogZF1IWr2a/zkWA4StRqjiyUUz9EGu3j0ek2(QSwF-');
define('LOGGED_IN_KEY',    'gSVx1TIKLC14UdeZe!!J2YGGU6WdIb(WOaoxzU^L9qxko(3G8bMbBnJz-/26G4rg');
define('NONCE_KEY',        'MPb7SwhATektG#j_8zQxjFxe!pokFC!PiYldqT-aD#2vfxd/hQ^x(IJ^re)1uOgZ');
define('AUTH_SALT',        '/B-PbRLgP!dYCcUKn^ob8)qf-r)t(XIEQ#VTBFDiB=wF(s2cW(nr+!FCNTWucJ9U');
define('SECURE_AUTH_SALT', 'ZryD5OgIFQPSW+E)6eo/urTWG5qSX_ExZxDA-b22g6d(4YHlRp7WIEafgeEmzk!m');
define('LOGGED_IN_SALT',   'qKOX9aVbq65LPu_gaYC+mRTE#=8c#lwIu7TU1TLw=nKbgCYWK6d19y#foB)jagP^');
define('NONCE_SALT',       'nmEk40PeOoSKl=2T^J9BuST/pfVn7sYjSR4eQk7!UHx66n8mzXJxPIiwUWWkOn_1');

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
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
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
define('WP_DEBUG', false);

/**
 *  Change this to true to run multiple blogs on this installation.
 *  Then login as admin and go to Tools -> Network
 */
define('WP_ALLOW_MULTISITE', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* Destination directory for file streaming */
define('WP_TEMP_DIR', ABSPATH . 'wp-content/');

