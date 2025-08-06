<?php
define('WP_CACHE', true);
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sa_msiguide' );

/** MySQL database username */
/** define( 'DB_USER', 'k4495279_wp405' ); */
define( 'DB_USER', 'ITAdmin' );

/** MySQL database password */
/** define( 'DB_PASSWORD', 'p5z](K2n9S' ); */
define( 'DB_PASSWORD', 'P@ssw0rd.1' );

/** MySQL hostname */
define( 'DB_HOST', '10.20.50.161' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'yd1b8te3zb5ouvpq5mthig5dvsuk8tzaetjydxvsvzsc4igyb8luvmwdvpmrpa7j' );
define( 'SECURE_AUTH_KEY',  'stlauu6so5nnhuoszpcobzk5sfkwttdvy5linc0hgnrquoq7yaa7etmthljzde27' );
define( 'LOGGED_IN_KEY',    'hai0ncc7nmfwxij5wguupisiob6duugdd3mppr8oejudwmtcgvtqu1ywdezxjeev' );
define( 'NONCE_KEY',        'hornyyty6fk4ydfhqznzaxejxfymrnngivuco79lwqmiplio2tx7msfhngxa01lc' );
define( 'AUTH_SALT',        'rpgzkt009sjxpkbpcar0xsqtky6cbwdbhwcczb6rmxn8bgljirdgil8dfdnw45u6' );
define( 'SECURE_AUTH_SALT', 'ijqt5s0rhrz2pv6vpnz8dvffwvu8s1jkkyqlc3svlehq4k1ksabpgavvmmqoep2w' );
define( 'LOGGED_IN_SALT',   'vewm8uegbr3oicsm9rypllpzva6ipaabuopmb1kgzosnsz6hwb1sjjlnibx7bm7l' );
define( 'NONCE_SALT',       '7d0rp12a92byb0vtnm8kq9rkcwo8d269wusbdtvab3jowsv4qcpx70gaftntchei' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
