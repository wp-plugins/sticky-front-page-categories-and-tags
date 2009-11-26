<?php
/*
Plugin Name: Sticky Frontpage Categories and Tags
Plugin URI: http://www.digitalquill.co.uk/wordpressplugins/
Version: 1.0
Author: Digitalquill
Author URI: http://www.digitalquill.co.uk/wordpressplugins/
Description: Allows you to choose what posts appear on the front page of your blog by category or tags. 

*/

//add_action ( 'pre_get_posts', 'changeQuerytoLimitCats' );
add_filter('posts_where', 'changeQuerytoLimitCats');
add_filter('posts_join', 'changeJointoLimitCats');
add_action('admin_menu', 'fpCatsSettingsmenu');
register_activation_hook(__FILE__,'plug_install_fpCats');

function fpCatsSettingsmenu() {
    add_options_page('Frontpage Categories Settings', 'Frontpage Categories', 8, __FILE__,'fpCats_options');
}
function plug_install_fpCats()
{
    global $table_prefix, $wpdb;
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    $qry = "CREATE TABLE {$table_prefix}fpCats (
        id int
        );";
    dbDelta($qry);
    $qry = "CREATE TABLE {$table_prefix}fpCats_tags (
        tags text
        );";
    dbDelta($qry);
}
function fpCats_options() {
  echo '<div class="wrap">';
  include 'manage.php';
  echo '</div>';
}

function changeQuerytoLimitCats($query)
{
	if(is_home())
	{
		global $table_prefix,$wpdb;
		$query2=" AND( {$table_prefix}term_taxonomy.taxonomy = 'category' AND {$table_prefix}term_taxonomy.term_id IN (SELECT id from {$table_prefix}fpCats) ";
		$tags=$wpdb->get_col($wpdb->prepare("SELECT * from {$table_prefix}fpCats_tags"));
		$tags=explode("\n",$tags[0]);
		foreach($tags as $tag)
		{
			$tag=strtoupper(trim($tag));
			$query2.=" or upper({$table_prefix}terms.name)='$tag'";
		}
		$query.=$query2.")";
	}
	return $query;
}
function changeJointoLimitCats($join)
{
	if(is_home())
	{
		global $table_prefix;
		if(!$join)
			$join="INNER JOIN wp_term_relationships ON ({$table_prefix}posts.ID = {$table_prefix}term_relationships.object_id) INNER JOIN {$table_prefix}term_taxonomy ON ({$table_prefix}term_relationships.term_taxonomy_id = {$table_prefix}term_taxonomy.term_taxonomy_id)
INNER JOIN {$table_prefix}terms ON ({$table_prefix}terms.term_id = {$table_prefix}term_taxonomy.term_id)
";
	}
	return $join;
}
?>