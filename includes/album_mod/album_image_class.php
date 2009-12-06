<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* Nuffmon (nuffmon@hotmail.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

class ImgObj
{
	var $ImageID;
	var $ExifData;
	var $ChangeFlag;
	var $Alpha;

	//****************************************************************************
	// Function called when object created
	//****************************************************************************
	function ImgObj()
	{
		$this->ChangeFlag = false;
		$this->Alpha = false;
	}

	//****************************************************************************
	// Function to read source image into memory
	//   Usage : ReadSourceFile(image file name)
	//****************************************************************************
	function ReadSourceFile($image_file_name)
	{
		$this->DestroyImage();
		$image_stats = @getimagesize($image_file_name);
		if ($image_stats[2] == 3)
		{
			$image_stats[2] = IMG_PNG;
		}
		$this->ImageTypeNo($image_stats[2]);
		switch ($this->ImageTypeNo())
		{
			case IMG_GIF:
				$read_function = 'imagecreatefromgif';
				break;
			case IMG_JPG:
				if (function_exists('exif_read_data'))
				{
					$this->ExifData = exif_read_data($image_file_name, 0,true);
				}
				$read_function = 'imagecreatefromjpeg';
				break;
			case IMG_PNG:
				$read_function = 'imagecreatefrompng';
				break;
			default:
				return false;
		}
		$this->ImageID = $read_function($image_file_name);
		if (function_exists('imageantialias'))
		{
			imageantialias($this->ImageID, true);
		}
		imagealphablending($this->ImageID, false);
		if (function_exists('imagesavealpha'))
		{
			$this->Alpha = true;
			imagesavealpha($this->ImageID, true);
		}
		$this->ChangeFlag = true;
		if ($this->ImageID)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//****************************************************************************
	// Function to read source image into memory
	//   Usage : ReadSourceFileJPG(image file name)
	//****************************************************************************
	function ReadSourceFileJPG($image_file_name)
	{
		//static $static_image_extension;
		//static $static_image_type;
		//static $static_mime_type;
		$this->DestroyImage();
		$static_image_type = IMG_JPG;
		$static_image_extension = '.jpg';
		$static_mime_type = 'image/jpeg';
		$this->ImageID = imagecreatefromjpeg($image_file_name);
		$this->ChangeFlag = true;
		if ($this->ImageID)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//****************************************************************************
	// Function to write image file to disk
	//   Usage : SendToFile(image file name, Quality)
	//   Returns : true on success and false on fail
	//****************************************************************************
	function SendToFile($image_file_name, $jpg_quality = 75)
	{
		if (!$this->ImageID)
		{
			return false;
		}
		//$image_file_name .= $this->ImageTypeExt();
		switch ($this->ImageTypeNo())
		{
			case IMG_GIF:
				imagegif($this->ImageID, $image_file_name);
				break;
			case IMG_JPG:
				imagejpeg($this->ImageID, $image_file_name, $this->JpegQuality($jpg_quality));
				break;
			case IMG_PNG:
				imagepng($this->ImageID, $image_file_name);
				break;
			default:
				return false;
		}
		@chmod($image_file_name, 0777);
		return true;
	}

	//****************************************************************************
	// Function to write image file to disk
	//   Usage : SendToFileJPG(image file name, Quality)
	//   Returns : true on success and false on fail
	//****************************************************************************
	function SendToFileJPG($image_file_name, $jpg_quality = 75)
	{
		if (!$this->ImageID)
		{
			return false;
		}
		imagejpeg($this->ImageID, $image_file_name, $this->JpegQuality($jpg_quality));
		@chmod($image_file_name, 0777);
		return true;
	}

	//****************************************************************************
	// Function to send image to browser
	//   Usage : SendToBrowser('Pic_Name', '.jpg', 'thumb_', '_nuffed')
	//   Returns : true on success and false on failure
	//****************************************************************************
	function SendToBrowser($pic_name = 'img_nuffed', $pic_filetype = 'jpg', $pic_prefix = '', $pic_suffix = '', $jpg_quality = 75)
	{
		if (!$this->ImageID)
		{
			return false;
		}
		header('Content-type: ' . $this->ImageMimeType());
		header('Content-Length: ' . $this->ImageFilesize());
		header('Content-Disposition: filename=' . $pic_prefix . preg_replace('/[^A-Za-z0-9]+/', '_', $pic_name) . $pic_suffix . '.' . $pic_filetype);
		switch ($this->ImageTypeNo()){
			case IMG_GIF:
				imagegif($this->ImageID);
				break;
			case IMG_JPG:
				if ($jpg_quality == false)
				{
					imagejpeg($this->ImageID);
				}
				else
				{
					imagejpeg($this->ImageID, "", $this->JpegQuality($jpg_quality));
				}
				break;
			case IMG_PNG:
				imagepng($this->ImageID);
				break;
			default:
				return false;
		}
		return true;
	}

	//****************************************************************************
	// Function to send image to browser
	//   Usage : SendToBrowserJPG('Pic_Name', '.jpg', 'thumb_', '_nuffed')
	//   Returns : true on success and false on failure
	//****************************************************************************
	function SendToBrowserJPG($pic_name = 'img_nuffed', $pic_filetype = 'jpg', $pic_prefix = '', $pic_suffix = '', $jpg_quality = 75)
	{
		if (!$this->ImageID)
		{
			return false;
		}
		header('Content-type: image/jpeg');
		header('Content-Disposition: filename=' . $pic_prefix . preg_replace('/[^A-Za-z0-9]+/', '_', $pic_name) . $pic_suffix . '.' . $pic_filetype);
		if ($jpg_quality == false)
		{
			imagejpeg($this->ImageID);
		}
		else
		{
			imagejpeg($this->ImageID, "", $this->JpegQuality($jpg_quality));
		}
		return true;
	}

	//****************************************************************************
	// Function to get image width
	//   Usage : ImageWidth()
	//   Returns : Image width in pixels or false on failure
	//****************************************************************************
	function ImageWidth()
	{
		if (!$this->ImageID)
		{
			return false;
		}
		return imagesx($this->ImageID);
	}

	//****************************************************************************
	// Function to get image height
	//   Usage : ImageHeight()
	//   Returns : Image height in pixels or false on failure
	//****************************************************************************
	function ImageHeight()
	{
		if (!$this->ImageID)
		{
			return false;
		}
		return imagesy($this->ImageID);
	}

	//****************************************************************************
	// Function to resize image
	//   usage : Resize(width, height, fit inside=-1 none=0 outside=-1, alpha)
	//   Returns : true on success and false on failure
	//******************************************************************************
	function Resize($resize_width = 0, $resize_height = 0, $fit = -1, $alpha = true)
	{
		if (!$this->ImageID || $resize_width < 1 || $resize_height < 1)
		{
			return false;
		}
		if (($this->ImageWidth() / $this->ImageHeight()) > ($resize_width / $resize_height))
		{
			if ($fit == -1)
			{
				$resize_height = $resize_width * ($this->ImageHeight()/$this->ImageWidth());
			}
			elseif ($fit == 1)
			{
				$resize_width = $resize_height * ($this->ImageWidth()/$this->ImageHeight());
			}
		}
		else
		{
			if ($fit == 1)
			{
				$resize_height = $resize_width * ($this->ImageHeight()/$this->ImageWidth());
			}
			elseif ($fit == -1)
			{
				$resize_width = $resize_height * ($this->ImageWidth()/$this->ImageHeight());
			}
		}
		$resize = ($this->gdVersion() == 1) ? imagecreate($resize_width, $resize_height) : imagecreatetruecolor($resize_width, $resize_height);
		$resize_function = ($this->gdVersion() == 1) ? 'imagecopyresized' : 'imagecopyresampled';
		if ($alpha){
			if (function_exists('imageantialias'))
			{
				imageantialias($resize, true);
			}
			imagealphablending($resize, false);
			if (function_exists('imagesavealpha'))
			{
				$this->Alpha = true;
				imagesavealpha($resize, true);
			}
		}
		$resize_function($resize, $this->ImageID, 0, 0, 0, 0, $resize_width, $resize_height, $this->ImageWidth(), $this->ImageHeight());
		imagedestroy($this->ImageID);
		$this->ImageID = $resize;
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to get or set filesize using current settings
	//   Usage : Filesize(refresh as bool [true = refresh filesize])
	//   Returns : filesize in bytes
	//****************************************************************************
	function ImageFileSize()
	{
		static $static_filesize = 0;

		if (!$this->ImageID)
		{
			$static_filesize = 0;
			$this->ChangeFlag = false;
		}

		if ($this->ChangeFlag)
		{
			ob_start(); // start a new output buffer
			switch ($this->ImageTypeNo())
			{
				case IMG_GIF:
					imagegif($this->ImageID);
					break;
				case IMG_JPG:
					imagejpeg($this->ImageID,"",$this->JpegQuality());
					break;
				case IMG_PNG:
					imagepng($this->ImageID);
					break;
				default:
					return false;
			}
			$static_filesize = ob_get_length();
			ob_end_clean(); // stop this output buffer
			$this->ChangeFlag = false;
		}

		return $static_filesize;
	}

	//****************************************************************************
	// Function to get and set JPEG Quality
	//   Usage : JpegQuality(Jpeg Quailty)
	//   Returns : JPEG Quailty
	//****************************************************************************
	function JpegQuality($quality = 75)
	{
		static $static_jpeg_quality = 75;
		if ( ($quality >= 1) && ($quality <= 100) )
		{
			$static_jpeg_quality = $quality;
		}
		$this->ChangeFlag = true;
		return $static_jpeg_quality;
	}

	//****************************************************************************
	// Function to get and set Imagetype
	//   Usage : ImageTypeNo(Image Type Constant)
	//   Returns : Image Type Constant
	//****************************************************************************
	function ImageTypeNo($image_type="", $validate=1)
	{
		static $static_image_type;
		switch ($image_type)
		{
			case IMG_GIF:
				if(!(imagetypes() & IMG_GIF))
				{
					return false;
				}
				$static_image_type = IMG_GIF;
				if ($validate)
				{
					$this->ImageTypeExt('.gif',0);
					$this->ImageMimeType('image/gif',0);
				}
				break;
			case IMG_JPG:
				if(!(imagetypes() & IMG_JPG))
				{
					return false;
				}
				$static_image_type = IMG_JPG;
				if ($validate)
				{
					$this->ImageTypeExt('.jpg',0);
					$this->ImageMimeType('image/jpeg',0);
				}
				break;
			case IMG_PNG:
				if(!(imagetypes() & IMG_PNG))
				{
					return false;
				}
				$static_image_type = IMG_PNG;
				if ($validate)
				{
					$this->ImageTypeExt('.png',0);
					$this->ImageMimeType('image/png',0);
				}
				break;
		}
		$this->ChangeFlag = true;
		return $static_image_type;
	}

	//****************************************************************************
	// Function to get and set Image mime type
	//   Usage : ImageTypeExt(Image type extension as string)
	//   Returns : Image type extension as string
	//****************************************************************************
	function ImageTypeExt($extension = '', $validate = 1)
	{
		static $static_image_extension;
		switch($extension)
		{
			case '.gif':
				if(!(imagetypes() & IMG_GIF))
				{
					return false;
				}
				$static_image_extension = '.gif';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_GIF,0);
					$this->ImageMimeType('image/gif',0);
				}
				break;
			case '.jpg':
			case '.jpeg':
				if(!(imagetypes() & IMG_JPG))
				{
					return false;
				}
				$static_image_extension = '.jpg';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_JPG,0);
					$this->ImageMimeType('image/jpeg',0);
				}
				break;
			case '.png':
				if(!(imagetypes() & IMG_PNG))
				{
					return false;
				}
				$static_image_extension = '.png';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_PNG,0);
					$this->ImageMimeType('image/png',0);
				}
				break;
		}
		return $static_image_extension;
	}

	//****************************************************************************
	// Function to get and set Image mime type
	//   Usage : ImageMimeType(Image mime type as string)
	//   Returns : Image mime type as string
	//****************************************************************************
	function ImageMimeType($mime_type = '', $validate = 1)
	{
		static $static_mime_type;
		switch($mime_type)
		{
			case 'image/gif':
				if(!(imagetypes() & IMG_GIF))
				{
					return false;
				}
				$static_mime_type = 'image/gif';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_GIF,0);
					$this->ImageTypeExt('.gif',0);
				}
				break;
			case 'image/jpeg':
			case 'image/jpg':
			case 'image/pjpeg':
				if(!(imagetypes() & IMG_JPG))
				{
					return false;
				}
				$static_mime_type = 'image/jpeg';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_JPG,0);
					$this->ImageTypeExt('.jpg',0);
				}
				break;
			case 'image/png':
			case 'image/x-png':
				if(!(imagetypes() & IMG_PNG))
				{
					return false;
				}
				$static_mime_type = 'image/png';
				if ($validate)
				{
					$this->ImageTypeNo(IMG_PNG,0);
					$this->ImageTypeExt('.png',0);
				}
				break;
		}
		return $static_mime_type;
	}

	//****************************************************************************
	// Function to read Exif image into memory
	//   Usage : ReadSourceExif(image file name)
	//   Returns : true on Success and false on failure
	//****************************************************************************
	function ReadSourceExif($image_file_name)
	{
		$this->DestroyImage();
		$image_stats = @getimagesize($image_file_name);
		if ($image_stats[2] != IMG_JPG)
		{
			return false;
		}
		$this->ImageID = imagecreatefromstring (exif_thumbnail($image_file_name, $width, $height, $type));
		$this->ImageTypeNo($type);
		$this->ExifData = exif_read_data($image_file_name, 0,true);
		if (($this->ImageTypeNo() != IMG_JPG) || !($this->ImageID))
		{
			return false;
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to auto correct using exif data
	//   Usage : ExifAutoCorrect()
	//   Returns : true on success, false on failure
	//****************************************************************************
	function ExifAutoCorrect()
	{
		switch ($this->ExifData['IFD0']['Orientation'])
		{
			case '1':
				return true;
				break;
			case '2':
				$this->Flip(1);
				break;
			case '3':
				$this->Rotate(180);
				break;
			case '4':
				$this->Flip(2);
				break;
			case '5':
				$this->Flip(1);
				$this->Rotate(90);
				break;
			case '6':
				$this->Rotate(-90);
				break;
			case '7':
				$this->Flip(1);
				$this->Rotate(-90);
				break;
			case '8':
				$this->Rotate(90);
				break;
			default:
				return false;
				break;
		}
		return true;
	}

	//****************************************************************************
	// Function to flip image
	//   usage : Flip(direction [1=Horizontal,2=Vertical,3=both])
	//   Returns : true on success and false on failure
	//******************************************************************************
	function Flip($direction)
	{
		if (!$this->ImageID || $direction < 1 || $direction > 3)
		{
			return false;
		}
		$flip = ($this->gdVersion() == 1) ? imagecreate($this->ImageWidth(), $this->ImageHeight()) : imagecreatetruecolor($this->ImageWidth(), $this->ImageHeight());
		$flip_function = (gdVersion == 1) ? 'imagecopyresized' : 'imagecopyresampled';
		if (function_exists('imageantialias'))
		{
			imageantialias($flip, true);
		}
		imagealphablending($flip, false);
		if (function_exists('imagesavealpha'))
		{
			$this->Alpha = true;
			imagesavealpha($flip, true);
		}
		switch ($direction)
		{
			case IMG_GIF:
				$flip_function($flip, $this->ImageID, 0, 0, $this->ImageWidth(), 0, $this->ImageWidth(), $this->ImageHeight(), -$this->ImageWidth(), $this->ImageHeight());
				break;
			case IMG_JPG:
				$flip_function($flip, $this->ImageID, 0, 0, 0, $this->ImageHeight(), $this->ImageWidth(), $this->ImageHeight(), $this->ImageWidth(), -$this->ImageHeight());
				break;
			case IMG_PNG:
				$flip_function($flip, $this->ImageID, 0, 0, $this->ImageWidth(), $this->ImageHeight(), $this->ImageWidth(), $this->ImageHeight(), -$this->ImageWidth(), -$this->ImageHeight());
				break;
		}
		imagedestroy($this->ImageID);
		$this->ImageID = $flip;
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to find version (1 or 2) of the GD extension.
	//   Usage : gdVersion()
	//   Returns : version number as integer
	//****************************************************************************
	function gdVersion($user_ver = 0)
	{
		if (! extension_loaded('gd'))
		{
			return;
		}
		static $gd_ver = 0;
		if ($user_ver == 1)
		{
			$gd_ver = 1; return 1;
		}
		if ($user_ver != 2 && $gd_ver > 0 )
		{
			return $gd_ver;
		}
		if (function_exists('gd_info'))
		{
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];
			return $match[0];
		}
		if (preg_match('/phpinfo/', ini_get('disable_functions')))
		{
			if ($user_ver == 2)
			{
				$gd_ver = 2;
				return 2;
			}
			else
			{
				$gd_ver = 1;
				return 1;
			}
		}
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];
		return $match[0];
	}

	//****************************************************************************
	// Function to rotate image
	//   Usage : rotate(anti-clockwise rotation)
	//****************************************************************************
	function Rotate($rotation)
	{
		$this->ImageID = imagerotate($this->ImageID, $rotation, 0);
		$this->ChangeFlag = true;
	}

	//****************************************************************************
	// Function to crop image
	//   Usage : Crop(	left as integer, top as integer, right as integer, bottom as integer)
	//   Returns : TRUE on success and FALSE on fail
	//****************************************************************************
	function Crop($left, $top, $right, $bottom)
	{
		if($left < 0)
		{
			$left = 0;
		}
		if($top < 0)
		{
			$top = 0;
		}
		if($right > $this->ImageWidth())
		{
			$right = $this->ImageWidth();
		}
		if($bottom > $this->ImageHeight())
		{
			$bottom = $this->ImageHeight();
		}
		$temp_img = ($this->gdVersion() == 1) ? imagecreate($right - $left, $bottom - $top) : imagecreatetruecolor($right - $left, $bottom - $top);
		imagecopy($temp_img, $this->ImageID, 0, 0, $left, $top, $right - $left, $bottom - $top) || die("Fuck");
		$this->DestroyImage();
		$this->ImageID = $temp_img;
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to crop image square to smallest dimension
	//   Usage : CropSquare()
	//   Returns : TRUE on success and FALSE on fail
	//****************************************************************************
	function CropSquare()
	{
		if ($this->ImageWidth() > $this->ImageHeight())
		{
			$slack = ($this->ImageWidth() - $this->ImageHeight()) / 2;
			return $this->Crop($slack, 0, $this->ImageWidth() - $slack, $this->ImageHeight());
		}
		else
		{
			$slack = ($this->ImageHeight() - $this->Width()) / 2;
			return $this->Crop(0, $slack, $this->ImageWidth(), $this->ImageHeight() - $slack);
		}
		// crop will change the changeflag for us
	}

	//****************************************************************************
	// Function to add watermark
	//   Usage : Watermark(Filename of the 24-bit PNG watermark file,
	//										position x as percentage,
	//										position y as percentage,
	//										maxsize as percentage)
	//   Returns : true on success and false on fail
	//****************************************************************************
	function Watermark($watermarkfile, $x = 50, $y = 50, $maxsize = 50)
	{
		$this->Resize($this->ImageWidth(), $this->ImageHeight(), 0, false);
		// Load the watermark into memory
		$watermarkfile_id = imagecreatefrompng($watermarkfile);
		$watermarkfile_width = imageSX($watermarkfile_id);
		$watermarkfile_height = imageSY($watermarkfile_id);
		// If alpha is true we have the capability to resize the png thus saving time and memory.
		// If not then we will have to do it the other way.
		if ($this->Alpha)
		{
			$resize_width = $this->ImageWidth() * $maxsize / 100;
			$resize_height = $this->ImageHeight() * $maxsize / 100;
			if (($watermarkfile_width / $watermarkfile_height) > ($resize_width / $resize_height))
			{
				$resize_height = $resize_width * ($watermarkfile_height / $watermarkfile_width);
			}
			else
			{
				$resize_width = $resize_height * ($watermarkfile_width / $watermarkfile_height);
			}
			$resize = ($this->gdVersion() == 1) ? imagecreate($resize_width, $resize_height) : imagecreatetruecolor($resize_width, $resize_height);
			$resize_function = (gdVersion == 1) ? 'imagecopyresized' : 'imagecopyresampled';
			if (function_exists('imageantialias'))
			{
				imageantialias($resize, true);
			}
			imagealphablending($resize, false);
			if (function_exists('imagesavealpha'))
			{
				imagesavealpha($resize, true);
			}
			$resize_function($resize, $watermarkfile_id, 0, 0, 0, 0, $resize_width, $resize_height, $watermarkfile_width, $watermarkfile_height);
			imagedestroy($watermarkfile_id);
			$watermarkfile_id = $resize;
			$watermarkfile_width = $resize_width;
			$watermarkfile_height = $resize_height;
		}
		else
		{
			//If watermark is too big, resize image then reduce after applying watermark
			//If we resize the watermark we will lose transparency.
			if( (($this->ImageWidth() * $maxsize / 100) < $watermarkfile_width) || (($this->ImageHeight() * $maxsize / 100) < $watermarkfile_height) )
			{
				$tempwidth=$this->ImageWidth();
				$tempheight=$this->ImageHeight();
				$this->Resize($watermarkfile_width * 100 / $maxsize, $watermarkfile_height * 100 / $maxsize, 1, false);
			}
		}
		//Position watermark and place on image
		$dest_x = ($this->ImageWidth() - $watermarkfile_width) / 100 * $x;
		$dest_y = ($this->ImageHeight() - $watermarkfile_height) / 100 * $y;
		imagecopy($this->ImageID, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
		imagedestroy($watermarkfile_id);
		//if it doesn't need resizing then resize function will ignore
		$this->Resize($tempwidth, $tempheight, 0, false);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to add watermark at position
	//   Usage : WatermarkPos(Filename of the 24-bit PNG watermark file,
	//										position as 1 to 9 matrix,
	//										1 2 3
	//										4 5 6
	//										7 8 9
	//										maxsize as percentage,
	//										transition is the transparency level to be applied on the watermark)
	//   Returns : true on success and false on fail
	//****************************************************************************
	function WatermarkPos($watermarkfile, $position = 5, $maxsize = 50, $transition = 100)
	{
		//$this->Resize($this->ImageWidth(), $this->ImageHeight(), 0, false);
		// Load the watermark into memory
		$watermarkfile_id = imagecreatefrompng($watermarkfile);
		$watermarkfile_width = imagesx($watermarkfile_id);
		$watermarkfile_height = imagesy($watermarkfile_id);
		$resize_width = $this->ImageWidth() * $maxsize / 100;
		$resize_height = $this->ImageHeight() * $maxsize / 100;
		if ( ($watermarkfile_width > $resize_width) || ($watermarkfile_height > $resize_height) )
		{
			if ( ($watermarkfile_width / $watermarkfile_height) > ($resize_width / $resize_height) )
			{
				$resize_height = $resize_width * ($watermarkfile_height / $watermarkfile_width);
			}
			else
			{
				$resize_width = $resize_height * ($watermarkfile_width / $watermarkfile_height);
			}

			$resize = ($this->gdVersion() == 1) ? imagecreate($resize_width, $resize_height) : imagecreatetruecolor($resize_width, $resize_height);
			$resize_function = ($this->gdVersion() == 1) ? 'imagecopyresized' : 'imagecopyresampled';
			if (function_exists('imageantialias'))
			{
				imageantialias($resize, true);
			}
			imagealphablending($resize, false);
			if (function_exists('imagesavealpha'))
			{
				imagesavealpha($resize, true);
			}
			$resize_function($resize, $watermarkfile_id, 0, 0, 0, 0, $resize_width, $resize_height, $watermarkfile_width, $watermarkfile_height);
			imagedestroy($watermarkfile_id);
			$watermarkfile_id = $resize;
			$watermarkfile_width = $resize_width;
			$watermarkfile_height = $resize_height;
			$wm_resized = true;
		}
		else
		{
			//If watermark is too big, resize image then reduce after applying watermark
			//If we resize the watermark we will lose transparency.
			if( (($this->ImageWidth() * $maxsize / 100) < $watermarkfile_width) || (($this->ImageHeight() * $maxsize / 100) < $watermarkfile_height) )
			{
				$tempwidth = $this->ImageWidth();
				$tempheight = $this->ImageHeight();
				$this->Resize($watermarkfile_width * 100 / $maxsize, $watermarkfile_height * 100 / $maxsize, 1, false);
			}
			$wm_resized = false;
		}

		//Position watermark and place on image
		switch( $position )
		{
			case 1: // top left
				$dest_x = 0;
				$dest_y = 0;
				break;

			case 2: // top middle
				$dest_x = ( ( $this->ImageWidth() - $watermarkfile_width ) / 2 );
				$dest_y = 0;
				break;

			case 3: // top right
				$dest_x = $this->ImageWidth() - $watermarkfile_width;
				$dest_y = 0;
				break;

			case 4: // middle left
				$dest_x = 0;
				$dest_y = ( $this->ImageHeight() / 2 ) - ( $watermarkfile_height / 2 );
				break;

			case 5: // middle
				$dest_x = ( $this->ImageWidth() / 2 ) - ( $watermarkfile_width / 2 );
				$dest_y = ( $this->ImageHeight() / 2 ) - ( $watermarkfile_height / 2 );
				break;

			case 6: // middle right
				$dest_x = $this->ImageWidth() - $watermarkfile_width;
				$dest_y = ( $this->ImageHeight() / 2 ) - ( $watermarkfile_height / 2 );
				break;

			case 7: // bottom left
				$dest_x = 0;
				$dest_y = $this->ImageHeight() - $watermarkfile_height;
				break;

			case 8: // bottom middle
				$dest_x = ( ( $this->ImageWidth() - $watermarkfile_width ) / 2 );
				$dest_y = $this->ImageHeight() - $watermarkfile_height;
				break;

			case 9: // bottom right
				$dest_x = $this->ImageWidth() - $watermarkfile_width;
				$dest_y = $this->ImageHeight() - $watermarkfile_height;
				break;

			default:
				break;
		}

		//$transition = 50;
		if ( ($transition == 100) || ($wm_resized == true) )
		{
			imagecopy($this->ImageID, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
		}
		else
		{
			/*
			$source_id = imagecreatefrompng($this->ImageID);
			$watermarkfile_id = imagecreatefrompng($watermarkfile_id);
			imagealphablending($source_id, true);
			imagealphablending($watermarkfile_id, true);
			$i = array($source_id, $watermarkfile_id); // here is the array of images, using the above specified $flag and $mask images

			$s = $this->AlphaMerger($i);
			*/

			//imagecopy($this->ImageID, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height);
			imagecopymerge($this->ImageID, $watermarkfile_id, $dest_x, $dest_y, 0, 0, $watermarkfile_width, $watermarkfile_height, $transition);
		}

		imagedestroy($watermarkfile_id);
		//if it doesn't need resizing then resize function will ignore
		$this->Resize($tempwidth, $tempheight, 0, false);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function merge images preserving alpha
	//   Usage : AlphaMerger()
	//   Returns : Image
	//****************************************************************************
	function AlphaMerger($i)
	{
		// Create a new image
		$s = imagecreatetruecolor(imagesx($i[0]), imagesy($i[1]));

		// Merge all images
		imagealphablending($s, true);
		$z = $i;
		while($d = each($z))
		{
			imagecopy($s, $d[1], 0, 0, 0, 0, imagesx($d[1]), imagesy($d[1]));
		}
		//restore the transparency
		imagealphablending($s, false);
		$w = imagesx($s);
		$h = imagesy($s);
		for($x=0; $x < $w; $x++)
		{
			for($y=0; $y < $h; $y++)
			{
				$c = imagecolorat($s, $x, $y);
				$c = imagecolorsforindex($s, $c);
				$z = $i;
				$t = 0;
				while($d = each($z))
				{
					$ta = imagecolorat($d[1], $x, $y);
					$ta = imagecolorsforindex($d[1], $ta);
					$t += 127 - $ta['alpha'];
				}
				$t = ($t > 127) ? 127 : $t;
				$t = 127 - $t;
				$c = imagecolorallocatealpha($s, $c['red'], $c['green'], $c['blue'], $t);
				imagesetpixel($s, $x, $y, $c);
			}
			}
		imagesavealpha($s, true);
		return $s;
	}


	//****************************************************************************
	// Function convert to grayscale
	//   Usage : Grayscale()
	//   Returns : true on success or false on failure
	//****************************************************************************
	function Grayscale()
	{
		for ($y = 0; $y < $this->ImageHeight(); $y++)
		{
			for ($x = 0; $x < $this->ImageWidth(); $x++)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red  = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8)  & 0xFF;
				$blue  = $rgb & 0xFF;

				$gray = round(.299*$red + .587*$green + .114*$blue);

				// shift gray level to the left
				$grayR = $gray << 16;   // R: red
				$grayG = $gray << 8;    // G: green
				$grayB = $gray;         // B: blue

				// OR operation to compute gray value
				$grayColor = $grayR | $grayG | $grayB;

				// set the pixel color
				imagesetpixel ($this->ImageID, $x, $y, $grayColor);
				imagecolorallocate ($this->ImageID, $gray, $gray, $gray);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}


	//****************************************************************************
	// Function convert to black and white
	//   	Usage : Threshold(threshold [0-255]) 0=all white, 255=all black
	//    Returns : TRUE on success or FALSE on failure
	//****************************************************************************
	function Threshold($threshold=128)
	{
		for ($y = 0; $y <$this->ImageHeight(); $y++)
		{
			for ($x = 0; $x <$this->ImageWidth(); $x++)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red  = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8)  & 0xFF;
				$blue  = $rgb & 0xFF;
				$gray = round(.299*$red + .587*$green + .114*$blue);
				$gray = ($gray > $threshold) ? 255 : 0 ;
				// set the pixel color
				$newcolour = imagecolorallocate ($this->ImageID, $gray, $gray, $gray);
				imagesetpixel ($this->ImageID, $x, $y, $newcolour);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function convert to sepia (Yellow hue and fairly desaturated)
	//   Usage : Sepia()
	//   Returns : true on success or false on failure
	//****************************************************************************
	function Sepia()
	{
		$this->Tint(80, 40, 0);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function convert to sepia (Yellow hue and fairly desaturated)
	//   Usage : Sepia2()
	//   Returns : true on success or false on failure
	//****************************************************************************
	function Sepia2()
	{
		$this->HueSat(38, 0.5);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function to change hue and saturation for different effects
	//   	Usage : HueSat()
	//    Returns : true on success or false on failure
	//****************************************************************************
	function HueSat($hue, $sat)
	{
		for ($x = 0; $x < $this->ImageWidth(); $x++)
		{
			for ($y = 0; $y < $this->ImageHeight(); $y++)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$rgb = array(($rgb >> 16) & 0xFF, ($rgb >> 8) & 0xFF, $rgb & 0xFF);
				$pxhls = $this->rgb2hls($rgb);
				$pxhls[0] = $hue;
				$pxhls[2] = $sat;
				$rgb = $this->hls2rgb($pxhls);
				$colour = imagecolorallocate($this->ImageID, $rgb[0], $rgb[1], $rgb[2]);
				imagesetpixel($this->ImageID, $x, $y, $colour);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	// function to convert rgb to hls values
	function rgb2hls($rgb)
	{
		for ($c=0; $c<3; $c++)
		{
			$rgb[$c] = $rgb[$c] / 255;
		}

		$hls = array(0, 0, 0);
		$max = max($rgb);
		$min = min($rgb);

		$hls[1] = ($max + $min) / 2;
		if ($max == $min)
		{
			$hls[0] = null;
			$hls[2] = 0;
		}
		else
		{
			$delta = $max - $min;
			$hls[2] = ($hls[1] <= 0.5) ? ($delta / ($max + $min)) : ($delta / (2 - ($max + $min)));

			if ($rgb[0] == $max)
			{
				$hls[0] = ($rgb[1] - $rgb[2]) / $delta;
			}
			elseif ($rgb[1] == $max)
			{
				$hls[0] = 2 + ($rgb[2] - $rgb[0]) / $delta;
			}
			else
			{
				$hls[0] = 4 + ($rgb[0] - $rgb[1]) / $delta;
			}

			$hls[0] *= 60;
			if ($hls[0] < 0)
			{
				$hls[0] += 360;
			}
			if ($hls[0] > 360)
			{
				$hls[0] -= 360;
			}
		}
		ksort($hls);
		return $hls;
	}

	// Funtion to convert hls to rgb values
	function hls2rgb($hls)
	{
		$rgb = array(0, 0, 0);

		$m2 = ($hls[1] <= 0.5) ? ($hls[1] * (1 + $hls[2])) : ($hls[1] + $hls[2] * (1 - $hls[1]));
		$m1 = 2 * $hls[1] - $m2;

		if (!$hls[2])
		{
			if ($hls[0] === null)
			{
				$rgb[0] = $rgb[1] = $rgb[2] = $hls[1];
			}
			else
			{
				return false;
			}
		}
		else
		{
			$rgb[0] = $this->_hVal($m1, $m2, $hls[0] + 120);
			$rgb[1] = $this->_hVal($m1, $m2, $hls[0]);
			$rgb[2] = $this->_hVal($m1, $m2, $hls[0] - 120);
		}

		for ($c=0; $c<3; $c++)
		{
			$rgb[$c] = round($rgb[$c] * 255);
		}
		return $rgb;
	}


	function _hVal($n1, $n2, $h)
	{
		if ($h > 360)
		{
			$h -= 360;
		}
		elseif ($h < 0)
		{
			$h += 360;
		}

		if ($h < 60)
		{
			return $n1 + ($n2 - $n1) * $h / 60;
		}
		elseif ($h < 180)
		{
			return $n2;
		}
		elseif ($h < 240)
		{
			return $n1 + ($n2 - $n1) * (240 - $h) / 60;
		}
		else
		{
			return $n1;
		}
	}

	//****************************************************************************
	// Function add white text on a black band
	//   Usage : Text(text as string)
	//****************************************************************************
	function Text($text = "")
	{
		$text_font = 1;
		$text_colour = ImageColorAllocate($this->ImageID,255,255,255);
		$text_height = imagefontheight($text_font);
		$text_width = imagefontwidth($text_font) * strlen($text);
		$text_x = ($this->ImageWidth() - $text_width) / 2;
		$text_y = $this->ImageHeight() - 16 + ((16 - $text_height) / 2);
		imagefilledrectangle($this->ImageID, 0, $this->ImageHeight()-16, $this->ImageWidth(), $this->ImageHeight(), 0);
		imagestring($this->ImageID, 1, $text_x, $text_y, $text, $text_colour);
		$this->ChangeFlag = true;
	}

	//****************************************************************************
	// Function to interlace image
	//   Usage : Interlace()
	//****************************************************************************
	function Interlace()
	{
		global $black;
		for ($y = 1; $y < $this->ImageHeight(); $y += 2)
		{
			imageline($this->ImageID, 0, $y, $this->ImageWidth(), $y, $black);
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function screen image (two way interlace)
	//   Usage : Screen()
	//****************************************************************************
	function Screen()
	{
		global $black;
		for($x = 1; $x <= $this->ImageWidth(); $x += 2)
		{
			imageline($this->ImageID, $x, 0, $x, $this->ImageHeight(), $black);
		}
		for($y = 1; $y <= $this->ImageHeight(); $y += 2)
		{
			imageline($this->ImageID, 0, $y, $this->ImageWidth(), $y, $black);
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function apply a negative filter
	//   Usage : Negate()
	//****************************************************************************
	function Negate()
	{
		for ($x = 0; $x <$this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y <$this->ImageHeight(); ++$y)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;

				$red = 255 - $red;
				$green = 255 - $green;
				$blue = 255 - $blue;

				$newcol = imagecolorallocate($this->ImageID, $red,$green,$blue);
				imagesetpixel($this->ImageID, $x, $y, $newcol);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function apply a tint to image after desaturation
	//   Usage : Tint(red, green, blue)
	//****************************************************************************
	function Tint($rplus = 128, $gplus = 128, $bplus = 128)
	{
		for ($x = 0; $x <$this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y <$this->ImageHeight(); ++$y)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;
				$red = (int)(($red+$green+$blue)/3);
				$green = $red + $gplus;
				$blue = $red + $bplus;
				$red += $rplus;

				if ($red > 255)
				{
					$red = 255;
				}
				if ($green > 255)
				{
					$green = 255;
				}
				if ($blue > 255)
				{
					$blue = 255;
				}
				if ($red < 0)
				{
					$red = 0;
				}
				if ($green < 0)
				{
					$green = 0;
				}
				if ($blue < 0)
				{
					$blue = 0;
				}

				$newcol = imagecolorallocate ($this->ImageID, $red,$green,$blue);
				imagesetpixel ($this->ImageID, $x, $y, $newcol);
				}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function a noise filter to image
	//   Usage : Noise($intensity as integer [0-255])
	//****************************************************************************
	function Noise($intensity = 64)
	{
		for ($x = 0; $x < $this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y < $this->ImageHeight(); ++$y)
			{
				if (rand(0,1))
				{
					$rgb = imagecolorat($this->ImageID, $x, $y);
					$red = ($rgb >> 16) & 0xFF;
					$green = ($rgb >> 8) & 0xFF;
					$blue = $rgb & 0xFF;
					$modifier = rand(-$intensity,$intensity);
					$red += $modifier;
					$green += $modifier;
					$blue += $modifier;

					if ($red > 255)
					{
						$red = 255;
					}
					if ($green > 255)
					{
						$green = 255;
					}
					if ($blue > 255)
					{
						$blue = 255;
					}
					if ($red < 0)
					{
						$red = 0;
					}
					if ($green < 0)
					{
						$green = 0;
					}
					if ($blue < 0)
					{
						$blue = 0;
					}

					$newcol = imagecolorallocate($this->ImageID, $red, $green, $blue);
					imagesetpixel($this->ImageID, $x, $y, $newcol);
				}
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function scatter filter, similar to a blur
	//   Usage : Scatter($distance as integer [in pixels])
	//****************************************************************************
	function Scatter($distance = 3)
	{
		for ($x = 0; $x < $this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y < $this->ImageHeight(); ++$y)
			{
				$distx = rand(-$distance, $distance);
				$disty = rand(-$distance, $distance);

				if ($x + $distx >= $this->ImageWidth())
				{
					continue;
				}
				if ($x + $distx < 0)
				{
					continue;
				}
				if ($y + $disty >= $this->ImageHeight())
				{
					continue;
				}
				if ($y + $disty < 0)
				{
					continue;
				}

				$oldcol = imagecolorat($this->ImageID, $x, $y);
				$newcol = imagecolorat($this->ImageID, $x + $distx, $y + $disty);
				imagesetpixel($this->ImageID, $x, $y, $newcol);
				imagesetpixel($this->ImageID, $x + $distx, $y + $disty, $oldcol);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function pixelate image
	//   Usage : Pixelate(Block size as integer [in pixels])
	//****************************************************************************
	function Pixelate($blocksize = 4)
	{
		//Cheat by reducing then enlarging image
		$x = intval($this->ImageWidth() / $blocksize);
		$y = intval($this->ImageHeight() / $blocksize);
		$ox = $this->ImageWidth();
		$oy = $this->ImageHeight();
		$this->Resize($x, $y, -1);
		$this->Resize($ox, $oy, 1);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function blur image
	//   Usage : Blur(X Distance as integer [in pixels], Y Distance as integer [in pixels])
	//****************************************************************************
	function Blur($xd = 4, $yd = 4)
	{
		$temp_img = @imagecreatetruecolor($this->ImageWidth(), $this->ImageHeight()) or die("Cannot Initialize new GD image stream");
		for ($x = 0; $x < $this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y < $this->ImageHeight(); ++$y)
			{
				$xstart = $x - $xd;
				$ystart = $y - $yd;
				$xdist = ($xd * 2) + 1;
				$ydist = ($yd * 2) + 1;
				if ($xstart < 0)
				{
					$xstart = 0;
				}
				if ($ystart < 0)
				{
					$ystart = 0;
				}
				if ($xstart + $xdist > $this->ImageWidth())
				{
					$xdist = $this->ImageWidth() - $xstart;
				}
				if ($ystart + $ydist > $this->ImageHeight())
				{
					$ydist = $this->ImageHeight() - $ystart;
				}
				imagecopyresampled($temp_img, $this->ImageID, $x, $y, $xstart, $ystart, 1, 1, $xdist , $ydist);
			}
		}
		$this->DestroyImage();
		$this->ImageID = $temp_img;
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function blur image
	//   Usage : Blur2(Distance as integer [in pixels])
	//****************************************************************************
	function Blur2($distance = 3)
	{
		for ($x = 0; $x < $this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y < $this->ImageHeight(); ++$y)
			{
				$newr = 0;
				$newg = 0;
				$newb = 0;

				$colours = array();
				$thiscol = imagecolorat($this->ImageID, $x, $y);

				for ($k = $x - $distance; $k <= $x + $distance; ++$k)
				{
					for ($l = $y - $distance; $l <= $y + $distance; ++$l)
					{
						if ($k < 0)
						{
							$colours[] = $thiscol;
							continue;
						}
						if ($k >= $this->ImageWidth())
						{
							$colours[] = $thiscol;
							continue;
						}
						if ($l < 0)
						{
							$colours[] = $thiscol;
							continue;
						}
						if ($l >= $this->ImageHeight())
						{
							$colours[] = $thiscol;
							continue;
						}
						$colours[] = imagecolorat($this->ImageID, $k, $l);
					}
				}

				foreach($colours as $colour)
				{
					$newr += ($colour >> 16) & 0xFF;
					$newg += ($colour >> 8) & 0xFF;
					$newb += $colour & 0xFF;
				}

				$numelements = sizeof($colours);
				$newr /= $numelements;
				$newg /= $numelements;
				$newb /= $numelements;

				$newcol = imagecolorallocate($this->ImageID, $newr, $newg, $newb);
				imagesetpixel($this->ImageID, $x, $y, $newcol);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function infrared filter
	//   Usage : Infrared(Noise [1 to 128])
	//****************************************************************************
	function Infrared($noise = 20)
	{
		for ($x = 0; $x <$this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y <$this->ImageHeight(); ++$y)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;
				$modifier = rand(-$noise, $noise);
				$gray = (int)((($red + $green + $blue) / 3) + $modifier);
				$green = $gray + 60;
				if ($gray > 255)
				{
					$gray = 255;
				}
				if ($green > 255)
				{
					$green = 255;
				}
				if ($gray < 0)
				{
					$gray = 0;
				}
				if ($green < 0)
				{
					$green = 0;
				}
				$newcol = imagecolorallocate ($this->ImageID, $gray,$green,$gray);
				imagesetpixel ($this->ImageID, $x, $y, $newcol);
			}
		}
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function infrared filter
	//   Usage : Infrared2()
	//****************************************************************************
	function Infrared2()
	{
		for ($x = 0; $x <$this->ImageWidth(); ++$x)
		{
			for ($y = 0; $y <$this->ImageHeight(); ++$y)
			{
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;
				$red *= 0.5;
				$green *= 2.0;
				$blue *= 0.5;
				if ($green > 255)
				{
					$green = 255;
				}
				$red = $green = $blue = (int)(($red+$green+$blue)/3);
				$green += 128;
				if ($green > 255)
				{
					$green = 255;
				}
				$newcol = imagecolorallocate ($this->ImageID, $red, $green, $blue);
				imagesetpixel ($this->ImageID, $x, $y, $newcol);
			}
		}
		$this->Noise(30);
		$this->ChangeFlag = true;
		return true;
	}

	//****************************************************************************
	// Function Apply smooth filter to image
	//   Usage : Smooth()
	//****************************************************************************
	function Smooth()
	{
		return $this->Convolution(array(array(1, 2, 1),array(2, 4, 2),array(1, 2, 1)));
	}

	//****************************************************************************
	// Function Apply sharp filter to image
	//   Usage : Sharp()
	//****************************************************************************
	function Sharp()
	{
		return $this->Convolution(array(array(-1, -2, -1),array(-2, 19, -2),array(-1, -2, -1)));
	}

	//****************************************************************************
	// Function Apply edge filter to image
	//   Usage : Edge()
	//****************************************************************************
	function Edge()
	{
		return $this->Convolution(array(array(1, 1, 1),array(1, -7, 1),array(1, 1, 1)));
	}

	//****************************************************************************
	// Function Apply emboss filter to image
	//   Usage : Emboss()
	//****************************************************************************
	function Emboss()
	{
		if(function_exists("imagefilter"))
		{
			return imagefilter($this->ImageID, IMG_FILTER_EMBOSS);
		}
		else
		{
			return $this->Convolution(array(array(1, 1, -1),array(1, 1, -1),array(1, -1, -1)));
		}
	}


	//****************************************************************************
	// Function Apply convolution to image
	//   Usage : Convolution (array(array(-1,-2,-1),array(-2,19,-2),array(-1,-2,-1)))
	//****************************************************************************
	function Convolution($matrix)
	{
		// Use builtin php5 function if available
		if (function_exists("imageconvolution"))
		{
			$divisor = 0;
			for ($x = 0; $x < 3; $x++)
			{
				for ($y = 0; $y < 3; $y++)
				{
					$divisor += $matrix[$y][$x];
				}
			}
			imageconvolution ($this->ImageID, $matrix, $divisor, 0);
			$this->ChangeFlag = true;
			return true;
		}
		// Bugger, guess we'll do it the hard way
		else
		{
			$temp_img = imagecreatetruecolor($this->ImageWidth(), $this->ImageHeight());
			for ($x = 0; $x < $this->ImageWidth(); ++$x)
			{
				for ($y = 0; $y < $this->ImageHeight(); ++$y)
				{
					$total_weight = 0;
					$total_red = 0;
					$total_green = 0;
					$total_blue = 0;
					for ($ax = 0; $ax < 3; $ax++)
					{
						for ($ay = 0; $ay < 3; $ay++)
						{
							$bx = $x + $ax - 1;
							$by = $y + $ay - 1;
							if ($bx < 0)
							{
								$bx = 0;
							}
							if ($by < 0)
							{
								$by = 0;
							}
							if ($bx >= $this->ImageWidth())
							{
								$bx = $this->ImageWidth() - 1;
							}
							if ($by >= $this->ImageHeight())
							{
								$by = $this->ImageHeight() - 1;
							}
							$rgb = imagecolorat($this->ImageID, $bx, $by);
							$red = ($rgb >> 16) & 0xFF;
							$green = ($rgb >> 8) & 0xFF;
							$blue = $rgb & 0xFF;
							$total_weight += $matrix[$ay][$ax];
							$total_red += ($red * $matrix[$ay][$ax]);
							$total_green += ($green * $matrix[$ay][$ax]);
							$total_blue += ($blue * $matrix[$ay][$ax]);
						}
					}
					$new_red = $total_red / $total_weight;
					$new_green = $total_green / $total_weight;
					$new_blue = $total_blue / $total_weight;

					if ($new_red < 0)
					{
						$new_red = 0;
					}
					if ($new_red > 255)
					{
						$new_red = 255;
					}
					if ($new_green < 0)
					{
						$new_green = 0;
					}
					if ($new_green > 255)
					{
						$new_green = 255;
					}
					if ($new_blue < 0)
					{
						$new_blue = 0;
					}
					if ($new_blue > 255)
					{
						$new_blue = 255;
					}

					$newcol = imagecolorallocate($temp_img, $new_red, $new_green, $new_blue);
					imagesetpixel($temp_img, $x, $y, $newcol);
				}
			}
			$this->DestroyImage();
			$this->ImageID = $temp_img;
			$this->ChangeFlag = true;
			return true;
		}
	}

	//****************************************************************************
	// Function to convert to stereogram
	//   Usage : Stereogram(0=grayscale, 1=colour)
	//****************************************************************************
	function Stereogram($colour = 1)
	{
		// Define some constants
		define('DPI', 30);
		define('OBS_DIST', DPI*12);
		define('EYE_SEP', (int)DPI*2.5);
		define('SEP_FACTOR', 0.55);
		define('MAX_SCENE_HEIGHT', 255);
		define('MIN_SCENE_HEIGHT', 0);
		define('MAX_DEPTH', OBS_DIST);
		define('MIN_DEPTH', (int)((SEP_FACTOR*MAX_DEPTH*OBS_DIST)/((1-SEP_FACTOR)*MAX_DEPTH+OBS_DIST)));
		define('HEIGHT_SCALE', 2);

		// Initialise random number generator
		srand(time());

		// Create a temp image, maybe able to use original if 24bit????
		$stereo_img = imagecreate($this->ImageWidth(), $this->ImageHeight());

		// Initialise colours, or use grayscale if chosen
		for($i = 0; $i < 256; $i++)
		{
			if(!$colour)
			{
				$rnd_color = rand(0,255);
				$colors[] = imagecolorallocate($stereo_img, $rnd_color, $rnd_color, $rnd_color);
			}
			else
			{
				$colors[] = imagecolorallocate($stereo_img, rand(0,255), rand(0,255), rand(0,255));
			}
		}

		// Load the buffer array with the stereo graphics data
		for($y = 0; $y < $this->ImageHeight(); $y++)
		{
			for( $x = 0; $x < $this->ImageWidth(); $x++)
			{
				// Find gray value at point
				$rgb = imagecolorat($this->ImageID, $x, $y);
				$red = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8) & 0xFF;
				$blue = $rgb & 0xFF;
				$gray = (int)(($red+$green+$blue)/3);

				// Calculate seperation
				$depth_color = $gray;
				$height = $depth_color/HEIGHT_SCALE;
				$height = ($height > MAX_SCENE_HEIGHT) ? MAX_SCENE_HEIGHT : $height;
				$height = ($height < MIN_SCENE_HEIGHT) ? MIN_SCENE_HEIGHT : $height;
				$feature_z = MAX_DEPTH-$height*(MAX_DEPTH-MIN_DEPTH)/256;
				$sep = (int)((float)(EYE_SEP*$feature_z)/($feature_z+OBS_DIST));

				$left_px = (int)$x-($sep/2);
				$right_px = (int)$x+($sep/2);

				if(( $left_px >= 0) && ($right_px < $this->ImageWidth()))
				{
					if(!isset( $buffer[$left_px][$y]))
					{
						$buffer[$left_px][$y] = $colors[rand(1,255)];
					}
					$buffer[$right_px][$y] = $buffer[$left_px][$y];
				}
			}
			// Find and fill in any spaces we missed
			for( $x = 0; $x < $this->ImageWidth();$x++)
			{
				if(!isset( $buffer[$x][$y]))
				{
					$buffer[$x][$y] = $colors[rand(1,255)];
				}
			}
		}

		// Copy buffer to temp image
		for($y = 0; $y < $this->ImageHeight(); $y++)
		{
			for( $x = 0; $x < $this->ImageWidth(); $x++)
			{
					imagesetpixel($stereo_img,$x, $y, $buffer[$x][$y]);
			}
		}
		$this->DestroyImage();
		$this->ImageID = $stereo_img;
		$this->ChangeFlag = true;
		return true;

	}

	//****************************************************************************
	// Function remove image from memory
	//   Usage : DestroyImage()
	//****************************************************************************
	function DestroyImage()
	{
		if ($this->ImageID)
		{
			imagedestroy($this->ImageID);
		}
		$this->ChangeFlag = true;
	}

	//****************************************************************************
	// Function to destroy object and remove image from memory
	//   Usage : Destroy()
	//****************************************************************************
	function Destroy()
	{
		$this->DestroyImage();
		settype($this, 'null');
	}
}


/*
Function get_full_image_info
*/

define ('IMAGE_WIDTH', 'width');
define ('IMAGE_HEIGHT', 'height');
define ('IMAGE_TYPE', 'type');
define ('IMAGE_ATTR', 'attr');
define ('IMAGE_BITS', 'bits');
define ('IMAGE_CHANNELS', 'channels');
define ('IMAGE_MIME', 'mime');

/**
 * mixed get_full_image_info( file $file [, string $out] )
 *
 * Returns information about $file.
 *
 * If the second argument is supplied, a string representing that information will be returned.
 *
 * Valid values for the second argument are IMAGE_WIDTH, 'width', IMAGE_HEIGHT, 'height', IMAGE_TYPE, 'type',
 * IMAGE_ATTR, 'attr', IMAGE_BITS, 'bits', IMAGE_CHANNELS, 'channels', IMAGE_MIME, and 'mime'.
 *
 * If only the first argument is supplied an array containing all the information is returned,
 * which will look like the following:
 *
 *    [width] => int (width),
 *    [height] => int (height),
 *    [type] => string (type),
 *    [attr] => string (attributes formatted for IMG tags),
 *    [bits] => int (bits),
 *    [channels] => int (channels),
 *    [mime] => string (mime-type)
 *
 * Returns false if $file is not a file, no arguments are supplied, $file is not an image, or otherwise fails.
 *
 **/
function get_full_image_info($file = null, $out = null, $local = false)
{
	// If $file is not supplied or is not a file, warn the user and return false.
	if ($local == true)
	{
		if(is_null($file) || !is_file($file))
		{
			//echo '<p><b>Warning:</b> image_info() => first argument must be a file.</p>';
			//echo '<br /><br />' . $file . '<br /><br />';
			return false;
		}
	}
	else
	{
		if(is_null($file) || !any_url_exists($file))
		{
			//echo '<p><b>Warning:</b> image_info() => first argument must be a file.</p>';
			//echo '<br /><br />' . $file . '<br /><br />';
			return false;
		}
	}

	// Defines the keys we want instead of 0, 1, 2, 3, 'bits', 'channels', and 'mime'.
	$redefine_keys = array(
		'width',
		'height',
		'type',
		'attr',
		'bits',
		'channels',
		'mime',
	);

	// If $out is supplied, but is not a valid key, nullify it.
	if (!is_null($out) && !in_array($out, $redefine_keys))
	{
		$out = null;
	}

	// Assign useful values for the third index.
	$types = array(
		1 => 'GIF',
		2 => 'JPG',
		3 => 'PNG',
		4 => 'SWF',
		5 => 'PSD',
		6 => 'BMP',
		7 => 'TIFF(intel byte order)',
		8 => 'TIFF(motorola byte order)',
		9 => 'JPC',
		10 => 'JP2',
		11 => 'JPX',
		12 => 'JB2',
		13 => 'SWC',
		14 => 'IFF',
		15 => 'WBMP',
		16 => 'XBM'
	);
	$temp = array();
	$data = array();

	// Get the image info using getimagesize().
	// If $temp fails to populate, warn the user and return false.
	if (!$temp = @getimagesize($file))
	{
		//echo '<p><b>Warning:</b> image_info() => first argument must be an image.</p>';
		return false;
	}

	// Get the values returned by getimagesize()
	$temp = array_values($temp);

	// Make an array using values from $redefine_keys as keys and values from $temp as values.
	foreach ($temp as $k => $v)
	{
		$data[$redefine_keys[$k]] = $v;
	}

	// Make 'type' useful.
	$data['type'] = $types[$data['type']];

	// Return the desired information.
	return !is_null($out) ? $data[$out] : $data;
}

/*
Function any_url_exists to check whether a file exists on any domain.
*/
function any_url_exists($url)
{
	$a_url = parse_url($url);
	if (!isset($a_url['port']))
	{
		$a_url['port'] = 80;
	}
	$errno = 0;
	$errstr = '';
	$timeout = 30;
	if(isset($a_url['host']))
	{
		if(trim($a_url['host']) == gethostbyname(trim($a_url['host'])))
		{
			return true;
		}
		else
		{
			$fid = fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
			if (!$fid)
			{
				return false;
			}
			$page = isset($a_url['path']) ? $a_url['path'] : '';
			$page .= isset($a_url['query']) ? '?' . $a_url['query'] : '';
			fwrite($fid, 'HEAD ' . $page . ' HTTP/1.0' . "\r\n" . 'Host: ' . $a_url['host'] . "\r\n\r\n");
			$head = fread($fid, 4096);
			fclose($fid);
			return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
		}
	}
	else
	{
		return false;
	}
}

/*
Function nuff_http_vars needed to store all HTTP vars into one array
*/
function nuff_http_vars()
{
	$nuff_http = array();
	$nuff_http['full_string'] = '';

	// Var Init
	/* Resize */
	if( (isset($_GET['nuff_resize'])) || (isset($_POST['nuff_resize'])))
	{
		$nuff_http['nuff_resize'] = isset($_GET['nuff_resize']) ? intval($_GET['nuff_resize']) : intval($_POST['nuff_resize']);
	}
	else
	{
		$nuff_http['nuff_resize'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_resize=' . $nuff_http['nuff_resize'];

	/* Resize Width */
	if( (isset($_GET['nuff_resize_w'])) || (isset($_POST['nuff_resize_w'])))
	{
		$nuff_http['nuff_resize_w'] = isset($_GET['nuff_resize_w']) ? intval($_GET['nuff_resize_w']) : intval($_POST['nuff_resize_w']);
	}
	else
	{
		$nuff_http['nuff_resize_w'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_resize_w=' . $nuff_http['nuff_resize_w'];

	/* Resize Height*/
	if( (isset($_GET['nuff_resize_h'])) || (isset($_POST['nuff_resize_h'])))
	{
		$nuff_http['nuff_resize_h'] = isset($_GET['nuff_resize_h']) ? intval($_GET['nuff_resize_h']) : intval($_POST['nuff_resize_h']);
	}
	else
	{
		$nuff_http['nuff_resize_h'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_resize_h=' . $nuff_http['nuff_resize_h'];

	/* Recompression */
	if( (isset($_GET['nuff_recompress'])) || (isset($_POST['nuff_recompress'])))
	{
		$nuff_http['nuff_recompress'] = isset($_GET['nuff_recompress']) ? intval($_GET['nuff_recompress']) : intval($_POST['nuff_recompress']);
	}
	else
	{
		$nuff_http['nuff_recompress'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_recompress=' . $nuff_http['nuff_recompress'];

	/* Recompression Ratio */
	if( (isset($_GET['nuff_recompress_r'])) || (isset($_POST['nuff_recompress_r'])))
	{
		$nuff_http['nuff_recompress_r'] = isset($_GET['nuff_recompress_r']) ? intval($_GET['nuff_recompress_r']) : intval($_POST['nuff_recompress_r']);
	}
	else
	{
		$nuff_http['nuff_recompress_r'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_recompress_r=' . $nuff_http['nuff_recompress_r'];

	/* Rotation */
	if( (isset($_GET['nuff_rotation'])) || (isset($_POST['nuff_rotation'])))
	{
		$nuff_http['nuff_rotation'] = isset($_GET['nuff_rotation']) ? intval($_GET['nuff_rotation']) : intval($_POST['nuff_rotation']);
	}
	else
	{
		$nuff_http['nuff_rotation'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_rotation=' . $nuff_http['nuff_rotation'];

	/* Rotation Degree */
	if( (isset($_GET['nuff_rotation_d'])) || (isset($_POST['nuff_rotation_d'])))
	{
		$nuff_http['nuff_rotation_d'] = isset($_GET['nuff_rotation_d']) ? intval($_GET['nuff_rotation_d']) : intval($_POST['nuff_rotation_d']);
	}
	else
	{
		$nuff_http['nuff_rotation_d'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_rotation_d=' . $nuff_http['nuff_rotation_d'];

	/* Sepia */
	if( (isset($_GET['nuff_sepia'])) || (isset($_POST['nuff_sepia'])))
	{
		$nuff_http['nuff_sepia'] = isset($_GET['nuff_sepia']) ? intval($_GET['nuff_sepia']) : intval($_POST['nuff_sepia']);
	}
	else
	{
		$nuff_http['nuff_sepia'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_sepia=' . $nuff_http['nuff_sepia'];

	/* Black And White */
	if( (isset($_GET['nuff_bw'])) || (isset($_POST['nuff_bw'])))
	{
		$nuff_http['nuff_bw'] = isset($_GET['nuff_bw']) ? intval($_GET['nuff_bw']) : intval($_POST['nuff_bw']);
	}
	else
	{
		$nuff_http['nuff_bw'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_bw=' . $nuff_http['nuff_bw'];

	/* Mirror */
	if( (isset($_GET['nuff_mirror'])) || (isset($_POST['nuff_mirror'])))
	{
		$nuff_http['nuff_mirror'] = isset($_GET['nuff_mirror']) ? intval($_GET['nuff_mirror']) : intval($_POST['nuff_mirror']);
	}
	else
	{
		$nuff_http['nuff_mirror'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_mirror=' . $nuff_http['nuff_mirror'];

	/* Flip */
	if( (isset($_GET['nuff_flip'])) || (isset($_POST['nuff_flip'])))
	{
		$nuff_http['nuff_flip'] = isset($_GET['nuff_flip']) ? intval($_GET['nuff_flip']) : intval($_POST['nuff_flip']);
	}
	else
	{
		$nuff_http['nuff_flip'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_flip=' . $nuff_http['nuff_flip'];

	/* Blur */
	if( (isset($_GET['nuff_blur'])) || (isset($_POST['nuff_blur'])))
	{
		$nuff_http['nuff_blur'] = isset($_GET['nuff_blur']) ? intval($_GET['nuff_blur']) : intval($_POST['nuff_blur']);
	}
	else
	{
		$nuff_http['nuff_blur'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_blur=' . $nuff_http['nuff_blur'];

	/* Pixelate */
	if( (isset($_GET['nuff_pixelate'])) || (isset($_POST['nuff_pixelate'])))
	{
		$nuff_http['nuff_pixelate'] = isset($_GET['nuff_pixelate']) ? intval($_GET['nuff_pixelate']) : intval($_POST['nuff_pixelate']);
	}
	else
	{
		$nuff_http['nuff_pixelate'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_pixelate=' . $nuff_http['nuff_pixelate'];

	/* Scatter */
	if( (isset($_GET['nuff_scatter'])) || (isset($_POST['nuff_scatter'])))
	{
		$nuff_http['nuff_scatter'] = isset($_GET['nuff_scatter']) ? intval($_GET['nuff_scatter']) : intval($_POST['nuff_scatter']);
	}
	else
	{
		$nuff_http['nuff_scatter'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_scatter=' . $nuff_http['nuff_scatter'];

	/* Infrared */
	if( (isset($_GET['nuff_infrared'])) || (isset($_POST['nuff_infrared'])))
	{
		$nuff_http['nuff_infrared'] = isset($_GET['nuff_infrared']) ? intval($_GET['nuff_infrared']) : intval($_POST['nuff_infrared']);
	}
	else
	{
		$nuff_http['nuff_infrared'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_infrared=' . $nuff_http['nuff_infrared'];

	/* Tint */
	if( (isset($_GET['nuff_tint'])) || (isset($_POST['nuff_tint'])))
	{
		$nuff_http['nuff_tint'] = isset($_GET['nuff_tint']) ? intval($_GET['nuff_tint']) : intval($_POST['nuff_tint']);
	}
	else
	{
		$nuff_http['nuff_tint'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_tint=' . $nuff_http['nuff_tint'];

	/* Interlace */
	if( (isset($_GET['nuff_interlace'])) || (isset($_POST['nuff_interlace'])))
	{
		$nuff_http['nuff_interlace'] = isset($_GET['nuff_interlace']) ? intval($_GET['nuff_interlace']) : intval($_POST['nuff_interlace']);
	}
	else
	{
		$nuff_http['nuff_interlace'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_interlace=' . $nuff_http['nuff_interlace'];

	/* Screen */
	if( (isset($_GET['nuff_screen'])) || (isset($_POST['nuff_screen'])))
	{
		$nuff_http['nuff_screen'] = isset($_GET['nuff_screen']) ? intval($_GET['nuff_screen']) : intval($_POST['nuff_screen']);
	}
	else
	{
		$nuff_http['nuff_screen'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_screen=' . $nuff_http['nuff_screen'];

	/* Stereogram */
	if( (isset($_GET['nuff_stereogram'])) || (isset($_POST['nuff_stereogram'])))
	{
		$nuff_http['nuff_stereogram'] = isset($_GET['nuff_stereogram']) ? intval($_GET['nuff_stereogram']) : intval($_POST['nuff_stereogram']);
	}
	else
	{
		$nuff_http['nuff_stereogram'] = 0;
	}
	$nuff_http['full_string'] .= '&nuff_stereogram=' . $nuff_http['nuff_stereogram'];

	return $nuff_http;

}

// Old Watermark functions

//$sourcefile = Filename of the picture into that $insertfile will be inserted.
//$insertfile = Filename of the picture that is to be inserted into $sourcefile.
//$targetfile = Filename of the modified picture.
//$transition = Intensity of the transition (in percent).
//$pos        = Position where $insertfile will be inserted in $sourcefile.
//          1 = top left
//          2 = top middle
//          3 = top right
//          4 = middle left
//          5 = middle
//          6 = middle right
//          7 = bottom left
//          8 = bottom middle
//          9 = bottom right
//

function mergePics($sourcefile, $insertfile, $pos = 0, $transition = 50, $pic_filetype)
{

	global $album_config;

	$insertfile_id = imageCreateFromPNG($insertfile);

	switch( $pic_filetype )
	{
		case 'jpg':
			$sourcefile_id = imageCreateFromJPEG($sourcefile);
			break;
		case 'png':
			$sourcefile_id = imageCreateFromPNG($sourcefile);
			break;
		default:
			break;
	}

	// Get the size of both pics
	$sourcefile_width = imageSX($sourcefile_id);
	$sourcefile_height = imageSY($sourcefile_id);
	$insertfile_width = imageSX($insertfile_id);
	$insertfile_height = imageSY($insertfile_id);

	switch( $pos )
	{
		case 1: // top left
			$dest_x = 0;
			$dest_y = 0;
			break;

		case 2: // top middle
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = 0;
			break;

		case 3: // top right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = 0;
			break;

		case 4: // middle left
			$dest_x = 0;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 5: // middle
			$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 6: // middle right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 7: // bottom left
			$dest_x = 0;
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		case 8: // bottom middle
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		case 9: // bottom right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		default:
			break;
	}

	// Merge the two pix
	imageCopyMerge($sourcefile_id, $insertfile_id, $dest_x, $dest_y, 0, 0, $insertfile_width, $insertfile_height, $transition);

	// Create the final image
	switch( $pic_filetype )
	{
		case 'jpg':
			imagejpeg($sourcefile_id, '', $album_config['thumbnail_quality']);
			break;
		case 'png':
			imagepng($sourcefile_id);
			break;
		default:
			break;
	}

	imageDestroy($sourcefile_id);
}

function mergeResizePics($sourcefile, $insertfile, $thumbnail_width, $thumbnail_height, $pos = 0, $transition = 50, $pic_filetype)
{

	global $album_config;

	switch( $pic_filetype )
	{
		case 'jpg':
			$sourcefile_id = imageCreateFromJPEG($sourcefile);
			break;
		case 'png':
			$sourcefile_id = imageCreateFromPNG($sourcefile);
			break;
		default:
			break;
	}

	$insertfile_id = imageCreateFromPNG($insertfile);

	// Get the size of both pics
	$sourcefile_width = imageSX($sourcefile_id);
	$sourcefile_height = imageSY($sourcefile_id);
	$insertfile_width = imageSX($insertfile_id);
	$insertfile_height = imageSY($insertfile_id);

	if ($album_config['gd_version'] == 1)
	{
		$thumbnail = @imageCreate($thumbnail_width, $thumbnail_height);
		@imageCopyResized($thumbnail, $sourcefile_id, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $sourcefile_width, $sourcefile_height);
	}
	else
	{
		$thumbnail = @imageCreateTrueColor($thumbnail_width, $thumbnail_height);
		@imageCopyResampled($thumbnail, $sourcefile_id, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $sourcefile_width, $sourcefile_height);
	}

	// Reset the size
	$sourcefile_width = $thumbnail_width;
	$sourcefile_height = $thumbnail_height;

	switch( $pos )
	{
		case 1: // top left
			$dest_x = 0;
			$dest_y = 0;
			break;

		case 2: // top middle
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = 0;
			break;

		case 3: // top right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = 0;
			break;

		case 4: // middle left
			$dest_x = 0;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 5: // middle
			$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 );
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 6: // middle right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 );
			break;

		case 7: // bottom left
			$dest_x = 0;
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		case 8: // bottom middle
			$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 );
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		case 9: // bottom right
			$dest_x = $sourcefile_width - $insertfile_width;
			$dest_y = $sourcefile_height - $insertfile_height;
			break;

		default:
			break;
	}

	// Merge the two pix
	imageCopyMerge($thumbnail, $insertfile_id, $dest_x, $dest_y, 0, 0, $insertfile_width, $insertfile_height, $transition);
	imageDestroy($sourcefile_id);
	imageDestroy($insertfile_id);

	return $thumbnail;
}

?>