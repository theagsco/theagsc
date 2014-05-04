<?php

//$s = 'http://www.photos-dauphine.com/wp-content/uploads/2010/07/champ-chatelard-425x282.jpg';

	$image_type = exif_imagetype($s);
			
	switch($image_type) {
		case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($s); break;
		case IMAGETYPE_GIF: $src = imagecreatefromgif($s); break;
		case IMAGETYPE_PNG: $src = imagecreatefrompng($s); break;
		default: 
			echo "Exif problem : exif_imagetype('$s') returned '" . (!$image_type ? 'FALSE' : $image_type) . "' <br/>";
			echo "<pre>";print_r(gd_info());echo "</pre>";
			echo "Aborting<br/>";
			exit();
	}
	
	header("Content-type: {$image_type}");
		
	list($width, $height) = getimagesize($s);
	$thumb = imagecreatetruecolor($w, $h);
	imagecopyresampled($thumb, $src, 0, 0, 0, 0, $w, $h, $width, $height);
	imagejpeg($thumb);
	imagedestroy($thumb);
	imagedestroy($src);

?>