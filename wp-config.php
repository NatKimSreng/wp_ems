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
define( 'DB_NAME', 'db_ems' );

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
define( 'AUTH_KEY',         'z*x8?<R@O88G@/(18W/*FrUnn==lf Yk -In_9wHha X8/.t-<@>}gj<BYm^4z_a' );
define( 'SECURE_AUTH_KEY',  '5Stx7M(@Liy?xd@DNFj:v{%ECH87N.-h1y~sf-OazbHx6kUN}RFYB^#t]hsPwfw{' );
define( 'LOGGED_IN_KEY',    '_<6k,@3zR1o++6 +]2{Tq1Znf81UnqX0x#l$N;x^XJl4G%U_Z.4U.&PbErq@MfdV' );
define( 'NONCE_KEY',        'G3>ixm%myS33YALs8|Q1Cq=Z+,]nP> a7|<:j5<Kb|3qPMxNc/d+Y0:D.v4-5oyf' );
define( 'AUTH_SALT',        '.8[kv|5}.G`U^pu+yuniQ/`5U}6({cUnZF)zmY;}B?gX}0JJ:8.;L[JTRTf</CMS' );
define( 'SECURE_AUTH_SALT', '2b@JY_^Mz!Z+9.$geC#znSV!<sE/qrZa=/yqo_>,=cTfT6W~xxJeM)fu:fIezL-;' );
define( 'LOGGED_IN_SALT',   'dFqo}c{#.px7Kg@40x5_w@Bv05-F?Uby+p$oDQW/sWBfG0*YE<-*f,R^bO;`L<b5' );
define( 'NONCE_SALT',       '6~^+n@C5C7d5:uKFIh NluDJJw%nq>j<G=p;=.K( A6M/Wd&N4G8cG%mb*||o}NI' );

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
