<?php
if (!defined('ABSPATH')) exit;

/**
 * Blue Nama Updater - آپدیت خودکار از گیت‌هاب
 */
class Blue_Nama_Updater {

    private $user = 'Shayanpouryazdan';
    private $repo = 'blue-nama';
    private $current_version;
    private $plugin_file;
    private $release_zip_url;

    public function __construct($current_version) {
        $this->current_version = $current_version;
        $this->plugin_file = plugin_basename(BLUE_NAMA_FILE);
        $this->release_zip_url = "https://github.com/{$this->user}/{$this->repo}/releases/latest/download/blue-nama.zip";

        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
    }

    public function check_update($transient) {
        if (empty($transient->checked)) return $transient;

        $remote = $this->get_latest_release();

        if (!$remote || empty($remote['tag_name'])) return $transient;

        $remote_version = ltrim($remote['tag_name'], 'v');

        if (version_compare($this->current_version, $remote_version, '<')) {
            $obj = new stdClass();
            $obj->slug = dirname($this->plugin_file);
            $obj->new_version = $remote_version;
            $obj->url = "https://github.com/{$this->user}/{$this->repo}";
            $obj->package = $this->release_zip_url;
            $obj->plugin = $this->plugin_file;

            $transient->response[$this->plugin_file] = $obj;
        }

        return $transient;
    }

    public function plugin_info($false, $action, $args) {
        if ($action !== 'plugin_information') return $false;
        if (!isset($args->slug) || $args->slug !== dirname($this->plugin_file)) return $false;

        $remote = $this->get_latest_release();
        if (!$remote) return $false;

        $remote_version = ltrim($remote['tag_name'], 'v');

        $info = new stdClass();
        $info->name = 'آبی‌نما | Blue Nama';
        $info->slug = dirname($this->plugin_file);
        $info->version = $remote_version;
        $info->author = '<a href="https://shayandvlpr.ir">shayandvlpr</a>';
        $info->author_profile = 'https://shayandvlpr.ir';
        $info->requires = '5.6';
        $info->tested = '6.7';
        $info->last_updated = $remote['published_at'] ?? current_time('mysql');
        $info->sections = [
            'description' => 'نمایش داینامیک محتوای هر صفحه با یک شورت‌کد ساده — ساخته‌شده توسط آبی‌ترین ماه',
            'changelog'   => $remote['body'] ?? 'به‌روزرسانی جدید منتشر شد.'
        ];
        $info->download_link = $this->release_zip_url;

        return $info;
    }

    private function get_latest_release() {
        $response = wp_remote_get("https://api.github.com/repos/{$this->user}/{$this->repo}/releases/latest", [
            'headers' => ['Accept' => 'application/vnd.github.v3+json'],
            'timeout' => 15,
            'user-agent' => 'Blue-Nama-Updater'
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}