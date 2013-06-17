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
define('DB_NAME', 'kora_wordpress');

/** MySQL database username */
define('DB_USER', 'kora_user');

/** MySQL database password */
define('DB_PASSWORD', 'kora_passw0rd');

/** MySQL hostname */
define('DB_HOST', 'mysql.parkbenchproject.com');

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
define('AUTH_KEY',         '+WDNE}s&qx}0`a]~_FuDJ4xA&L^igka|h5B+#bn2++{9O(7X,)B>HH0+e&uyvSBe');
define('SECURE_AUTH_KEY',  'T.8`D|c6(RoP/X#E)1EoxBKxWULh2n_L-y7Qc,Jd+jD:sm5-N&13/yo~kVo_k|be');
define('LOGGED_IN_KEY',    '~k}#W.&d29UF*1-R6#W`Mw20ST-K1V;2J>jqIMj=f-Q0|VyP@l1_3|}t}+LIwP1t');
define('NONCE_KEY',        'M@+i!.2]@Fra<dVw(VNoSB|u^aB-Y@wca2??+AX*~]~O*cqOpL7w?8+lF|MQp~UM');
define('AUTH_SALT',        'T,b7b`vWOtbY|$AODusPmY9]J6Fw@!n:z1GSoOU09@o;pM1P|GzAlAhuq)|cmY$R');
define('SECURE_AUTH_SALT', 'xrNxbc/Ec,7H8>pB|rJ+pPJ]^5^6T`-O@abD).0E%aI]nZ}5Fpdt$CtUJ?1;?b9!');
define('LOGGED_IN_SALT',   '&D-.;EbnS^^!h2=#`Zv]a QuCHA_+qvJkx,QoS:i^})gKf;23LiO30`nf?A[1O02');
define('NONCE_SALT',       'A,<!v)dFGc14+yvz=!6+K%XjMKq`qgiUZg5m1644#3}aM||*D`p|lr6p(fG5qpSX');

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
