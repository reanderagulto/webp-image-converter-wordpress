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

	global $controlfile;
	global $txt;
	global $myfile;

	$txt = "";
	$controlfile  = 'control.file';    
    $myfile = fopen($controlfile, "a");
	/*-------------------------------------------------
	    Function name: imageCreateFromAny
	    Params: 
	        $filepath - image file path.
	---------------------------------------------------*/
	function imageCreateFromAny($filepath) { 
	    $path_parts = pathinfo($filepath);
	    $type = $path_parts['extension'];
	    /*
	    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
	    $allowedTypes = array( 
	        1,  // [] gif 
	        2,  // [] jpg 
	        3,  // [] png 
	        6   // [] bmp 
	    ); 
	    */
	    switch ($type) { 
	        case 'gif' : 
	            $im = imageCreateFromGif($filepath); 
	        break; 
	        case 'jpg' : 
	            $im = imageCreateFromJpeg($filepath); 
	        break; 
	        case 'png' : 
	            $im = imageCreateFromPng($filepath); 
	        break; 
	        case 'bmp' : 
	            $im = imageCreateFromBmp($filepath); 
	        break; 
	    }    
	    return $im;  
	   
	}
	/*-------------------------------------------------
	    Function name: readDirs
	    Params: 
	        $filefromcreate - created image (png, jpg, bmp).
	        $origpath       - image original path
	        $filename       - Image file name only (no extension)
	---------------------------------------------------*/
	function readDirs($path){
	    global $controlfile;
	    global $txt;
		global $myfile;

	    $dirHandle = opendir($path);
	    $cfilecontent = file_get_contents($controlfile);
	    while($item = readdir($dirHandle)) {
	        $newPath = $path."/".$item;
	        $path_parts = pathinfo($newPath);
	        $type = $path_parts['extension'];
	        if(is_dir($newPath) && $item != '.' && $item != '..') {
	           readDirs($newPath);
	        }
	        else if(is_file($newPath) && 
	                $type != 'webp' &&
	                (
	                    $type == 'png' ||
	                    $type == 'jpeg' ||
	                    $type == 'jpg' ||  
	                    $type == 'bmp' || 
	                   	$type == 'gif'
	                ) 
	        ){
	            $pattern = preg_quote($path_parts['dirname'] . $path_parts['filename'] . '.webp', '/');
	            $pattern = "/^.*$pattern.*\$/m";
	            //$im = imageCreateFromAny($newPath);
	            
	            if((!preg_match($pattern, $cfilecontent)) && (!file_exists($path_parts['dirname'] . $path_parts['filename'] . '.webp')))
	            {
	                $im = imageCreateFromAny($newPath);
	                if(imagewebp($im, $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp', 65)){
	                    $txt .= "Conversion for " . $path_parts['dirname'] . '/' . $path_parts['filename'] . ".webp is successful" . PHP_EOL;
	                }
	                imagedestroy($im);
	            }
	            
	        }
	    }

	}
	/*
		Change $folder based on framework
		Wordpress: 
			wp-content/uploads
		Magento 1: 
			skin/frontend/rwd/<theme>/images
		Magento 2:
			pub/media
	*/
	$folder = "wp-content/uploads"; 
	readDirs($folder);
	fwrite($myfile, $txt);
	fclose($myfile);
?>