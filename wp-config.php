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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'boostsho_58f' );

/** MySQL database username */
define( 'DB_USER', 'boostsho_58f' );

/** MySQL database password */
define( 'DB_PASSWORD', 'FD392C8f1k5mo0c6pi4y7' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '0ZSGYLyo$q-AGm+qg1v%%YH({Qu8PzN!K41JOpYRTVw~+PP5-6a.g9gT=_D+TI=*');
define('SECURE_AUTH_KEY',  'QWPg#u7xtkrtcA!Q-L*7+0:.4#vQn~g$r|5U$k0@|lZ)jC^jcYMH;% (yV9^w=N5');
define('LOGGED_IN_KEY',    'q;0sB[Z+)egbiyyMD*7|w{t}boI_f|+wC:%$S7Z;Z9^ZI5MIw.-4wF=7sCJ}[z.b');
define('NONCE_KEY',        '<P0O8&KLaxJ;[RjM8ct`_I. ZNP)sda(5Z1.Er#-p1=n^wPo~y.vaDKT8KC6R+Z[');
define('AUTH_SALT',        '[:$n{|r ou+Wk!{U7w$-$#M%P$]0oln^-hktq-1Ge]qRs%)n?qPS^]dL$_kBkd]k');
define('SECURE_AUTH_SALT', '#!w{RCS1:mg^xQD0y+CW<Tt612I6+D4=*mPa8FTP)!] bs<mz->{5ybEi3?n7g8 ');
define('LOGGED_IN_SALT',   'hw7_yh/#7CY>Zv&Ta5V%L.j.@@uSXk_W,(qaHrDa)$|YTN2+Jq6D+an_<1.,NS1y');
define('NONCE_SALT',       '7EPu:?n)OodzBaJM%6Rty|emu?wI^n/9;aB]X3b2}pi({:lwWOY-Ig?F`S?|CkpB');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '58f_';



define( 'AUTOSAVE_INTERVAL',    300  );
define( 'WP_POST_REVISIONS',    5    );
define( 'EMPTY_TRASH_DAYS',     7    );
define( 'WP_AUTO_UPDATE_CORE',  true );
define( 'WP_CRON_LOCK_TIMEOUT', 120  );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
