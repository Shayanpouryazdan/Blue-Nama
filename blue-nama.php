<?php
/**
 * Plugin Name:       آبی‌نما | Blue Nama
 * Plugin URI:        https://bluestmoon.ir/blue-nama
 * Description:       نمایش داینامیک محتوای هر صفحه با یک شورت‌کد ساده — تولید شده توسط آبی‌ترین ماه
 * Version:           1.1.0
 * Author:            shayandvlpr
 * Author URI:        https://shayandvlpr.ir
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       blue-nama
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ---------------------------
// ثابت‌ها
// ---------------------------
define( 'BLUE_NAMA_VERSION', '1.1.0' );
define( 'BLUE_NAMA_FILE', __FILE__ );
define( 'BLUE_NAMA_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLUE_NAMA_URL', plugin_dir_url( __FILE__ ) );

// ---------------------------
// لود فایل‌ها
// ---------------------------
require_once BLUE_NAMA_DIR . 'includes/class-blue-nama-admin.php';
require_once BLUE_NAMA_DIR . 'includes/class-blue-nama-core.php';
require_once BLUE_NAMA_DIR . 'includes/class-blue-nama-updater.php';

add_action( 'plugins_loaded', function() {

    if ( is_admin() ) {
        new Blue_Nama_Admin();
    }

    new Blue_Nama_Core();
    new Blue_Nama_Updater( 'Shayanpouryazdan', 'Blue-Nama', BLUE_NAMA_VERSION );
});