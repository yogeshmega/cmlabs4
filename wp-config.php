<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache







/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/Editing_wp-config.php Modifier
 * wp-config.php} (en anglais). C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
 //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/var/www/html/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', 'cmlabs_wordpress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '4dabf40c7efdd652cb1f09ee2cdb4b24');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'db:3306');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '20OrE/zgfZ#DXFn|<A9N7D~56ogO!^r|w/IvEj$Q;/1|$A(O)j49:|<?}W~N+045');
define('SECURE_AUTH_KEY',  'LI(l.4e +4<6-i>3+,nvH6xj23Wfbt~V0}8!fIYR7GuasW]AISZ :k8hkM7WC}cb');
define('LOGGED_IN_KEY',    '$kdr#!uJWZcCl%m&^/SZD|]e85Qxp/oIJxad:OE73uJi<oX|r@%ea/##F{U-PA+3');
define('NONCE_KEY',        'c6(N^P-RBH$]|%0V~4^^<`RH-bDwq O=OO3kOP+KE]#UZXEh]!roDg=LtMESj)p=');
define('AUTH_SALT',        'Dm5?+Fr`dLzH8upRjO.v-&{Zh{UP*|r8+P(G,t^Ff`3FiaD?hB.*oAGy`Y:ZwNbz');
define('SECURE_AUTH_SALT', 'Mj3Da@)6S=>-1-PU^c*.%G&+1*- mx_z>RI(]EAwsjHQWBfiYIS7`+pAHZ7&%ng4');
define('LOGGED_IN_SALT',   '^K,o,d2{JuBdP#AR0NR`Ud<(x3b+UemKIs^{wBgb8s]|o`jKGgq=PY3O$_?Md%`7');
define('NONCE_SALT',       'sK-)W~rQT8^LE i j`OKfja ^M.e_B?1Ni7lRlkCTPQ4CSvO-E1OY%^!M+_j@b}J');

/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
#define('WPLANG', 'fr_FR');

/**
 * Pour les développeurs : le mode deboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 */
define('WP_DEBUG', false);

#ini_set('log_errors','On');
#ini_set('display_errors','On');
#ini_set('error_reporting', E_ALL );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

#ini_set('error_reporting', E_ALL );

/* Multisite */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'www.cm-labs.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define( 'WP_ALLOW_MULTISITE', true );

//define( 'NOBLOGREDIRECT', 'http://cm-labs.com' );

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

# CM Labs: Required for SLL being a reverse-proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    $_SERVER['HTTPS'] = 'on';


/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
