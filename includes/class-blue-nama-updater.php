<?php
if (!defined('ABSPATH')) exit;

class Blue_Nama_Updater {

    private $user;
    private $repo;
    private $current_version;
    private $slug;
    private $plugin_file;
    private $release_zip;

    public function __construct( $user, $repo, $current_version ) {
        $this->user = $user;
        $this->repo = $repo;
        $this->current_version = $current_version;
        $this->plugin_file = plugin_basename( BLUE_NAMA_FILE );
        $this->slug = dirname( $this->plugin_file );

        $this->release_zip = "https://github.com/{$this->user}/{$this->repo}/releases/latest/download/blue-nama.zip";

        add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ] );
        add_filter( 'plugins_api', [ $this, 'plugin_info' ], 10, 3 );
    }

    public function check_update( $transient ) {
        if ( empty( $transient->checked ) ) return $transient;

        $remote = $this->get_remote_release();
        if ( ! $remote || empty( $remote['tag_name'] ) ) return $transient;

        $remote_version = ltrim( $remote['tag_name'], 'v' );

        if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
            $obj = new stdClass();
            $obj->slug        = $this->slug;
            $obj->new_version = $remote_version;
            $obj->url         = "https://github.com/{$this->user}/{$this->repo}";
            $obj->package     = $this->release_zip;

            $transient->response[ $this->plugin_file ] = $obj;
        }

        return $transient;
    }

    public function plugin_info( $false, $action, $args ) {
        if ( $action !== 'plugin_information' ) return $false;
        if ( ! isset( $args->slug ) || $args->slug !== $this->slug ) return $false;

        $remote = $this->get_remote_release();
        if ( ! $remote ) return $false;

        $remote_version = ltrim( $remote['tag_name'], 'v' );

        return (object) [
            'name'           => 'آبی‌نما | Blue Nama',
            'slug'           => $this->slug,
            'version'        => $remote_version,
            'author'         => '<a href="https://shayandvlpr.ir">shayandvlpr</a>',
            'requires'       => '5.6',
            'tested'         => '6.7',
            'last_updated'   => $remote['published_at'] ?? current_time('mysql'),
            'download_link'  => $this->release_zip,
            'sections'       => [
                'description' => 'نمایش داینامیک محتوای هر صفحه با یک شورت‌کد ساده — تولید شده توسط آبی‌ترین ماه',
                'changelog'   => $remote['body'] ?? 'به‌روزرسانی جدید'
            ]
        ];
    }

    private function get_remote_release() {
        $response = wp_remote_get( "https://api.github.com/repos/{$this->user}/{$this->repo}/releases/latest", [
            'timeout' => 15,
            'headers' => [ 'User-Agent' => 'Blue-Nama-Updater' ]
        ]);

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return false;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true ) ?: false;
    }
}