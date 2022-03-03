<?php

/**
 * Class
 *
 * @package 
 */
class WPS_Google_Sitemap_Generator
{
	/**
	 * Construct
	 */
	public function __construct()
	{
		add_filter('wp-bundle/make_post_link_relative', function($make){

			global $wp_query;
			return $make && empty($wp_query->query_vars["xml_sitemap"]);
		});
	}
}