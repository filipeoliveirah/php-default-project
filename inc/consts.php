<?php
/**
 * Configuração base do DeskAdmin
 *
 *
 * Após fazer as devidas configurações este arquivo deverá ser renomeado para
 * const.php
 *
 */

// ** Configurações de Banco de Dados ** //
/** The name of the database for WordPress */
define('DB_TYPE', (int)'1');

/** The name of the database for WordPress */
define('DB_NAME', 'database_name');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', 'utf8');

define('DB_ENGINE', 'InnoDB');
/**
 * Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
define('DB_PREFIX_TABLE', 'tb_');
define('DB_PREFIX_FOREIGNKEY', 'fk_');

/**
 * Não alterar nada daqui para baixo =D
 *
 */
define("SPACE", " ");
define("PATH", realpath(__DIR__."/../"));
define("URL", str_replace("\\", "/", str_replace(PATH, "", str_replace("/", "\\", $_SERVER["SCRIPT_FILENAME"]))));
define("SITE_NAME", basename(PATH));
define("SITE_PATH", str_replace($_SERVER['DOCUMENT_ROOT'], "", str_replace("\\", "/", PATH)));

define("DIR_ADMIN", "admin");
?>
