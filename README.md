# WP Steroids

[![Latest Stable Version](http://poser.pugx.org/metabolism/wp-steroids/v)](https://packagist.org/packages/metabolism/wp-steroids)
[![Latest Unstable Version](http://poser.pugx.org/metabolism/wp-steroids/v/unstable)](https://packagist.org/packages/metabolism/wp-steroids)
[![License](http://poser.pugx.org/metabolism/wp-steroids/license)](https://packagist.org/packages/metabolism/wp-steroids)
[![Doc - Gitbook](https://img.shields.io/badge/Doc-Gitbook-346ddb?logo=gitbook&logoColor=fff)](https://metabolism.gitbook.io/symfony-wordpress-bundle/guides/wp-steroids-plugin)

Supercharge WordPress with:

* WordPress configuration using yml
* Permalink configuration for custom post types and taxonomies
* Maintenance mode
* Backup download in dev mode
* Build hook
* Disabled automatic updates
* Enhanced security
* Better GUID using RFC 4122 compliant UUID version 5
* Multisite images sync (for multisite as multilingual)
* SVG support
* Better performance
* WordPress bug fixes
* Radio type for taxonomies
* Multisite post deep copy (with multisite-language-switcher plugin)
* Custom datatable support with view and delete actions in admin
* Google Translate or DeepL integration
* Optimizations

The YML file allows you to configure:
* Image options
* Maintenance support
* Admin pages removal
* WYSIWYG MCE editor
* Feature support
* Multisite configuration
* ACF configuration
* Menu
* Custom post types
* Custom taxonomies
* Blocks
* Page, post, and taxonomy templates
* Page state
* Post format
* External table viewer
* Advanced roles

## Documentation

Documentation is available on [Gitbook](https://metabolism.gitbook.io/symfony-wordpress-bundle/guides/wp-steroids-plugin)

## Demo

Bedrock install using WP Steroids: https://github.com/metabolism/wp-steroids-demo

## YML Sample

```yaml
##########################################################################
##                                                                      ##
##                    WordPress configuration file                      ##
##                                                                      ##
##########################################################################

wordpress:

  #####################
  ##    Gutenberg    ##
  #####################

  gutenberg:
    replace_reset_styles: true
    remove_core_block: true
    remove_plugin_block: false
    remove_core_block_patterns: true
    disable_classic_theme_styles: true
    remove_block_library: true
    load_remote_block_patterns: false
    block_editor_style: '/build/bundle.css'
    block_editor_script: '/blocks.js'
    render_template: 'block/{name}/{name}.twig'
    preview_image: '/app/blocks/{name}.png'


  #########################
  ##    Image options    ##
  #########################

  image:
    compression: 95 #on the fly compression ratio
    resize: #resize image on upload to save server space
      max_width: 1920
      max_height: 2160

  ###########################
  ##    Admin interface    ##
  ###########################

  ## Enable maintenance mode support
  maintenance: true

  ## Hide pages from menu
  remove_menu_page:
    - edit-comments.php #Comments
  #  - index.php #Dashboard
  #  - jetpack #Jetpack
  #  - upload.php #Media
  #  - themes.php #Appearance
  #  - plugins.php #Plugins
  #  - users.php #Users
  #  - tools.php #Tools
  #  - options-general.php #Settings

  ## Hide page from submenu
  remove_submenu_page:
    - themes.php: site-editor.php?path=/patterns
  #  - themes.php: nav-menus.php

  ## Handle Customize experience on the server-side.
  ## https://developer.wordpress.org/reference/classes/wp_customize_manager/
  wp_customize:
    remove_section:
      - custom_css

  ## Customize WYSIWYG Editor TinyMCE Buttons
  ##https://www.tiny.cloud/docs/advanced/editor-control-identifiers/
  mce_buttons:
    - formatselect
    - bold
    - italic
    - underline
    - sup
    - strikethrough
    - superscript
    - subscript
    - bullist
    - numlist
    - blockquote
    - hr
    - table
    - alignleft
    - aligncenter
    - alignright
    - alignjustify
    - link
    - unlink
    - wp_more
    - spellchecker
    - wp_adv
    - dfw

  #editor_style: backoffice.css

  ##################
  ##    System    ##
  ##################

  ## Add blog support
  support:
    - page #enable page
    #- post #enable post
    - tag #enable tag taxonomy for post
    - category #enable category taxonomy for post

  ## Add post type support
  ## https://developer.wordpress.org/reference/functions/post_type_supports/
  post_type_support:
    page:
      - excerpt #add excerpt to page

  ## Add theme support
  ## https://developer.wordpress.org/reference/functions/add_theme_support/
  theme_support:
    - disable-layout-styles
    - thumbnail #add thumbnails to post
    #- post-formats:
    #  - video
    #  - gallery

  options_page: #add option pages https://www.advancedcustomfields.com/resources/options-page/
    global:
      fields:
        gtm:
          type: text
    translations:
      fields:
        translations:
          type: repeater
          layout: table
          sub_fields:
            key:
              type: text
            translation:
              type: text

  ## Use WordPress as a headless cms
  #headless:
  #  mapping: true

  ## Multisite configuration
  # multisite:
  #  shared_media: true
  #  clone_post: true

  ## Declare constant
  #define:
  #  disallow_file_edit: false

  #########################
  ##    Carbon fields    ##
  #########################
  carbon_fields:
    shared_fields:
      display:
        type: select
        options:
          default: Default
          theme: Theme
          theme-alt: Theme alt

  ###############
  ##    ACF    ##
  ###############

  acf:
    json_path: '/config/acf'
    settings:
      use_entity: true #add new optimised return format
      autoload: true #autoload options
    user_settings:
      gallery_height: 210
    block:
      api_version: 3
    input:
      lock_max_length: false
    toolbars: #customize wysiwyg toolbar
      Full:
        1:
          - formatselect
          - bold
          - italic
          - blockquote
          - superscript
          - subscript
          - bullist
          - numlist
          - table
          - alignjustify
          - pastetext
          - removeformat
          - link
          - wp_adv
          - fullscreen
        2:
          - undo
          - redo
          - hr
          - underline
          - strikethrough
          - forecolor
          - removeformat
          - charmap
          - outdent
          - indent
      Basic:
        1:
          - bold
          - italic
          - blockquote
          - superscript
          - subscript
          - forecolor
          - bullist
          - numlist
          - table
          - link
          - alignjustify
          - pastetext
          - removeformat
          - fullscreen


  ################
  ##    Menu    ##
  ################

  menu:
    depth: 1
    register:
      footer:
        title: Footer
        fields:
          sample:
            type: text
      header: Header


  ###################
  ##    Sidebar    ##
  ###################

  #sidebar: # https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
  #  footer:
  #    name : Footer
  #    description : 'Add widgets here to appear in your footer.'
  #    before_widget : '<section id="%1$s" class="widget %2$s">'
  #    after_widget : '</section>'
  #    before_title : '<h2 class="widget-title">'
  #    after_title : '</h2>'


  ##########################
  ##       New block      ##
  ##########################

  block:
    hero:
      title: Hero
      description: Page Hero
      supports:
        multiple: false
      icon: 'editor-alignleft'
      post_types:
        - page
        - guide
      fields:
        suptitle:
          type: text
        title:
          type: text
        content:
          type: wysiwyg
          toolbar: basic
          media_upload: false
        image:
          type: image
        cta:
          type: link

    video:
      title: Video
      #description: Video
      icon: 'format-video'
      post_types:
        - page
        - guide
      fields:
        video:
          type: url
          placeholder: 'https://www.youtube.com/watch?v=FcTLMTyD2DU'
        cover:
          type: image

    contact:
      title: Contact
      #description: Featured article
      icon: 'email'
      post_types:
        - page

    #hero-home:
    #  title: Homepage hero
    #  description: Display a slider
    #  supports:
    #    align_text: 'left'
    #    #multiple: false
    #  icon: 'editor-alignleft'
    #  post_types:
    #    - page
    #    - guide


  ##########################
  ##    New post types    ##
  ##########################

  post_type: # https://developer.wordpress.org/reference/functions/register_post_type/

    page:
      template:
        - - acf/hero
          - lock:
              move: true
              remove: true

    guide:
      menu_icon: book #https://developer.wordpress.org/resource/dashicons/
      has_archive: true
      #enable_for_blogs:
      #  - 1
      #disable_for_blogs:
      #  - 2#
      #has_options: true #add acf page option to post type menu
      #capability_type: post
      #publicly_queryable: false
      ## options for archive page
      #posts_per_page: 11
      #orderby: name #https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters or last_word
      #order: ASC
      show_in_rest: true
      template:
        - - acf/hero
          - lock:
              move: true
              remove: true
      supports:
        - title
        - excerpt
        - thumbnail
        - editor
        - revisions
        #- sticky
        #- author
        #- excerpt
        #- trackbacks
        #- custom-fields
        #- comments
        #- page-attributes
        #- post-formats
        #columns: #add new column to post listing, thumbnail|meta
        # - thumbnail
        #taxonomies:
        # - category
      #labels:
      #  name:           'Guides'
      #  singular_name:  'Guide'
      #  all_items:      'Tous les guides'
      #  edit_item:      'Editer le guide'
      #  view_item:      'Voir le guide'
      #  update_item:    'Mettre à jour le guide'
      #  add_new_item:   'Ajouter un guide'
      #  new_item_name:  'Nouveau guide'
      #  search_items:   'Rechercher un guide'
      #  popular_items:  'Guides populaires'
      #  not_found:      'Aucun guide trouvé'
      field_group:
        position: side
      fields:
        sample:
          type: text


  ##########################
  ##    New taxonomies    ##
  ##########################

  taxonomy: # https://developer.wordpress.org/reference/functions/register_taxonomy/
    item:
      ## options for archive page
      #posts_per_page: 11
      #orderby: name #https://developer.wordpress.org/reference/classes/wp_term_query/__construct/
      #order: ASC
      #radio: true #display terms selection as radio instead of checkbox
      #publicly_queryable: false
      show_in_rest: true
      #capability_type: true
      object_type:
        - guide
      #capabilities:
      #  manage_terms: 'do_not_allow'
      #  edit_terms: 'do_not_allow'
      #  delete_terms: 'do_not_allow'
      #  assign_terms: 'edit_posts'
      #labels:
      #  name:           'Catégories'
      #  singular_name:  'Catégorie'
      #  all_items:      'Toutes les catégories'
      #  edit_item:      'Editer la catégorie'
      #  view_item:      'Voir la catégorie'
      #  update_item:    'Mettre à jour la catégorie'
      #  add_new_item:   'Ajouter une catégorie'
      #  new_item_name:  'Nouvelle catégorie'
      #  search_items:   'Rechercher une catégorie'
      #  popular_items:  'Catégories populaires'
      #  not_found:      'Aucune catégorie trouvée'
      fields:
        sample:
          type: text

    ####################################
    ##    Templates/States/Formats    ##
    ####################################

    ## Add post and taxonomy templates
    #template:
    #  page:
    #    coming_soon: 'Coming Soon'
    #    not_found: '404'
    #  post:
    #    video: 'Video'
    #  taxonomy:
    #    item:
    #      edito: 'Edito'
    #      podcast: 'Podcast'
    #      reportage: 'Reportage'
    #      video: 'Video'

    ## Add page states like "homepage"
    #page_states:
    #archive_guide: 'Guide archive'

  #################################
  ##    Database table viewer    ##
  #################################

  #table:
  #  newsletter:
  #    page_title: Newsletter
  #    menu_title: Newsletter
  #    column_title: id
  #    export: false
  #    columns:
  #      name: Name
  #      email: Email
  #      company: Company
  #      type: Type
  #  contact:
  #    page_title: Contact
  #    menu_title: Contact
  #    column_title: id
  #    columns:
  #      name: Name
  #      email: Email
  #      company: Company
  #      request: Request

  #############################################
  ##               Add Roles                 ##
  ##                                         ##
  ##    To reload role please go to          ##
  ##    /wp-admin/index.php?reload_role=1    ##
  ##                                         ##
  #############################################
  #role:
  #  translator:
  #    display_name: Translator
  #    redirect_to: edit.php?post_type=guide
  #    #inherit: editor
  #    capabilities:
  #      read: true
  #      publish: false
  #      manage_items: true
  #      edit_items: true
  #
  #      edit_blocks: false
  #
  #      manage_categories: true
  #      edit_category: true
  #      delete_category: false
  #      assign_category: false
  #
  #      edit_others_pages: true
  #      read_page: true
  #      edit_pages: true
  #      publish_pages: false
  #      edit_published_pages: true
  #
  #      edit_others_posts: true
  #      read_post: true
  #      edit_posts: true
  #      publish_posts: false
  #      edit_published_posts: true
  #
  #      acf_edit_taxonomy: false
  #      acf_edit_layout: false
  #      acf_edit_relationship: false
  #      acf_edit_repeater: false
  #      acf_edit_true-false: false
  #      acf_edit_radio: false
  #      acf_edit_select: false
  #      acf_edit_image: false
  #      acf_edit_file: false
  #      acf_edit_link: false
  #      acf_edit_post-object: false


  ############################
  ##    Plugins specific    ##
  ############################

  #plugins:
  #  redirection:
  #    redirection_role: edit_others_posts

  #########################
  ##    Optimisations    ##
  #########################

  rewrite_rules:
    remove:
      - author
      - attachment
      - embed
      - trackback
      - comment
  #    - feed

  ####################
  ##    Security    ##
  ####################

  security:
    rest_api: false
    xmlrpc: false
    pings: false
    disable_update: true
    unfiltered_html: false

  ##################
  ##    Search    ##
  ##################

  #search: # change search settings
  # use_metafields: false
  # posts_per_page: 12
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
