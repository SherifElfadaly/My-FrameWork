<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

class Image
{
	public static function UploadImage($imageName,$imageTmpName,$imageType,$newWidth,$newHeight,$path)
	{
		$extension = strtolower(pathinfo($imageName,PATHINFO_EXTENSION));
		$name = md5($imageName.time());
		$fullName = $name.'.'.$extension;

		switch ($imageType) {
			case "image/gif":
				$img=imagecreatefromgif($imageTmpName);
				break;
			case "image/jpg":
			case "image/jpeg":
				$img=imagecreatefromjpeg($imageTmpName);
				break;
			case "image/png":
				$img=imagecreatefrompng($imageTmpName);
				break;
			default:
				$error='Invalid File';
				break;
		}

		$crruentWidth=imagesx($img);
		$crruentHeight=imagesy($img);

		$newWidth=$newWidth;
		$newHeight=$newHeight;

		$scaledImage=imagecreatetruecolor($newWidth, $newHeight);

		if ($imageType == "image/png")
		{
			imagealphablending($scaledImage, false);
			imagesavealpha($scaledImage, true);
				
			imagealphablending($img, true);

			imagecopyresampled($scaledImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $crruentWidth, $crruentHeight);
				
			$x = imagepng($scaledImage,"$path".$fullName);
		}
		else
		{
			imagecopyresampled($scaledImage, $img, 0, 0, 0, 0, $newWidth, $newHeight, $crruentWidth, $crruentHeight);

			$x = imagejpeg($scaledImage,"$path".$fullName);
		}

		imagedestroy($img);
		imagedestroy($scaledImage);

		return $fullName;
	}
}
/* End of file image.php */