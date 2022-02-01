<?php


/**
 * Class MSLSProvider
 *
 * @package 
 */
class WPS_Multisite_Language_Switcher {

	public function setupClone()
	{
		// add blog and post origin
		add_filter( 'msls_admin_icon_get_edit_new', function($path){

			global $current_blog;
			$current_id = $_GET['post'] ?? get_the_ID();

			if( $current_id ) {
				$path = add_query_arg([
					'clone' => 'true',
					'blog_id' => $current_blog->blog_id,
					'post_id' => $current_id
				], $path);
			}

			return $path;
		});


		add_action( 'load-post-new.php', function(){

			global $wpdb;

			if( isset($_GET['blog_id'], $_GET['post_id'], $_GET['clone']))
			{
				$main_site_id = get_main_network_id();

				// switch to origin blog
				switch_to_blog($_GET['blog_id']);

				// get the original post
				$post = get_post($_GET['post_id'], ARRAY_A);

				if( !is_wp_error($post) )
				{
					//remove tags
					unset($post['tags_input']);

					// get the original meta
					$meta = get_post_meta($_GET['post_id']);

					// get the original language, fallback to en
					$language = get_option('WPLANG');
					$language = empty($language)?'en':$language;

					// empty id field, to tell wordpress that this will be a new post
					$post['ID'] = '';

					// return to target blog
					restore_current_blog();

					// insert the post as draft
					$post['post_status'] = 'draft';
					$inserted_post_id = wp_insert_post($post);

					// delete post_name
					$wpdb->query("UPDATE $wpdb->posts SET `post_name`='' WHERE `ID`=".$inserted_post_id);

					// register original post
					add_option('msls_'.$inserted_post_id, [$language => $_GET['post_id']], '', 'no');

					$current_site_id = get_current_blog_id();

					// add and filter meta
					foreach($meta as $key=>$value){

						$value = maybe_unserialize($value[0]);

						if(empty($value))
							continue;

						if($key === '_thumbnail_id' && is_string($value)) {

							if( $current_site_id == $main_site_id )
							{
								switch_to_blog($_GET['blog_id']);
								$original_id = get_post_meta($value, '_wp_original_attachment_id', true);
								restore_current_blog();

								update_post_meta($inserted_post_id, $key, $original_id);
							}
							else
							{
								$attachments = get_posts(['numberposts'=>1, 'post_type'=>'attachment', 'meta_value'=>$value, 'meta_key'=>'_wp_original_attachment_id', 'fields'=>'ids']);

								if( count($attachments) )
									update_post_meta($inserted_post_id, $key, $attachments[0]);
							}
						}
						else{

							if( function_exists('get_field_object') && is_string($value) )
							{
								$field = get_field_object($value);

								if( isset($field['type']) && in_array($field['type'], ['image', 'file']) )
								{
									$meta_key = substr($key, 1);
									$meta_value = $meta[$meta_key][0];

									if( !empty($meta_value)){

										if( $current_site_id == $main_site_id )
										{
											switch_to_blog($_GET['blog_id']);
											$original_id = get_post_meta($meta_value, '_wp_original_attachment_id', true);

											restore_current_blog();

											if( $original_id )
											{
												$meta[$meta_key][0] = $original_id;
												update_post_meta($inserted_post_id, substr($key, 1), $original_id);

												continue;
											}
										}
										else
										{
											$attachments = get_posts(['numberposts'=>1, 'post_type'=>'attachment', 'meta_value'=>$meta_value, 'meta_key'=>'_wp_original_attachment_id', 'fields'=>'ids']);
											if( count($attachments) )
											{
												$meta[$meta_key][0] = $attachments[0];
												update_post_meta($inserted_post_id, substr($key, 1), $attachments[0]);

												continue;
											}
										}
									}
								}
							}

							update_post_meta($inserted_post_id, $key, $value);
						}
					}

					// get the target language, fallback to us
					$language = get_option('WPLANG');
					$language = empty($language)?'us':$language;

					// switch to origin blog
					switch_to_blog($_GET['blog_id']);

					// register new post
					add_option('msls_'.$_GET['post_id'], [$language => $inserted_post_id], '', 'no');

					// return to original blog
					restore_current_blog();

					// return to edit page
					wp_redirect( get_admin_url(get_current_blog_id(), 'post.php?post='.$inserted_post_id.'&action=edit'));
					exit;
				}
			}
		});
	}


	/**
	 * MSLSProvider constructor.
	 */
	public function __construct(){

		if( is_multisite() ){

			if( is_admin() ) {

                global $_config;

				if( $_config->get('multisite.clone_post', false) )
					$this->setupClone();
			}

            //todo: find why $url is buggy
           add_filter( 'mlsl_output_get_alternate_links', function ($url, $blog){

               if( strpos($url, 'http') === false )
                   return null;

               return $url;
            }, 10, 2);
        }
	}
}
