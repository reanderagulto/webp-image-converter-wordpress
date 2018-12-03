<?php 
/*-------------------------------------------------
    Function name: imageCreateFromAny
    Params: 
        $filepath - image file path.
---------------------------------------------------*/
function imageCreateFromAny($filepath) { 
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3,  // [] png 
        6   // [] bmp 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
        case 1 : 
            $im = imageCreateFromGif($filepath); 
        break; 
        case 2 : 
            $im = imageCreateFromJpeg($filepath); 
        break; 
        case 3 : 
            $im = imageCreateFromPng($filepath); 
        break; 
        case 6 : 
            $im = imageCreateFromBmp($filepath); 
        break; 
    }    
    return $im;  
}
/*-------------------------------------------------
    Function name: imageConvertToWebP
    Params: 
        $filefromcreate - created image (png, jpg, bmp).
        $origpath       - image original path
        $filename       - Image file name only (no extension)
---------------------------------------------------*/
function imageConvertToWebP($filefromcreate, $origpath, $filename){
    $filepath = $origpath . "/" . $filename . ".webp";
    if(imagewebp($filefromcreate, $filepath))
        return true; 
    else
        return false;
}
/*-------------------------------------------------
    Function name: readDirs
    Params: 
        $filefromcreate - created image (png, jpg, bmp).
        $origpath       - image original path
        $filename       - Image file name only (no extension)
---------------------------------------------------*/
function readDirs($path){
    $dirHandle = opendir($path);
    while($item = readdir($dirHandle)) {
        $newPath = $path."/".$item;
        $path_parts = pathinfo($newPath);
        if(is_dir($newPath) && $item != '.' && $item != '..') {
           readDirs($newPath);
        }
        else if(is_file($newPath) && $path_parts['extension'] != 'webp'){
            $im = imageCreateFromAny($newPath);
            if(imageConvertToWebP($im, $path_parts['dirname'], $path_parts['filename']))
                echo 'Conversion Successful!<br>';
            else
                echo 'Conversion not Successful!<br>';
        }
    }
}
?>