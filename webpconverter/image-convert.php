<?php 
global $controlfile;
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
    Function name: readDirs
    Params: 
        $filefromcreate - created image (png, jpg, bmp).
        $origpath       - image original path
        $filename       - Image file name only (no extension)
---------------------------------------------------*/
function readDirs($path){
    global $controlfile;
    $controlfile  = 'webpconverter/control.file';
    $dirHandle = opendir($path);
    $myfile = fopen($controlfile, "a");

    $cfilecontent = file_get_contents($controlfile);
    $txt = "";
    while($item = readdir($dirHandle)) {
        $newPath = $path."/".$item;
        $path_parts = pathinfo($newPath);
        if(is_dir($newPath) && $item != '.' && $item != '..') {
           readDirs($newPath);
        }
        else if(is_file($newPath) && 
                $path_parts['extension'] != 'webp' &&
                $path_parts['extension'] != 'mmdb' &&
                (
                    $path_parts['extension'] == 'png' ||
                    $path_parts['extension'] == 'jpeg' ||
                    $path_parts['extension'] == 'jpg' ||  
                    $path_parts['extension'] == 'bmp' || 
                    $path_parts['extension'] == 'gif'
                ) 
        ){
            $pattern = preg_quote($path_parts['dirname'] . $path_parts['filename'] . '.webp', '/');
            $pattern = "/^.*$pattern.*\$/m";
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
    fwrite($myfile, $txt);
    fclose($myfile);

}
?>