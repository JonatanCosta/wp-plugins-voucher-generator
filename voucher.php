<?php
/**
 * Plugin Name
 *
 * @package     Voucher Generator
 * @author      Jonatan Costa da Rosa
 * @copyright   2018  - Jonatan Costa da Rosa
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Voucher Generator
 * Plugin URI:  https://github.com/JonatanCosta/wp-plugins-voucher-generator
 * Description: Plugin para gerar Vouchers Codes dinamicamente, através do usuario fornecer email.
 * Version:     1.0.0
 * Author:      Jonatan Costa
 * Author URI:  https://github.com/JonatanCosta
 * Text Domain: plugin-name
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* Quando ativar o plugin */
register_activation_hook( __FILE__, "voucher_activate" );

require_once 'modules/Voucher.php';
require_once 'modules/Utils.php';

/*
* Init Plugin
*/
initVoucher();

/*
 * Ativar o Plugin
 */
function voucher_activate()
{
    if ( version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
        echo '
		<div id="message" class="error">
			<p><strong>' . __( "Desculpe, sua versão do PHP não é compativel com esse plugin.") . '</strong></p>
		</div>
		';
        return;
    }

    voucher_create_tables();
}
/*
* Create tables
*/
function voucher_create_tables()
{
    $prefix = get_db_prefix();

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_vouchers = "CREATE TABLE IF NOT EXISTS " . $prefix . "vouchers (
			  id int unsigned NOT NULL AUTO_INCREMENT,
			  name VARCHAR(50) NOT NULL,
			  `description` TEXT NULL,
			  codeprefix VARCHAR(50) DEFAULT '',
			  deleted BOOLEAN DEFAULT 0,
			  active BOOLEAN DEFAULT 0,
			  generates_per_day int DEFAULT 0,
			  PRIMARY KEY  id (id)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    dbDelta($table_vouchers);

    $table_vouchers_codes = "CREATE TABLE IF NOT EXISTS " . $prefix . "voucher_codes (
			  id int unsigned NOT NULL AUTO_INCREMENT,
			  voucher_id int unsigned,
			  code VARCHAR(250) NOT NULL,
			  deleted tinyint DEFAULT '0',
			  PRIMARY KEY  id (id),
              FOREIGN KEY (voucher_id) REFERENCES ".$prefix."vouchers(id)
			) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    dbDelta($table_vouchers_codes);

    sleep(1);
}