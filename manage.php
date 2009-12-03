<?php
global $table_prefix, $wpdb;
if($_POST)
{
	$wpdb->query("delete from {$table_prefix}fpCats");
	foreach($_POST['cats'] as $cat)
	{
		$wpdb->query("insert {$table_prefix}fpCats values($cat)");
	}
	$tags=$_POST['tags'];
	$wpdb->query("delete from {$table_prefix}fpCats_tags");
	$wpdb->query("insert {$table_prefix}fpCats_tags values('$tags')");
}
?>
<H1>Front Page Categories</H1>
<form method="post">
<table><D><TD width="250px"><b>Category</b></TD><TD><b>Active on front page</b></TD></TR>
 <?php 
  $sel=$wpdb->get_col($wpdb->prepare("SELECT * from {$table_prefix}fpCats"));
  $tags=$wpdb->get_col($wpdb->prepare("SELECT * from {$table_prefix}fpCats_tags"));
  $categories=  get_categories('hide_empty=0'); 
  foreach ($categories as $cat) {
	if(in_array($cat->cat_ID,$sel))
		$selected='checked';
	else
		$selected='';
  	$option = '<TR><TD>';
	$option .= $cat->cat_name.'</TD><TD><input type=checkbox name="cats[]" value="'.$cat->cat_ID.'" '.$selected.'></TD>';
	$option .= '</TR>';
	echo $option;
  }
 ?>
</table>
<H3>OR Having one of the tags(each tag in its line)</H3><br>
<textarea name="tags" cols="60" rows="9">
<?=$tags[0]?>
</textarea>
<br>
<input type="submit" value="Save">
</form>
<br /> <br /> <br />
<div style="text-align:center; font-weight:bold#">This plugin was brought to you by Digitalquill, <a href="http://www.digitalquill.co.uk/wordpressplugins/">please click here see our other plugins</a></div>
