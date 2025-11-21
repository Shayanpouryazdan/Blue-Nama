<?php
if (!defined('ABSPATH')) exit;

class Blue_Nama_Core {

    public function __construct() {
        add_shortcode('blue_nama', [$this, 'shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_styles']);
    }

    public function shortcode($atts) {
        $atts = shortcode_atts(['id' => 0], $atts, 'blue_nama');
        $page_id = intval($atts['id']);
        if (!$page_id) return '';

        $allowed = get_option('blue_nama_pages', []);
        if (!in_array($page_id, $allowed)) {
            return current_user_can('manage_options')
                ? '<p style="color:#d63638;background:#fff3cd;padding:12px;border-radius:6px;">این صفحه در <a href="' . admin_url('edit.php?post_type=page&page=blue-nama') . '">آبی‌نما</a> فعال نیست.</p>'
                : '';
        }

        $page = get_post($page_id);
        if (!$page || $page->post_status !== 'publish') {
            return '<p>صفحه یافت نشد یا منتشر نشده است.</p>';
        }

        $content = apply_filters('the_content', $page->post_content);
        return '<div class="blue-nama-content">' . $content . '</div>';
    }

    public function frontend_styles() {
        if (has_shortcode(get_the_content(), 'blue_nama')) {
            wp_add_inline_style('theme-style-handle', '.blue-nama-content { line-height: 1.8; margin: 2em 0; }');
        }
    }
}