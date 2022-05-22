<?php

/**
 * Class
 */
class WPS_Editor {

	private $config;


	/**
	 * Configure Tiny MCE first line buttons
	 * @param $mce_buttons
	 * @return array
	 */
	public function TinyMceButtons( $mce_buttons )
	{
		$mce_buttons = $this->config->get('mce_buttons', ['formatselect','bold','italic','underline','sup','strikethrough','bullist','numlist','blockquote','hr','alignleft',
			'aligncenter','alignright','alignjustify','link','unlink','wp_more','spellchecker','wp_adv','dfw']);

		return $mce_buttons;
	}


	/**
	 * Add quick link top bar archive button
	 * @param $wp_admin_bar
	 */
	public function editBarMenu($wp_admin_bar)
	{
		if( is_post_type_archive() && !is_admin() )
		{
			$object = get_queried_object();

			$args = [
				'id'    => 'edit',
				'title' => __('Edit '.$object->label),
				'href'  => get_admin_url( null, '/edit.php?post_type='.$object->name ),
				'meta'   => ['class' => 'ab-item']
			];

			$wp_admin_bar->add_node( $args );
		}

		$wp_admin_bar->remove_node('themes');
		$wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('wp-logo');

        if( in_array('edit-comments.php', $this->config->get('remove_menu_page', [])) )
            $wp_admin_bar->remove_node('comments');
    }

	/**
	 * Filter admin menu entries
	 */
	public function adminMenu()
	{
		foreach ( $this->config->get('remove_menu_page', []) as $menu )
		{
			remove_menu_page($menu);
		}

		remove_submenu_page('themes.php', 'themes.php' );

		foreach ( $this->config->get('remove_submenu_page', []) as $menu=>$submenu )
		{
			remove_submenu_page($menu, $submenu);
		}

		if( HEADLESS && !URL_MAPPING ){

			remove_submenu_page('options-general.php', 'options-reading.php');
			remove_submenu_page('options-general.php', 'options-permalink.php');
		}

		global $submenu;

		if ( isset( $submenu[ 'themes.php' ] ) )
		{
			foreach ( $submenu[ 'themes.php' ] as $index => $menu_item )
			{
				if ( in_array( 'customize', $menu_item ) )
					unset( $submenu[ 'themes.php' ][ $index ] );
			}

			if( empty($submenu[ 'themes.php' ]) )
				remove_menu_page('themes.php');
		}
	}

	/**
	 * Disable wordpress auto update and check
	 */
	protected function disableUpdate(){

		remove_action( 'admin_init', '_maybe_update_core' );
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		remove_action( 'update_option_WPLANG', 'wp_clean_update_cache' );
		remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
		remove_action( 'init', 'wp_schedule_update_checks' );

		add_filter( 'plugins_auto_update_enabled', '__return_false' );
	}


	/**
	 * Disable widgets
	 */
	function disableDashboardWidgets()
	{
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );   // Incoming Links
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );          // Plugins
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );        // Quick Press
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );            // WordPress blog
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );          // Other WordPress News
		remove_action( 'welcome_panel', 'wp_welcome_panel' );                // Remove WordPress Welcome Panel
	}


	/**
	 * add Custom css
	 */
	function addCustomAdminHeader()
	{
		echo '<link rel="stylesheet" href="'.WPS_PLUGIN_URL.'public/admin.css'.'"/>';
		echo '<link rel="stylesheet" href="'.WPS_PLUGIN_URL.'public/admin_bar.css'.'"/>';
		echo '<script type="text/javascript" src="'.WPS_PLUGIN_URL.'public/admin.js'.'"></script>';
	}


	/**
	 * add Custom css
	 */
	function addCustomLoginHeader()
	{
		echo '<link rel="stylesheet" href="'.WPS_PLUGIN_URL.'public/login.css"/>';
	}

	/**
	 * Update editor role
	 */
	public function adminInit()
	{
		$role_object = get_role( 'editor' );

		if( !$role_object->has_cap('edit_theme_options') )
			$role_object->add_cap( 'edit_theme_options' );

	}


	/**
	 * Editor constructor.
	 */
	public function __construct()
	{
        global $_config;

		$this->config = $_config;

		add_action( 'wp_before_admin_bar_render', function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('customize');
		} );

        if( is_admin() )
		{
			add_filter('mce_buttons', [$this, 'TinyMceButtons']);
			add_action('admin_menu', [$this, 'adminMenu']);
			add_action('wp_dashboard_setup', [$this, 'disableDashboardWidgets']);
			add_action('admin_head', [$this, 'addCustomAdminHeader']);
			add_action('admin_init', [$this, 'adminInit'] );

			add_filter('admin_body_class', function ( $classes ) {

				$data = get_userdata( get_current_user_id() );
				$caps = [];

				foreach($data->allcaps as $cap=>$value)
					$caps[] = $value ? $cap : 'no-'.$cap;

				return implode(' ', $caps).$classes.(HEADLESS?' headless':'').(URL_MAPPING?' url-mapping':'');
			});
		}

        add_action('init', function (){

            if( is_admin_bar_showing() )
                wp_enqueue_style('wp_steroid_adminbar', WPS_PLUGIN_URL.'public/admin_bar.css');
        });


        add_action( 'login_head', [$this, 'addCustomLoginHeader']);
        add_action( 'admin_bar_menu', [$this, 'editBarMenu'], 80);

		if( $this->config->get('disable_update', true) )
			$this->disableUpdate();
	}
}
