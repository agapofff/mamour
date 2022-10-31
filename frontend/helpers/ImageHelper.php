<?php
namespace frontend\helpers;
    
class ImageHelper
{
    public static function getMainColor($filename)
    {
        $info = getimagesize($filename);
        
        switch ($info[2]) { 
            case 1: 
                $img = imageCreateFromGif($filename);
                break;					
            case 2: 
                $img = imageCreateFromJpeg($filename); 
                break;	
            case 3: 
                $img = imageCreateFromPng($filename); 
                break;
        }
         
        $width = ImageSX($img);
        $height = ImageSY($img);
         
        $thumb = imagecreatetruecolor(1, 1); 
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, 1, 1, $width, $height);
        $color = '#' . dechex(imagecolorat($thumb, 0, 0));
         
        imageDestroy($img);
        imageDestroy($thumb);
         
        return $color;
    }
    
    public static function getAverageColor($filename)
    {
        $info = getimagesize($filename);
        switch ($info[2]) { 
            case 1: 
                $img = imageCreateFromGif($filename);
                break;					
            case 2: 
                $img = imageCreateFromJpeg($filename); 
                break;	
            case 3: 
                $img = imageCreateFromPng($filename); 
                break;
        }
         
        $width = ImageSX($img);
        $height = ImageSY($img);
             
        $total_r = $total_g = $total_b = 0;
        
        for ($x = 0; $x < $width; $x++) {
            for ($y=0; $y<$height; $y++) {
                $c = ImageColorAt($img, $x, $y);
                $total_r += ($c>>16) & 0xFF;
                $total_g += ($c>>8) & 0xFF;
                $total_b += $c & 0xFF;
            }
        }
         
        $rgb = array(
            round($total_r / $width / $height),
            round($total_g / $width / $height),
            round($total_b / $width / $height)
        );
         
        $color = '#';
        foreach ($rgb as $row) {
            $color .= str_pad(dechex($row), 2, '0', STR_PAD_LEFT);
        }
         
        imageDestroy($img);
        echo $color;
    }
    
    public static function isDark($hex)
    {
        $hex = trim($hex, ' #');
     
        $size = strlen($hex);
        
        if ($size == 3) {
            $parts = str_split($hex, 1);
            $hex = '';
            foreach ($parts as $row) {
                $hex .= $row . $row;
            }		
        }
     
        $dec = hexdec($hex);
        $rgb = array(
            0xFF & ($dec >> 0x10),
            0xFF & ($dec >> 0x8),
            0xFF & $dec
        );
        
        $contrast = (round($rgb[0] * 299) + round($rgb[1] * 587) + round($rgb[2] * 114)) / 1000;
        
        return $contrast >= 170 ? false : true;
    }
}