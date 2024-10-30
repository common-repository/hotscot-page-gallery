var formfield1='';
var formfield2='';
var formfield3='';
jQuery(window).load(function(){
	hpgPopulateImages();
	
	if(jQuery('.hgp_img_btn').size()>0){
		jQuery('.hgp_img_btn').each(function(){
			jQuery(this).click(function(){
				formfield1 = jQuery(this).parent('.imageCover').children('.title').attr('name');
                formfield2 = jQuery(this).parent('.imageCover').children('.thumb').attr('name');
                formfield3 = jQuery(this).parent('.imageCover').children('.large').attr('name');

				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				
				var oldFuncHDM = window.send_to_editor;
				window.send_to_editor = function(html) {
					thumb = jQuery('img','<div>' + html + '</div>').attr('src');
                    large = jQuery('a','<div>' + html + '</div>').attr('href');
                    title = jQuery('img','<div>' + html + '</div>').attr('title');
                    if(!title) title = jQuery('img','<div>' + html + '</div>').attr('alt');
					
					if(!large) large = '';
					if(!title) title = '';
                    
					var item = title + '||' + thumb + '||' + large;	
					var val = jQuery('#hpg_gallery_items').val();
					if(val==''){
						jQuery('#hpg_gallery_items').val(item);
					}else{
						jQuery('#hpg_gallery_items').val(jQuery('#hpg_gallery_items').val() + "\n" + item);
					}
					hpgPopulateImages();
                    window.send_to_editor = oldFuncHDM;
					tb_remove();
				}
				return false;
			});
		});
	}
});

function hpgPopulateImages(){
	var mainText = jQuery('#hpg_gallery_items').val();
	jQuery('#hpg_gallery_pics').html('');
	jQuery('#hpg_gallery_pics').css('height','auto');
	if(mainText&&mainText!=''){
		if(/\n/.test(mainText)){
			//more than one item
			var images = mainText.split("\n");
			for(iimg=0;iimg<images.length;iimg++){
				hpgPopulateImage(images[iimg],iimg);
			}
		}else{
			hpgPopulateImage(mainText,0);
		}
	}
	jQuery('#hpg_gallery_pics img').load(function(){
		jQuery('#hpg_gallery_pics').css('height',jQuery('#hpg_gallery_pics').height() + 'px');
		jQuery('#hpg_gallery_pics').sortable({
			stop: function(event,ui){
				hpg_resort();
			}
		});
		jQuery('#hpg_gallery_pics').disableSelection();
	});

}

function hpgPopulateImage(image,location){
	if(/||/.test(image)){
		var fields = image.split("||");
		jQuery('#hpg_gallery_pics').append('<div id="hpg_image_' + location + '" class="hpg_image_wrapper"><a href="JavaScript:hpg_delete(' + location + ');" title="Remove Image">Remove Image</a><img src="' + fields[1] + '" alt="' + fields[0] + '" title="' + fields[0] + '" /></div>');
	}
}

function hpg_resort(){
	//get all images from the view
	var images = new Array();
	jQuery('#hpg_gallery_pics .hpg_image_wrapper').each(function(){
		images.push(jQuery(this).attr('id').replace("hpg_image_",""));
	});
	
	var mainText = jQuery('#hpg_gallery_items').val();
	if(mainText&&mainText!=''){
		if(/\n/.test(mainText)){
			var images2 = mainText.split("\n");
			mainText = '';
			for(iimg=0;iimg<images.length;iimg++){
				if(mainText==''){
					mainText = images2[images[iimg]];
				}else{
					mainText = mainText + "\n" + images2[images[iimg]];
				}
			}
		}
	}
	jQuery('#hpg_gallery_items').val(mainText);
	hpgPopulateImages();
}

function hpg_delete(item){
	var mainText = jQuery('#hpg_gallery_items').val();
	if(mainText&&mainText!=''){
		if(/\n/.test(mainText)){
			//more than one item
			var images = mainText.split("\n");
			var newText = '';
			images[item] = '';
			for(iimg=0;iimg<images.length;iimg++){
				if(images[iimg]!=''){
					if(newText==''){
						newText = images[iimg];
					}else{
						newText = newText + "\n" + images[iimg];
					}
				}
			}
			jQuery('#hpg_gallery_items').val(newText);
			hpgPopulateImages();
		}else{
			jQuery('#hpg_gallery_items').val('');
			hpgPopulateImages();
		}
	}
}