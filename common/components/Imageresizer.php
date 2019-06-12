<?php
namespace common\components;
 
use Yii;
use yii\base\Component;


class Imageresizer extends Component {

   public function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg'){ 
			$image = imagecreatefromjpeg($source);
			imagejpeg($image, $destination, $quality);
		}elseif ($info['mime'] == 'image/gif') {
			$image = imagecreatefromgif($source);
			imagejpeg($image, $destination, $quality);
		}elseif ($info['mime'] == 'image/png') {
			
			$this->convertPNGto8bitPNG($source, $destination);
		}

		return $destination;
	}
	
	function convertPNGto8bitPNG($sourcePath, $destPath) {
		$srcimage = imagecreatefrompng($sourcePath);
		list($width, $height) = getimagesize($sourcePath);
		$img = imagecreatetruecolor($width, $height);
		$bga = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagecolortransparent($img, $bga);
		imagefill($img, 0, 0, $bga);
		imagecopy($img, $srcimage, 0, 0, 0, 0, $width, $height);
		imagetruecolortopalette($img, false, 255);
		imagesavealpha($img, true);
		imagepng($img, $destPath);
		imagedestroy($img);
		return $destPath;
    }
}
