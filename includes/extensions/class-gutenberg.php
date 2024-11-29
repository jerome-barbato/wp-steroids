<?php

/**
 * Class
 */
class WPS_Gutenberg
{
	/**
	 * @return void
	 */
	public function removeBlockLibrary(){

        wp_dequeue_style('core-block-supports');
	    wp_dequeue_style( 'wp-block-library' );
	    wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'classic-theme-styles' );
	    wp_dequeue_style( 'wc-block-style' ); // REMOVE WOOCOMMERCE BLOCK CSS
	    wp_dequeue_style( 'global-styles' ); // REMOVE THEME.JSON
    }

	/**
	 * @param $allowed_block_types
	 * @param $editor_context
	 * @return int[]|string[]
	 */
	function removeCoreBlock($allowed_block_types, $editor_context ) {

        if( !is_array($allowed_block_types) )
            $allowed_block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach($allowed_block_types as $block=>$data){
			if( substr($block, 0, '5') == 'core/' )
				unset($allowed_block_types[$block]);
        }

		return array_keys($allowed_block_types);
	}

	/**
	 * @return array
	 */
	function blockEditorSettings($editor_settings) {

		global $_config, $wp_version;

        if( version_compare( $wp_version, '4.7', '<' ) )
            return $editor_settings;

        if( is_multisite() )
            $base_url = network_home_url();
        else
            $base_url = get_home_url();

        $theme_styles_settings = array(
            'css'            => file_get_contents(WPS_PATH.'/public/reset-editor-styles.css'),
            '__unstableType' => 'theme',
            'isGlobalStyles' => true
        );

        $editor_settings['styles'][] = $theme_styles_settings;

        if( $block_editor_style = $_config->get('gutenberg.block_editor_style', false) ){

            $cssUrl = apply_filters('block_editor_settings_theme_css', $base_url.$block_editor_style);

            if( !empty($css = @file_get_contents($cssUrl)) ){

                $theme_styles_settings = array(
                    'css'            => $css,
                    '__unstableType' => 'theme',
                    'isGlobalStyles' => true
                );

                $editor_settings['styles'][] = $theme_styles_settings;
            }
        }

        return $editor_settings;
    }

    /**
	 * @return void
	 */
	function addBlockEditorAssets() {

        global $_config, $wp_version;

        if( is_multisite() )
            $base_url = network_home_url();
        else
            $base_url = get_home_url();

        if( $block_editor_script = $_config->get('gutenberg.block_editor_script', false) )
            wp_enqueue_script('block_editor_script',$base_url.$block_editor_script);

        if ( version_compare( $wp_version, '4.7', '<' ) && $block_editor_style = $_config->get('gutenberg.block_editor_style', false) )
            wp_enqueue_style('block_editor_style',$base_url.$block_editor_style);
    }

    /**
     * @return void
     */
    public function registerTemplate(){

        global $_config;

        if( $template = $_config->get('post_type.page.template', false) ){

            $post_type_object = get_post_type_object( 'page' );
            $post_type_object->template = $template;
        }

        if( $template = $_config->get('post_type.post.template', false) ){

            $post_type_object = get_post_type_object( 'post' );
            $post_type_object->template = $template;
        }
    }

    public function __construct()
    {
	    global $_config;

        if ( $_config->get('gutenberg.disable_classic_theme_styles', true) )
            remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );

        if ( !$_config->get('gutenberg.load_remote_block_patterns', false) )
            add_action( 'should_load_remote_block_patterns', '__return_false' );

		if( is_admin() ){

			if ( $_config->get('gutenberg.remove_core_block', false) )
				add_filter( 'allowed_block_types_all', [$this, 'removeCoreBlock'], 25, 2 );

            add_action( 'enqueue_block_assets', [$this, 'addBlockEditorAssets'] );

            add_filter( 'block_editor_settings_all', [$this, 'blockEditorSettings'] );

            add_action( 'init', [$this, 'registerTemplate']);
        }
		else{

			if ( $_config->get('gutenberg.remove_block_library', true) ){

				add_action( 'wp_enqueue_scripts', [$this, 'removeBlockLibrary'], 100 );
                add_action( 'wp_footer', [$this, 'removeBlockLibrary']);
            }
		}
    }
}
