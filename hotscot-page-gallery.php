<?php
/*
Plugin Name: Hotscot Page Gallery
Description: Hotscot Page Gallery
Version: 1.0.2
Author: Hotscot

Copyright 2011 Hotscot  (email : support@hotscot.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

//add the GALLERY module to the pages and posts
add_action('admin_menu', 'hpg_gallery_box');
add_action("save_post","hpg_save_post");
add_action('admin_print_scripts', 'hpg_scripts');
add_action('admin_print_styles', 'hpg_styles');

function hpg_gallery_box(){
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'hpg_gallery', 'Page Images', 'hpg_gallery_form', 'page', 'advanced', 'high' );
		add_meta_box( 'hpg_gallery', 'Page Images', 'hpg_gallery_form', 'post', 'advanced', 'high' );
	}else{
		add_action('dbx_post_advanced', 'hpg_gallery_form' );
		add_action('dbx_page_advanced', 'hpg_gallery_form' );
	}
}

function hpg_save_post($post_id){
	if (!wp_is_post_revision($post_id)){
		add_post_meta($post_id, '_hpg_items', sanitize_textarea_field($_POST['hpg_gallery_items']), true) or update_post_meta($post_id, '_hpg_items', sanitize_textarea_field($_POST['hpg_gallery_items']));
	}
}

function hpg_scripts() {
    wp_register_script('hpg-script', plugins_url('scripts.js', __FILE__), array('jquery','media-upload','thickbox','jquery-ui-sortable','jquery-ui-draggable'));
    wp_enqueue_script('hpg-script');
}

function hpg_styles() {
    wp_register_style('hpg-css', plugins_url('style.css.js', __FILE__));
    wp_enqueue_style('thickbox');
    wp_enqueue_style('hpg-css');
}

function hpg_gallery_form(){
	global $post;
	?>
	<div id="hpg_gallery_pics"></div>
	<textarea style="display: none;" id="hpg_gallery_items" name="hpg_gallery_items"><?php echo get_post_meta($post->ID, '_hpg_items', true); ?></textarea>
	<div class="imageCover">
		<input type="button" class="hgp_img_btn" value="Add Image..." />
	</div>
	<?php
}

function hpg_paint_images(){
	global $post;
	$tempStr = '';
	if(isset($post->ID) && $post->ID){
		$items = get_post_meta($post->ID, '_hpg_items', true);
		if(strpos($items,"\n")!=0){
			$items = explode("\n",$items);
			foreach($items as $itemk => $itemv){
				$tempStr .= hpg_paint_image($itemv);	
			}
		}else{
			$tempStr = hpg_paint_image($items);	
		}
	}
	return $tempStr;
}

function hpg_paint_image($itm){
	$strtmp = '';
	if(strpos("**" . $itm,"||")!=0){
		$itm = str_ireplace("\r","",$itm);
		$itmFields = explode("||",$itm);
		if($itmFields[2]!=''){
			$strtmp = '<a href="' . $itmFields[2] . '" target="_blank"><img alt="' . $itmFields[0] . '" title="' . $itmFields[0] . '" src="' . $itmFields[1] . '" /></a>';
		}else{
			$strtmp = '<img alt="' . $itmFields[0] . '" title="' . $itmFields[0] . '" src="' . $itmFields[1] . '" />';
		}
	}
	return $strtmp;
}

function hpg_paint_images_obj(){
	global $post;
	$tempStr = array();
	if($post->ID){
		$items = get_post_meta($post->ID, '_hpg_items', true);
		if(strpos($items,"\n")!=0){
			$items = explode("\n",$items);
			foreach($items as $itemk => $itemv){
				$tempStr[] = hpg_paint_image_obj($itemv);	
			}
		}else{
			$tempStr[] = hpg_paint_image_obj($items);	
		}
	}
	return $tempStr;
}

function hpg_paint_image_obj($itm){
	$strtmp = '';
	if(strpos($itm,"||")!=0){
		$itm = str_ireplace("\r","",$itm);
		$itmFields = explode("||",$itm);
		
		$strtmp->Thumb = $itmFields[0];
		$strtmp->Large = $itmFields[2];
		$strtmp->Title = $itmFields[1];
	}
	return $strtmp;
}
?>