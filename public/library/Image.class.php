<?php
class Image
{
    private $paper_width;
    private $paper_height;
    private $img_width;
    private $img_height;
    private $img_pos_top;
    private $img_pos_left;



}

$img_width = 1550;
$img_height = 1200;
//Set the content-type 
header("Content-type: image/png"); 

// Create the image 
$im = imagecreatetruecolor($img_width, $img_height); 

// Create some colors 
$white = imagecolorallocate($im, 255, 255, 255); 
$grey = imagecolorallocate($im, 128, 128, 128); 
$black = imagecolorallocate($im, 0, 0, 0); 
imagefilledrectangle($im, 0, 0, $img_width, $img_height, $white); 

// The text to draw 
$text = $content; 
// Replace path by your own font path 
$font = "./ttf/{$font_config[$type]['name']}.ttf";
// Add some shadow to the text 
//imagettftext($im, 60, 0, 11, 21, $grey, $font, $text); 

// Add the text 
imagettftext($im, 60, 0, 0, 70+(-$img_height+100)*($page-1), $black, $font, $text); 

// Using imagepng() results in clearer text compared with imagejpeg() 
//imagepng($im, 'filename.jpg'); 
imagepng($im); 
imagedestroy($im); 
exit;


//保存图片
ob_start();  
imagejpeg($im); 
$img = ob_get_contents();  
ob_end_clean();  
$size = strlen($img);  

$fp2=@fopen('tst.jpg', "a");  
fwrite($fp2,$img);  
fclose($fp2);
