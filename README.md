# WP Steroids

Supercharge Wordpress with :

* Wordpress YML configuration
* Permalink configuration for custom post type and taxonomy
* Maintenance mode
* Backup download in dev mode
* Build hook
* Disabled automatic update
* Enhanced Security
* Better guid using RFC 4122 compliant UUID version 5
* Multisite images sync ( for multisite as multilangue )
* SVG Support
* Better Performance
* Wordpress Bugfix
* CSS Fix
* Relative urls
* Multisite post deep copy ( with multisite-language-switcher plugin )
* Custom datatable support with view and delete actions in admin

YML file allows to configure : 
* Image options
* Maintenance support
* Admin pages removal
* WYSIWYG MCE Editor
* Feature Support
* Multi-site configuration
* ACF configuration
* Menu
* Custom Post type
* Custom Taxonomy
* Page, post, taxonomy templates
* Page states
* Post format
* External table viewer
* Roles
* Optimisations

## YML configuration file sample

```yaml
###############################################
##                                           ##
##    Wordpress Bundle configuration file    ##
##                                           ##
###############################################

version: 2.0

#########################
##    Image options    ##
#########################

image:
  compression: 90 #on the fly compression ration
  show_meta: false #add metadata to Image entity
  #return_format: url|id|object
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
  themes.php: customize.php?return=%2Fedition%2Fwp-admin%2F

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

## Add theme support
support:
  - thumbnail #add thumbnails to page
  - page #enable page
  - post #enable post
  - tag #enable tag taxonomy for post
  - category #enable category taxonomy for post
 #- post-formats: # https://wordpress.org/support/article/post-formats/#supported-formats
  #  - video
  #  - gallery

## Multisite configuration
# multisite:
#  shared_media: true
#  clone_post: true


###############
##    ACF    ##
###############

acf:
  settings:
    use_entity: true #add new optimised return format
    autoload: true #autoload options
  options_page: #add option pages https://www.advancedcustomfields.com/resources/options-page/
    - 'Personnalisation'
  toolbars: #customize wysiwyg toolbar
    Full:
      1:
        - bold
        - italic
        - underline
        - blockquote
        - strikethrough
        - superscript
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
      2:
        - strikethrough
        - hr
        - forecolor
        - pastetext
        - removeformat
        - charmap
        - outdent
        - indent
        - undo
        - redo
        - wp_help
    Basic:
      1:
        - bold
        - italic
        - underline
        - blockquote
        - strikethrough
        - superscript
        - bullist
        - numlist
        - table
        - alignleft
        - aligncenter
        - alignright
        - undo
        - redo
        - link
        - fullscreen


################
##    Menu    ##
################

menu:
  depth: 1
  register:
    footer: Footer
    header: Header


###################
##    Sidebar    ##
###################

sidebar: # https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
  footer:
    name : Footer
    description : 'Add widgets here to appear in your footer.'
    before_widget : '<section id="%1$s" class="widget %2$s">'
    after_widget : '</section>'
    before_title : '<h2 class="widget-title">'
    after_title : '</h2>'

##########################
##    New post types    ##
##########################

post_type: # https://developer.wordpress.org/reference/functions/register_post_type/
  guide:
    menu_icon: book #https://developer.wordpress.org/resource/dashicons/
    has_archive: true
    #enable_for_blogs:
    #  - 1
    #disable_for_blogs:
    #  - 2#
    #has_options: true #add acf page option to post type menu
    capability_type: post
    #publicly_queryable: false
    ## options for archive page
    #posts_per_page: 11
    #orderby: name #https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
    #order: ASC
    supports:
      - title
      - excerpt
      - thumbnail
      #- editor
      #- author
      #- excerpt
      #- trackbacks
      #- custom-fields
      #- comments
      #- revisions
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
    object_type:
      - guide
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

## Add page states, like "homepage"
#page_states:
  #archive_guide: 'Archive des guides'

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
#    display_name: Traducteur
#    redirect_to: edit.php?post_type=guide
#    #inherit: editor
#    capabilities:
#      read: true
#      publish: false
#
#      edit_others_guides: true
#      read_guide: true
#      edit_guides: true
#      publish_guides: false
#      edit_published_guides: true
#
#      acf_edit_layout: false
#      acf_edit_repeater: false
#      acf_edit_true-false: false
#      acf_edit_radio: false
#      acf_edit_image: false
#      acf_edit_file: false
#      acf_edit_post-object: false
#
#      manage_items: true
#      edit_items: true


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

################
##    Misc    ##
################

gutenberg: false

#search: # change search settings
# posts_per_page: 12
```
## Licence

GNU AFFERO GPL
    
    
## Maintainers

This project is made by Metabolism ( http://metabolism.fr )

Current maintainers:
 * Jérôme Barbato - jerome@metabolism.fr
