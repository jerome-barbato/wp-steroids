<?php

use Dflydev\DotAccessData\Data;

/**
 * Class
 */
class WPS_Carbon_Fields{

    /* @var Data $config */
    private $config;

    private $shared_fields;

    public function addContent(){

        $this->addBlocks();
        $this->addOptionsPages();
    }

    /**
     * Adds options pages
     * @see https://carbonfields.net/docs/containers-theme-options/
     */
    public function addOptionsPages()
    {
        foreach ( $this->config->get('options_page', []) as $option_name => $option_args )
        {
            $carbon_container = \Carbon_Fields\Container::make( 'theme_options', __t( $option_args['title']??$option_name ) );

            $fields = [];

            foreach ($option_args['fields']??[] as $field_name=>$field_args)
                $fields[] = $this->createField($field_name, $field_args);

            $carbon_container->add_fields( $fields);

            $this->callMethods($carbon_container, $option_args);
        }

        foreach ( $this->config->get('post_type', []) as $post_type => $args )
        {
            if( $option_args = $args['has_options']??false ){

                $carbon_container = \Carbon_Fields\Container::make( 'theme_options', __t('Archive options') );
                $carbon_container->set_page_parent( 'edit.php?post_type='.$post_type );

                $fields = [];

                foreach ($option_args as $field_name=>$field_args)
                    $fields[] = $this->createField($field_name, $field_args);

                $carbon_container->add_fields( $fields);
            }
        }
    }

    /**
     * @param $field
     * @param $field_args
     * @return void
     */
    private function callMethods($field, $field_args){

        foreach ($field_args as $field_arg=>$value){

            $method = 'set_'.$field_arg;

            if( method_exists($field, 'set_'.$field_arg) )
                $field->$method($value);
        }
}

    /**
     * Adds Gutenberg blocks
     * @see https://www.advancedcustomfields.com/resources/acf_register_block_type/
     */
    public function addBlocks()
    {
        $render_template = $this->config->get('gutenberg.render_template', '');
        $preview_image = $this->config->get('gutenberg.preview_image', false);

        $upload_dir = wp_upload_dir();

        foreach ( $this->config->get('block', []) as $block_name => $block_args )
        {
            $block = [
                'name'             => $block_name,
                'title'            => __t($block_args['title']??$block_name),
                'description'      => __t($block_args['description']??''),
                'render_template'  => $block_args['render_template']??str_replace('{name}', $block_name, $render_template),
                'category'         => $block_args['category']??'layout',
                'icon'             => $block_args['icon']??'admin-comments',
                'mode'             => $block_args['mode']??'preview',
                'keywords'         => $block_args['keywords']??[],
                'post_types'       => $block_args['post_types']??[],
                'supports'         => $block_args['supports']??[],
                'front'            => $block_args['front']??true
            ];

            if( substr($block_args['icon']??'', -4) == '.svg' )
                $block['icon'] = file_get_contents(ABSPATH.'/'.$block_args['icon']);

            if( $_preview_image = $block_args['preview_image']??$preview_image ){

                if( is_string($_preview_image) )
                    $_preview_image = str_replace('{name}', $block_name, $preview_image);
                else
                    $_preview_image = $upload_dir['relative'].'/blocks/'.$block_name.'.jpg';

                $block['data'] = [
                    '_preview_image' => $_preview_image
                ];
            }

            $block['render_callback'] = function ($fields, $attributes, $inner_blocks) use($block){

                $block['fields'] = $fields;

                apply_filters('block_render_callback', $block, '', false);
            };

            $carbon_block = \Carbon_Fields\Block::make($block_name, $block['title']);

            $fields = [];

            foreach ($block_args['fields']??[] as $field_name=>$field_args)
                $fields[] = $this->createField($field_name, $field_args);

            $carbon_block->add_fields($fields);

            $this->callMethods($carbon_block, $block);
        }
    }

    /**
     * @param $field_name
     * @param $field_args
     * @return \Carbon_Fields\Field\Field
     */
    public function createField($field_name, $field_args)
    {
        if( ($field_args['type']??'') == 'clone' ){

            $component = $this->shared_fields[$field_args['id']??$field_name]??[];
            unset($field_args['type'], $field_args['id']);

            $field_args = array_merge($component, $field_args);
        }

        $type = $field_args['type']??'text';

        // not very nice but type is already used for the field type
        if( $type == 'file' && isset($field_args['allow']) )
            $field_args['type'] = $field_args['allow'];

        $field = \Carbon_Fields\Field::make($type, $field_name, __t($field_args['label']??null));

        if( $type == 'complex' && isset($field_args['fields']) ){

            $subfields = [];

            foreach ($field_args['fields'] as $subfield_name=>$subfield_args)
                $subfields[] = $this->createField($subfield_name, $subfield_args);

            $field->add_fields($subfields);

            if( !isset($field_args['header_template']) ){

                $collapsed = array_keys($field_args['fields'])[0];
                $field->set_header_template( '<% if ('.$collapsed.') { %><%- '.$collapsed.' %><% } %>' );
            }

            if( !isset($field_args['collapsed']) )
                $field->set_collapsed();
        }

        $this->callMethods($field, $field_args);

        if( !isset($field_args['visible_in_rest_api']) )
            $field->set_visible_in_rest_api();

        return $field;
    }

    /**
     * @param $allowed_block_types
     * @param $block_editor_context
     * @return array|bool
     */
    public function allowedBlockTypes($allowed_block_types, $block_editor_context) {

        if ( empty( $block_editor_context->post ) )
            return $allowed_block_types;

        $disabled_block_types = [];
        $post_type = get_post_type( $_GET['post']??0 );

        foreach ( $this->config->get('block', []) as $block_name => $block_args ) {

            if( !in_array($post_type, $block_args['post_types']??[]) )
                $disabled_block_types[] = 'carbon-fields/'.$block_name;
        }

        return array_values(array_diff($allowed_block_types, $disabled_block_types));
    }

    /**
     * Add quick link top bar archive button
     * @param $wp_admin_bar
     */
    public function editBarMenu($wp_admin_bar)
    {
        $object = get_queried_object();

        if( is_post_type_archive() && !is_admin() && current_user_can( $object->cap->edit_posts ) )
        {
            if( $this->config->get('post_type.'.$object->name.'.has_options', false) ){

                $args = [
                    'id'    => 'archive_options',
                    'title' => __t('Edit archive options'),
                    'href'  => get_admin_url( null, '/edit.php?post_type='.$object->name.'&page=crb_carbon_fields_container_archive_options.php' ),
                    'meta'   => ['class' => 'ab-item']
                ];

                $wp_admin_bar->add_node( $args );
            }
        }
    }

    /**
     * ACFPlugin constructor.
     */
    public function __construct()
    {
        if( !class_exists('\Carbon_Fields\Carbon_Fields') )
            return;

        global $_config;

        $this->config = $_config;
        $this->shared_fields = $this->config->get('carbon_fields.shared_fields', []);

        add_filter( 'allowed_block_types_all', [$this, 'allowedBlockTypes'], 99, 2 );

        add_action( 'admin_bar_menu', [$this, 'editBarMenu'], 80);

        add_action( 'after_setup_theme', ['\Carbon_Fields\Carbon_Fields', 'boot']);

        add_action( 'carbon_fields_register_fields', [$this, 'addContent']);
    }
}