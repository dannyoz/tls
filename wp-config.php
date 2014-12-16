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
define('DB_NAME', 'tlsdev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'brabantia');

/** MySQL hostname */
define('DB_HOST', '10.169.34.9');

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
define('AUTH_KEY',         'e;/}uMhiHc2R,@G#&ToVCq^s*]-N%*5ep@4,5%Mz<08[jn?%P~8sU6C5yd^=m<jT');
define('SECURE_AUTH_KEY',  'n2PMgsa@N;VmF=&Tr8xaTtRWH`aW8+I5(:*>ZFZU0wjqb(:Lr7S$fXTWJi(|TNez');
define('LOGGED_IN_KEY',    'k)#a@$ten2mHHG4KMZ8}*2qbC9;_@1-6)%Tz}fcZ1r:Xo|/wt<wm$eOG@!+>/kgq');
define('NONCE_KEY',        'L`C$h{fs,-{2n`|z;k>%C)FhrnX+!<(MsxcZU;b@pNB_Wf;f$f,416++~F$&#88F');
define('AUTH_SALT',        'SQ(cBRs;4,W]B/f|9O:uh2[RM}1s$LFE/hKVYdFfOZ{Pd/t-S3V#Wp%8+8sE#25B');
define('SECURE_AUTH_SALT', 'pwGz8?.n?>mZ[^]@8-Cqq.`][lXlSwYdWU}0E8$)o]|l>e[S|LhsV4#{0w|JDyrj');
define('LOGGED_IN_SALT',   ';DUBl47__0uFcGAk.`8g76CFW*rjCZF=P-HrC%nXU;_I+*o[mF|~.L3,z|0K-_lc');
define('NONCE_SALT',       'jIL!~#QdAV42* L%.$K|#dQ-1@^%|.A~),}8Qj uCM0boUtuhVf|`u?wOZvbw4wb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '_tls_wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
