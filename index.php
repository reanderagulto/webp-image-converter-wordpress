<?php
	/*-------------------------------------------------
		WEBP Image Converter
		Description: 
			Convert Images (png, jpg, bmp) to WEBP
		Creator:
			Reander Agulto
		How to Use:
			Change the $folder to corresponding folder of the images
			The script will convert every valid images inside the said folder
	---------------------------------------------------*/
	include_once("lib/image-convert.php");
	$folder = "uploads";
	readDirs($folder);
?>