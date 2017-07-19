<?php
/* 
Custom Post Type Images
Attach images to custom post type archive pages and singles.
Copyright 2011  Yonatan Reinberg (email : yoni [a t ] s o cia l-ink DOT net) - http://social-ink.net

Administration options page - Wordpress backend
*/

global $cptplugin_url_images;


/* **************************************************
	CHECK REQUEST PARAMS
**************************************************	*/	
	
//@upload image if attached
if(!empty($_POST['attach_image'])) {
	global $pTypeUploadDirectory;
	global $pTypeDownloadDirectory;
	
	
	if(!file_exists($pTypeUploadDirectory)) 
		$successful_upload = mkdir($pTypeUploadDirectory);
	else
		$successful_upload = true;

		if($successful_upload) {
			$target_path = $pTypeUploadDirectory . basename($_FILES['ptImages_CPTimage']['name']); 
		
			if(move_uploaded_file($_FILES['ptImages_CPTimage']['tmp_name'], $target_path)) {
				update_option($_POST['attach_image_db'], $_FILES['ptImages_CPTimage']['name']);
				echo '<div class="updated"><p><strong>The file was attached successfully!</strong></p></div>';
			} else{
				echo '<div class="updated"><p><strong>Sorry, there was an error uploading the file, please try again!</strong></p></div>';
			}	
		} else {
			echo '<div class="updated"><p><strong>Sorry, there was an error creating the upload directory, please check file permissions on your wp-uploads folder and try again!</strong></p></div>';
		}
	}

//@delete image if attached
if ('GET' === $_SERVER['REQUEST_METHOD']) {	//check to see if we need to remove a line
	if(!empty($_GET['deleteImage'])) {
		deleteFile($_GET['deleteImage']);
	}
}	
	
	
/* **************************************************
	DELETE IMAGE & RECORD IF DESIRED	
**************************************************	*/	
	
function deleteFile($removeOption) {
	global $pTypeUploadDirectory;
	$errormsg = false;
	$imagefile_name =  get_option($removeOption);
	$file_to_delete = $pTypeUploadDirectory.$imagefile_name;

	if(file_exists($file_to_delete)&&is_file($file_to_delete)) {
		$file_to_delete = unlink($file_to_delete);
			if($file_to_delete) {
				delete_option($removeOption);
				echo '<div class="updated"><p><strong>You have deleted the image ' . $imagefile_name . ' from the site.  You may still see the cached image below; please refresh the page by <a href="?page=ptImages">clicking here</a></strong>.</p></div>';
				}
			else
				$errormsg = true;
	} else {	//file to delete does not exist
		$errormsg = true;
	}
	
	if($errormsg)
		echo "<div class=\"updated\"><p><strong>Sorry, we couldn't delete the file. Perhaps it was already deleted?  Please try again or contact system administrator.</strong></p></div>";
}	
	?>
	

	<div class="wrap">
			
		<div  id="ptImages" class="metabox-holder">
			<div class="icon32" id="icon-options-general"><br></div>	
			<h2>Custom Post Type Images</h2>
				
				<div id="post-body">
					<div id="post-body-content" class="has-sidebar-content">
					
						<div class="section introsection">
							<h3>Add images to custom post types. By yonatan reinberg and the folks at <a href="http://social-ink.net">Social Ink</a></h3>	
							<p>To use, select an image for the custom post type and attach.  You can then use the image in various ways:</p>	
							<p><b>As a shortcode</b>.  Put [cptImage] wherever you want the image to appear. Not that in this usage (without a parameter) it will only appear when you are on a single item of that custom post type OR in the archive page itself.  To call any attached image, use [cptImage cpt="Custom Post Type Name"].</p>							
							<p><b>In PHP in your template</b>.  To retrieve the image url to a variable, use <code>get_cptimage_image($optionalname, $echo)</code> (you can use <code>get_cptimage_image("Custom Post Type Name")</code> to retrieve a specific post type image, or <code>get_cptimage_image("Custom Post Type Name", true)</code> to echo the url rather than retrieving it.  To merely display the image, call <code>cptimage_image("Custom Post type Name")</code> or simply <code>cptimage_image()</code>.  Not that if you do not pass a post type name it will only appear when you are on a single item of that custom post type OR in the archive page itself. </p>
						</div>

						<div class="section">
							<h3>all registered post types</h3>										
							<table class="widefat imagetable">
								<thead>
									<tr>
										<th>Post type name</th>
										<th>Post type slug</th>
										<th>Image attached?</th>
										<th>Delete File</th>
									</tr>
								</thead>
								<tbody>
								
							<?	
							global $pTypeDownloadDirectory;							
							$allCPTs = get_post_types();
								$skipTypes = array('page','attachment','revision','nav_menu_item');
								
								foreach($allCPTs as $CPT) {
									if(in_array($CPT,$skipTypes))
										continue;

									$metainfo = get_post_type_object($CPT);

									$mylabel = $metainfo->label;
									$myimageDB = "ptImages_$CPT";
									$myimage = get_option($myimageDB);
									$deletelink = "<a onclick=\"return confirm(\'Are you sure you want to permanently delete this FILE?\');\" href=\"?page=ptImages&deleteImage=$myimageDB\"><img src=\"$cptplugin_url_images/adminX16x16.png\" alt=\"Delete - permanent!\" /></a>";
									
									if(!empty($myimage))
										$myimage = "<img src=\"$pTypeDownloadDirectory$myimage\" alt=\"$mylabel image\" />";
										
									$imageUploadLink = "<br /><a href=\"\" class=\"uploadimage\">Upload a new image</a>";

									echo "<tr><td class=\"labelSection\">$mylabel</td><td class=\"labelSection\">$CPT</td><td class=\"imageUploadSection\">$myimage $imageUploadLink"; ?>
										<div class="ptImage_uploadBox imageUploadSection<? if(empty($myimage)) echo ' expandSection'; ?>">
											<form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
											<input type="file" value=""  class="regular-text code" value="" id="ptImages_CPTimage" name="ptImages_CPTimage">
											<input type="hidden" name="attach_image" value="attach_image" />
											<input type="hidden" name="attach_image_db" value="<? echo $myimageDB ?>" />
											<p class="submit">
												<input type="submit" class="button-primary" name="submit" value="attach image" />
											</p>
											</form>
										</div>											
									
									<? echo "</td><td class=\"labelSection\">$deletelink</td></tr>";
								}
								?>		
								</tbody>
								<tfoot>
									<tr>
										<th>Post type name</th>
										<th>Post type slug</th>
										<th>Image attached?</th>
										<th>Delete File</th>									
									</tr>
								</tfoot>									
							</table>
						</div>
					<div class="section">		
						<div style="float:right;margin-left:25px;margin-bottom:30px;">
							<a href="http://www.social-ink.net"><img src="<?php echo $cptplugin_url_images ?>/logoSocialInk.png" alt="Social Ink" /></a>
						</div>			
						<p>Thanks for downloading a Social Ink plugin - we hope you love it helps your site as much as we enjoyed making it.</p>

						
						<p>If you have any support questions or to buy our other famous plugins, please head over to <a href="http://shop.social-ink.net">shop.social-ink.net</a> to see support options.  To learn more about Social Ink and our projects, please visit our main site at <a href="http://www.social-ink.net">social-ink.net</a>.</p>
						<div style="clear:both"></div>
					</div>						
					</div>		
				</div>
			</div>	
 </div>