<?php
$max_size = Subfolio::get_setting('display_max_filesize_iphone');
$size = Subfolio::current_file('rawsize');
$width = Subfolio::current_file('width');
$height = Subfolio::current_file('height');
?>

<div class="file file_img">

	<?php if (($width * $height) < ($max_size * 1024 * 1024)) { ?>
		
		<!-- File preview -->
		<div class="file_preview">
			<img src="<?php echo Subfolio::current_file('url') ?>" alt="" />
		</div>
		<!-- Information -->
		<?php if (SubfolioTheme::get_option('display_info')) { require("_download_box.php"); } ?>
			
	<?php
 	} else {
		echo "The image exceeds iPhone limits (copy to be determined)";
		require("_download_box.php");
	} ?>
	
</div>
