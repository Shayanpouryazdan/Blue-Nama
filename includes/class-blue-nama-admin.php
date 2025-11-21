<?php
if (!defined('ABSPATH')) exit;

class Blue_Nama_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_submenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_submenu() {
        add_submenu_page(
            'edit.php?post_type=page',
            'آبی‌نما',
            'آبی‌نما',
            'manage_options',
            'blue-nama',
            [$this, 'render_page']
        );
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'pages_page_blue-nama') return;

        wp_enqueue_style('blue-nama-admin', BLUE_NAMA_URL . 'assets/css/admin.css', [], BLUE_NAMA_VERSION);
        wp_enqueue_script('blue-nama-admin', BLUE_NAMA_URL . 'assets/js/admin.js', ['jquery'], BLUE_NAMA_VERSION, true);
    }

    public function render_page() {
        if (isset($_POST['blue_nama_save']) && check_admin_referer('blue_nama_save')) {
            $selected = array_map('intval', (array)$_POST['selected_pages']);
            update_option('blue_nama_pages', $selected);
            echo '<div class="notice notice-success is-dismissible"><p>تنظیمات با موفقیت ذخیره شد</p></div>';
        }

        $selected_pages = get_option('blue_nama_pages', []);
        $pages = get_pages(['sort_column' => 'post_title']);
        ?>
        <div class="blue-nama-wrap">
            <div class="blue-header">
                <h1 class="big-white-bold">آبی‌نما</h1>
                <p>هر صفحه را با یک شورت‌کد ساده در هر جای سایت نمایش دهید</p>
            </div>

            <div class="blue-card">
                <form method="post" id="blue-nama-form">
                    <?php wp_nonce_field('blue_nama_save'); ?>
                    <div class="table-header">
                        <label><input type="checkbox" id="select-all"> انتخاب همه</label>
                        <button type="submit" name="blue_nama_save" class="button button-primary">ذخیره تغییرات</button>
                    </div>

                    <table class="wp-list-table widefat striped">
                        <thead>
                            <tr>
                                <th class="check-column"></th>
                                <th>عنوان صفحه</th>
                                <th>شلت‌کد</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pages as $page):
                                $checked = in_array($page->ID, $selected_pages) ? 'checked' : '';
                                $shortcode = '[blue_nama id="' . $page->ID . '"]';
                            ?>
                            <tr>
                                <td><input type="checkbox" name="selected_pages[]" value="<?php echo $page->ID; ?>" <?php echo $checked; ?>></td>
                                <td><strong><?php echo esc_html($page->post_title); ?></strong></td>
                                <td>
                                    <code class="shortcode-code"><?php echo $shortcode; ?></code>
                                    <button type="button" class="copy-btn" data-clipboard-text="<?php echo esc_attr($shortcode); ?>">کپی</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>

            <!-- فوتِر زیبا و حرفه‌ای -->
            <div class="blue-footer">
                <div class="footer-left">
                    <p>تولید شده توسط <a href="https://bluestmoon.ir" target="_blank">آبی‌ترین ماه</a></p>
                    <p>کد نویسی و توسعه توسط <a href="https://shayandvlpr.ir" target="_blank">shayandvlpr</a></p>
                </div>
                <div class="footer-right">
                    <p>نسخه 1.0.0</p>
                </div>
            </div>
        </div>
        <?php
    }
}