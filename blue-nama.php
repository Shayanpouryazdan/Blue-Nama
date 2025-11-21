<?php
/**
 * Plugin Name:       Blue Nama | آبی‌نما
 * Plugin URI:        https://bluestmoon.ir/blue-nama
 * Description:       نمایش داینامیک محتوای هر صفحه با یک شورت‌کد ساده — تولید شده توسط آبی‌ترین ماه
 * Version:           1.0.0
 * Author:            shayandvlpr
 * Author URI:        https://shayandvlpr.ir
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       blue-nama
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least:    6.0
 * WC requires at least: 7.0
 * WC tested up to:   9.3
 */

if (!defined('ABSPATH')) exit;

// --- ثابت‌ها ---
define('BLUE_NAMA_VERSION', '1.0.0');
define('BLUE_NAMA_FILE', __FILE__);
define('BLUE_NAMA_PATH', plugin_dir_path(__FILE__));
define('BLUE_NAMA_URL', plugin_dir_url(__FILE__));

// --- لود فایل‌های اصلی ---
require_once BLUE_NAMA_PATH . 'includes/class-blue-nama-admin.php';
require_once BLUE_NAMA_PATH . 'includes/class-blue-nama-core.php';

// --- اجرای کلاس‌ها ---
add_action('plugins_loaded', function () {
    if (is_admin()) {
        new Blue_Nama_Admin();
    }
    new Blue_Nama_Core();
    
    new Blue_Nama_Updater(BLUE_NAMA_VERSION);
});

// --- حذف تنظیمات در صورت حذف افزونه ---
register_deactivation_hook(__FILE__, function () {
    delete_option('blue_nama_selected_pages');
});