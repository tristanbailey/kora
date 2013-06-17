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
define('DB_NAME', 'kora_blog');

/** MySQL database username */
define('DB_USER', 'koraus3r');

/** MySQL database password */
define('DB_PASSWORD', 'k0r4pl4t34u');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'z0/n;j,Xbdza+6o^ZuVI|0L=zQ#n`dtugG8{3L3_3>vN`3_aX7a>SeezE865:u8E');
define('SECURE_AUTH_KEY',  'u>|L0dkZuqWKDoi{}Fv[k?&)p8FM:TtV=Qm?15K#r0|H-5(GQ]c^!V/t^7pxR[JG');
define('LOGGED_IN_KEY',    'EBy:H!64%o}A%!ONWM+E|#rG_!$VPxy7*&:gmF{-`:N-aDBc8+O_!Ag/_N4 tBS9');
define('NONCE_KEY',        '7~S07iqfu7BFg%B*9CQsO,[:&S~v&VD*lHC;,XY@Vz}z77yag`ltG*8o~fW|j`9~');
define('AUTH_SALT',        'r/+wayiJj@j),=5L|wTL*ALqp!B`m[}6a<|zop.^_3c j5-5+,G,qhq}P[%N82a|');
define('SECURE_AUTH_SALT', 'K@]&k783Wse9I+zM80ub^oKej;>d*_}}i+Zb@!ZE}J8}46H7A?d=qY z5b.GW] ?');
define('LOGGED_IN_SALT',   '$F2bw=$kU8IOFn=|>-K=+#Hz0hY jAN!vjQtlcbKEs3kb ; 1&ikpRD>,K!C)7Pn');
define('NONCE_SALT',       'xb)W}3l *:G5h.z}n&/vDGwQlv*s6yOz5#&$F}rE=0n_O?=ui/[HsTI__TjBU1nJ');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
