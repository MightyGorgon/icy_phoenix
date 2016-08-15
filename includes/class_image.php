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

$mem_limit = img_check_mem_limit();
@ini_set('memory_limit', $mem_limit);

class ImgObj
{
	var $ImageID;
	var $ChangeFlag;
	var $Alpha;

	var $ExifData = array();
	var $ImageStatsSRC = array();
	var $ImageMimeType;
	var $ImageTypeNo;
	var $ImageTypeExt;

	var $JPGQuality = 75;

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
		$this->ImageStatsSRC = @getimagesize($image_file_name);

		if ($this->ImageStatsSRC[2] == 3)
		{
			$this->ImageStatsSRC[2] = IMG_PNG;
		}

		$this->ImageMimeType = $this->ImageStatsSRC['mime'];
		$this->ImageTypeNo = $this->ImageStatsSRC[2];

		switch ($this->ImageTypeNo)
		{
			case IMG_GIF:
				if(!(imagetypes() & $this->ImageTypeNo))
				{
					return false;
				}
				$this->ImageTypeExt = '.gif';
				$image_read_func = 'imagecreatefromgif';
				break;
			case IMG_JPG:
				if(!(imagetypes() & $this->ImageTypeNo))
				{
					return false;
				}
				if (function_exists('exif_read_data'))
				{
					$this->exif_get_data($image_file_name);
				}
				$this->ImageTypeExt = '.jpg';
				$image_read_func = 'imagecreatefromjpeg';
				break;
			case IMG_PNG:
				if(!(imagetypes() & $this->ImageTypeNo))
				{
					return false;
				}
				$this->ImageTypeExt = '.png';
				$image_read_func = 'imagecreatefrompng';
				break;
			default:
				return false;
		}

		$this->ImageID = $image_read_func($image_file_name);

		if (function_exists('imageistruecolor'))
		{
			if (imageistruecolor($this->ImageID))
			{
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
			}
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

		//$image_file_name .= $this->ImageTypeExt;
		switch ($this->ImageTypeNo)
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

		header('Content-type: ' . $this->ImageMimeType);
		// Better avoid filesize if we are not sure about that...
		/*
		$img_filesize = $this->ImageFilesize();
		if (!empty($img_filesize))
		{
			header('Content-Length: ' . $img_filesize);
		}
		*/
		header('Content-Disposition: filename=' . $pic_prefix . preg_replace('/[^A-Za-z0-9]+/', '_', $pic_name) . $pic_suffix . '.' . $pic_filetype);

		switch ($this->ImageTypeNo){
			case IMG_GIF:
				imagegif($this->ImageID);
				break;
			case IMG_JPG:
				if (empty($jpg_quality))
				{
					imagejpeg($this->ImageID);
				}
				else
				{
					imagejpeg($this->ImageID, null, $this->JpegQuality($jpg_quality));
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
	//   Usage : LoadSendToBrowser('Image', 'jpg', '', '', 75)
	//   Returns : true on success and false on failure
	//****************************************************************************
	function LoadSendToBrowser($pic_src, $pic_name = 'image', $pic_filetype = 'jpg', $pic_prefix = '', $pic_suffix = '', $jpg_quality = 75)
	{
		switch ($pic_filetype)
		{
			case 'gif':
				$pic_filetype_header = 'gif';
				break;
			case 'png':
				$pic_filetype_header = 'png';
				break;
			case 'jpg':
			case 'jpeg':
			default:
				$pic_filetype_header = 'jpeg';
				break;
		}

		header('Content-type: image/' . $pic_filetype_header);
		header('Content-Disposition: filename=' . $pic_prefix . $pic_name . $pic_suffix . '.' . $pic_filetype);
		@readfile($pic_src);

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
		if (!$this->ImageID || ($resize_width < 1) || ($resize_height < 1))
		{
			return false;
		}

		$this_image_w = $this->ImageWidth();
		$this_image_h = $this->ImageHeight();

		if (($this_image_w / $this_image_h) > ($resize_width / $resize_height))
		{
			if ($fit == -1)
			{
				$resize_height = $resize_width * ($this_image_h / $this_image_w);
			}
			elseif ($fit == 1)
			{
				$resize_width = $resize_height * ($this_image_w / $this_image_h);
			}
		}
		else
		{
			if ($fit == 1)
			{
				$resize_height = $resize_width * ($this_image_h / $this_image_w);
			}
			elseif ($fit == -1)
			{
				$resize_width = $resize_height * ($this_image_w / $this_image_h);
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
		$resize_function($resize, $this->ImageID, 0, 0, 0, 0, $resize_width, $resize_height, $this_image_w, $this_image_h);
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
		$filesize = 0;

		if (!$this->ImageID)
		{
			$this->ChangeFlag = false;
		}

		if ($this->ChangeFlag)
		{
			ob_start(); // start a new output buffer
			switch ($this->ImageTypeNo)
			{
				case IMG_GIF:
					imagegif($this->ImageID);
					break;
				case IMG_JPG:
					imagejpeg($this->ImageID, null, $this->JpegQuality());
					break;
				case IMG_PNG:
					imagepng($this->ImageID);
					break;
				default:
					return $filesize;
			}
			$filesize = ob_get_length();
			ob_end_clean(); // stop this output buffer
			$this->ChangeFlag = false;
		}

		return $filesize;
	}

	//****************************************************************************
	// Function to get and set JPEG Quality
	//   Usage : JpegQuality(Jpeg Quailty)
	//   Returns : JPEG Quailty
	//****************************************************************************
	function JpegQuality($quality = 75)
	{
		if (($quality >= 1) && ($quality <= 100))
		{
			$this->JPGQuality = $quality;
		}
		$this->ChangeFlag = true;
		return $this->JPGQuality;
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
		$this->ImageID = imagecreatefromstring(exif_thumbnail($image_file_name, $width, $height, $type));
		$this->ExifData = @exif_read_data($image_file_name, 0, true);
		if (($this->ImageTypeNo != IMG_JPG) || !($this->ImageID))
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

	// New EXIF Functions
	/*
	* EXIF Get EXIF Data
	*/
	function exif_get_data($pic)
	{
		$exif_data = exif_read_data($pic);
		$this->ExifData = $exif_data;
		return $this->ExifData;
	}

	/*
	* EXIF Get EXIF Data
	*/
	function exif_get_data_short($exif_data)
	{
		$exif_data_short = array(
			'MAKE' => !empty($exif_data['Make']) ? $exif_data['Make'] : 'EXIF_UNKNOWN',
			'MODEL' => !empty($exif_data['Model']) ? $exif_data['Model'] : 'EXIF_UNKNOWN',
			'LENS' => !empty($exif_data['Lens']) ? $exif_data['Lens'] : 'EXIF_UNKNOWN',
			'LENS_ID' => !empty($exif_data['LensId']) ? $exif_data['LensId'] : 'EXIF_UNKNOWN',
			'FLASH' => !empty($exif_data['Flash']) ? $exif_data['Flash'] : 'EXIF_UNKNOWN',
			'FOCAL_LENGTH' => !empty($exif_data['FocalLength']) ? ($this->exif_get_float($exif_data['FocalLength']) . 'mm') : 'EXIF_UNKNOWN',
			'EXPOSURE' => (!empty($exif_data['ExposureTime']) || !empty($exif_data['ShutterSpeedValue'])) ? $this->exif_get_exposure($exif_data) : 'EXIF_UNKNOWN',
			'APERTURE' => (!empty($exif_data['COMPUTED']['ApertureFNumber']) || !empty($exif_data['FNumber'])) ? $this->exif_get_aperture($exif_data) : 'EXIF_UNKNOWN',
			'ISO' => !empty($exif_data['ISOSpeedRatings']) ? $exif_data['ISOSpeedRatings'] : 'EXIF_UNKNOWN',
			'DATE' => !empty($exif_data['DateTime']) ? $exif_data['DateTime'] : 'EXIF_UNKNOWN',
		);
		return $exif_data_short;
	}

	/*
	* EXIF Get Float Value
	*/
	function exif_get_float($value)
	{
		$pos = strpos($value, '/');
		if ($pos === false) return (float) $value;
		$a = (float) substr($value, 0, $pos);
		$b = (float) substr($value, $pos + 1);
		return ($b == 0) ? ($a) : ($a / $b);
	}

	/*
	* EXIF Shutter Speed
	*/
	function exif_get_exposure($exif_data)
	{
		if (!isset($exif_data['ShutterSpeedValue']) && !isset($exif_data['ExposureTime'])) return 0;
		if (isset($exif_data['ExposureTime']))
		{
			$et = $exif_data['ExposureTime'];
			$pos = strpos($et, '/');
			if ($pos === false) return (float) $et . 's';
			$a = (float) substr($et, 0, $pos);
			$b = (float) substr($et, $pos + 1);
			if (($b == 0) || ($b == 1))
			{
				$shutter = $a . 's';
			}
			else
			{
				$shutter = '1/' . round($b / $a, 0) . 's';
			}
			return $shutter;
		}
		else
		{
			$ssv = exif_get_float($exif_data['ShutterSpeedValue']);
			$shutter = pow(2, -$ssv);
			if ($shutter == 0) return 0;
			if ($shutter >= 1) return round($shutter, 0) . 's';
			return '1/' . round(1 / $shutter) . 's';
		}
	}

	/*
	* EXIF Aperture
	*/
	function exif_get_aperture($exif_data)
	{
		if (!isset($exif_data['COMPUTED']['ApertureFNumber']) && !isset($exif_data['FNumber'])) return 0;
		if (isset($exif_data['COMPUTED']['ApertureFNumber']))
		{
			return $exif_data['COMPUTED']['ApertureFNumber'];
		}
		if (isset($exif_data['FNumber']))
		{
			$apex = $this->exif_get_float($exif_data['FNumber']);
			$fstop = pow(2, $apex / 2);
			if ($fstop == 0) return 0;
			return 'f/' . round($fstop, 1);
		}
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

		$this_image_w = $this->ImageWidth();
		$this_image_h = $this->ImageHeight();

		$flip = ($this->gdVersion() == 1) ? imagecreate($this_image_w, $this_image_h) : imagecreatetruecolor($this_image_w, $this_image_h);
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
				$flip_function($flip, $this->ImageID, 0, 0, $this_image_w, 0, $this_image_w, $this_image_h, -$this_image_w, $this_image_h);
				break;
			case IMG_JPG:
				$flip_function($flip, $this->ImageID, 0, 0, 0, $this_image_h, $this_image_w, $this_image_h, $this_image_w, -$this_image_h);
				break;
			case IMG_PNG:
				$flip_function($flip, $this->ImageID, 0, 0, $this_image_w, $this_image_h, $this_image_w, $this_image_h, -$this_image_w, -$this_image_h);
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
		if ($user_ver != 2 && $gd_ver > 0)
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
		$this_image_w = $this->ImageWidth();
		$this_image_h = $this->ImageHeight();

		if($left < 0)
		{
			$left = 0;
		}
		if($top < 0)
		{
			$top = 0;
		}
		if($right > $this_image_w)
		{
			$right = $this_image_w;
		}
		if($bottom > $this_image_h)
		{
			$bottom = $this_image_h;
		}
		$temp_img = ($this->gdVersion() == 1) ? imagecreate($right - $left, $bottom - $top) : imagecreatetruecolor($right - $left, $bottom - $top);
		imagecopy($temp_img, $this->ImageID, 0, 0, $left, $top, $right - $left, $bottom - $top) || die('error');
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
		$this_image_w = $this->ImageWidth();
		$this_image_h = $this->ImageHeight();

		if ($this_image_w > $this_image_h)
		{
			$slack = ($this_image_w - $this_image_h) / 2;
			return $this->Crop($slack, 0, $this_image_w - $slack, $this_image_h);
		}
		else
		{
			$slack = ($this_image_h - $this->Width()) / 2;
			return $this->Crop(0, $slack, $this_image_w, $this_image_h - $slack);
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
		$watermarkfile_width = imagesx($watermarkfile_id);
		$watermarkfile_height = imagesy($watermarkfile_id);
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
			if((($this->ImageWidth() * $maxsize / 100) < $watermarkfile_width) || (($this->ImageHeight() * $maxsize / 100) < $watermarkfile_height))
			{
				$tempwidth = $this->ImageWidth();
				$tempheight = $this->ImageHeight();
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
		if (($watermarkfile_width > $resize_width) || ($watermarkfile_height > $resize_height))
		{
			if (($watermarkfile_width / $watermarkfile_height) > ($resize_width / $resize_height))
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
			if((($this->ImageWidth() * $maxsize / 100) < $watermarkfile_width) || (($this->ImageHeight() * $maxsize / 100) < $watermarkfile_height))
			{
				$tempwidth = $this->ImageWidth();
				$tempheight = $this->ImageHeight();
				$this->Resize($watermarkfile_width * 100 / $maxsize, $watermarkfile_height * 100 / $maxsize, 1, false);
			}
			$wm_resized = false;
		}

		//Position watermark and place on image
		switch($position)
		{
			case 1: // top left
				$dest_x = 0;
				$dest_y = 0;
				break;

			case 2: // top middle
				$dest_x = (($this->ImageWidth() - $watermarkfile_width) / 2);
				$dest_y = 0;
				break;

			case 3: // top right
				$dest_x = $this->ImageWidth() - $watermarkfile_width;
				$dest_y = 0;
				break;

			case 4: // middle left
				$dest_x = 0;
				$dest_y = ($this->ImageHeight() / 2) - ($watermarkfile_height / 2);
				break;

			case 5: // middle
				$dest_x = ($this->ImageWidth() / 2) - ($watermarkfile_width / 2);
				$dest_y = ($this->ImageHeight() / 2) - ($watermarkfile_height / 2);
				break;

			case 6: // middle right
				$dest_x = $this->ImageWidth() - $watermarkfile_width;
				$dest_y = ($this->ImageHeight() / 2) - ($watermarkfile_height / 2);
				break;

			case 7: // bottom left
				$dest_x = 0;
				$dest_y = $this->ImageHeight() - $watermarkfile_height;
				break;

			case 8: // bottom middle
				$dest_x = (($this->ImageWidth() - $watermarkfile_width) / 2);
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
		if (($transition == 100) || ($wm_resized == true))
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
				imagesetpixel($this->ImageID, $x, $y, $grayColor);
				imagecolorallocate($this->ImageID, $gray, $gray, $gray);
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
				$newcolour = imagecolorallocate($this->ImageID, $gray, $gray, $gray);
				imagesetpixel($this->ImageID, $x, $y, $newcolour);
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

	/**
	* Converts hexidecimal color value to rgb values and returns as array/string
	*
	* @param string $hex
	* @param bool $asString
	* @return array|string
	*/
	function hex2rgb($hex, $asString = false)
	{
		// strip off any leading #
		if (0 === strpos($hex, '#'))
		{
			$hex = substr($hex, 1);
		}
		elseif (0 === strpos($hex, '&H'))
		{
			$hex = substr($hex, 2);
		}

		// break into hex 3-tuple
		$cutpoint = ceil(strlen($hex) / 2) - 1;
		$rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

		// convert each tuple to decimal
		$rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
		$rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
		$rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

		return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
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
		return true;
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
				imagesetpixel($this->ImageID, $x, $y, $newcol);
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
				$newcol = imagecolorallocate($this->ImageID, $gray,$green,$gray);
				imagesetpixel($this->ImageID, $x, $y, $newcol);
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
				$newcol = imagecolorallocate($this->ImageID, $red, $green, $blue);
				imagesetpixel($this->ImageID, $x, $y, $newcol);
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
			imageconvolution($this->ImageID, $matrix, $divisor, 0);
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
	// Function to create a reflection on the image
	//   Usage : Reflection(40, 40, 50, false, '#a4a4a4')
	//****************************************************************************
	/**
	* Creates Apple-style reflection under image, optionally adding a border to main image
	*
	* @param int $percent
	* @param int $reflection
	* @param int $white
	* @param bool $border
	* @param string $borderColor
	*/
	function Reflection($percent = 40, $reflection = 40, $white = 50, $border = false, $border_color = '#a4a4a4')
	{
		$width = $this->ImageWidth();
		$height = $this->ImageHeight();

		$reflection_height = intval($height * ($reflection / 100));
		$new_height = $height + $reflection_height;
		$reflected_part = $height * ($percent / 100);

		$temp_img = imagecreatetruecolor($width, $new_height);
		imagealphablending($temp_img, true);

		$color_to_paint = imagecolorallocatealpha($temp_img, 255, 255, 255, 0);
		imagefilledrectangle($temp_img, 0, 0, $width, $new_height, $color_to_paint);

		imagecopyresampled($temp_img, $this->ImageID, 0, 0, 0, $reflected_part, $width, $reflection_height, $width, ($height - $reflected_part));

		// FLIP - BEGIN
		// Flip the image
		$x_i = imagesx($temp_img);
		$y_i = imagesy($temp_img);
		for($x = 0; $x < $x_i; $x++)
		{
			for($y = 0; $y < $y_i; $y++)
			{
				imagecopy($temp_img, $temp_img, $x, $y_i - $y - 1, $x, $y, 1, 1);
			}
		}
		// FLIP - END

		imagecopy($temp_img, $this->ImageID, 0, 0, 0, 0, $width, $height);

		imagealphablending($temp_img, true);

		for($i = 0; $i < $reflection_height; $i++)
		{
			$color_to_paint = imagecolorallocatealpha($temp_img, 255, 255, 255, ($i / $reflection_height * - 1 + 1) * $white);
			imagefilledrectangle($temp_img, 0, $height + $i, $width , $height + $i, $color_to_paint);
		}

		if($border == true)
		{
			$rgb = $this->hex2rgb($border_color, false);
			$color_to_paint = imagecolorallocate($temp_img, $rgb[0], $rgb[1], $rgb[2]);
			imageline($temp_img, 0, 0, $width, 0, $color_to_paint); //top line
			imageline($temp_img, 0, $height, $width, $height, $color_to_paint); //bottom line
			imageline($temp_img, 0, 0, 0, $height, $color_to_paint); //left line
			imageline($temp_img, $width - 1, 0, $width - 1, $height, $color_to_paint); //right line
		}

		$this->DestroyImage();
		$this->ImageID = $temp_img;
		$this->ChangeFlag = true;
		return true;
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
			for($x = 0; $x < $this->ImageWidth(); $x++)
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

				if(($left_px >= 0) && ($right_px < $this->ImageWidth()))
				{
					if(!isset($buffer[$left_px][$y]))
					{
						$buffer[$left_px][$y] = $colors[rand(1,255)];
					}
					$buffer[$right_px][$y] = $buffer[$left_px][$y];
				}
			}
			// Find and fill in any spaces we missed
			for($x = 0; $x < $this->ImageWidth();$x++)
			{
				if(!isset($buffer[$x][$y]))
				{
					$buffer[$x][$y] = $colors[rand(1,255)];
				}
			}
		}

		// Copy buffer to temp image
		for($y = 0; $y < $this->ImageHeight(); $y++)
		{
			for($x = 0; $x < $this->ImageWidth(); $x++)
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
* Function get_full_image_info
*/

define('IMAGE_WIDTH', 'width');
define('IMAGE_HEIGHT', 'height');
define('IMAGE_TYPE', 'type');
define('IMAGE_ATTR', 'attr');
define('IMAGE_BITS', 'bits');
define('IMAGE_CHANNELS', 'channels');
define('IMAGE_MIME', 'mime');

/**
* mixed get_full_image_info(file $file [, string $out])
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
* Function any_url_exists to check whether a file exists on any domain.
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
* Function any_url_exists_alt to check whether a file exists on any domain (version taken from php.net
*/
function any_url_exists_alt($url){
	return (@fopen($url,"r") == true);
}

/**
* Check MEM Limit... used to set higher memory usage when processing images
*/
function img_check_mem_limit()
{
	$mem_limit = @ini_get('memory_limit');
	if (!empty($mem_limit))
	{
		$unit = strtolower(substr($mem_limit, -1, 1));
		$mem_limit = (int) $mem_limit;

		if ($unit == 'k')
		{
			$mem_limit = floor($mem_limit / 1024);
		}
		elseif ($unit == 'g')
		{
			$mem_limit *= 1024;
		}
		elseif (is_numeric($unit))
		{
			$mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
		}
		$mem_limit = max(128, $mem_limit) . 'M';
	}
	else
	{
		$mem_limit = '128M';
	}
	return $mem_limit;
}

/*
* create_thumb
* Thumbnails Creation
*/
function create_thumb($source_pic, $allowed_extensions, $t_width, $t_height, $t_suffix = '', $t_path = '', $t_quality = '75', $force_size = false)
{
	if (!class_exists('class_files'))
	{
		include_once(IP_ROOT_PATH . 'includes/class_files.' . PHP_EXT);
	}
	$class_files = new class_files();

	if (!empty($allowed_extensions))
	{
		$allowed_extensions = is_array($allowed_extensions) ? $allowed_extensions : array($allowed_extensions);
		$allowed_extensions = array_map('strtolower', $allowed_extensions);
	}

	$file_details = array();
	$pic_fullpath = str_replace(array(' '), array('%20'), $source_pic);
	$file_details = $class_files->get_file_details($source_pic);
	$pic_filename = $file_details['name_full'];
	$pic_title = $file_details['filename'];
	$pic_filetype = $file_details['extension'];
	$pic_title_reg = $class_files->clean_string($pic_title, false);
	$pic_thumbnail = $pic_title . $t_suffix . '.' . $pic_filetype;

	if (!empty($allowed_extensions) && !in_array($pic_filetype, $allowed_extensions))
	{
		return false;
	}

	$pic_size = get_full_image_info($source_pic, null, true);
	if($pic_size == false)
	{
		return false;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype_new = strtolower($pic_size['type']);
	$pic_thumbnail_fullpath = (($t_path == '') ? '' : ($t_path . '/')) . $pic_thumbnail;

	switch($pic_filetype_new)
	{
		case 'gif':
			$img_tmp = @imagecreatefromgif($source_pic);
			break;
		case 'jpg':
			$img_tmp = @imagecreatefromjpeg($source_pic);
			break;
		case 'png':
			$img_tmp = @imagecreatefrompng($source_pic);
			break;
		default:
			return false;
			break;
	}

	$pic_ratio = $pic_width / $pic_height;
	$t_ratio = $t_width / $t_height;
	$x_offset = 0;
	$y_offset = 0;
	$dest_width = $t_width;
	$dest_height = $t_height;
	if (!empty($force_size))
	{
		if ($pic_ratio > $t_ratio)
		{
			$dest_width = $t_height * $pic_ratio;
		}
		else
		{
			$dest_height = $t_width / $pic_ratio;
		}
		$x_mid = $dest_width / 2;
		$y_mid = $dest_height / 2;
		$x_offset = round($x_mid - ($t_width / 2), 0);
		$y_offset = round($y_mid - ($t_height / 2), 0);

		// If we want to crop the image, we need an intermediate image... and we need to reset final width and height
		$img_new = @imagecreatetruecolor($dest_width, $dest_height);
		if (!$img_new)
		{
			return false;
		}

		@imagealphablending($img_new, false);
		@imagecopyresampled($img_new, $img_tmp, 0, 0, 0, 0, $dest_width, $dest_height, $pic_width, $pic_height);
		@imagesavealpha($img_new, true);
		@imagedestroy($img_tmp);

		$img_tmp = $img_new;
		$dest_width = $t_width;
		$dest_height = $t_height;
		$pic_width = $t_width;
		$pic_height = $t_height;
	}
	else
	{
		if (($pic_width <= $t_width) && ($pic_height <= $t_height))
		{
			$dest_width = $pic_width;
			$dest_height = $pic_height;
			if (($t_path != '') && !file_exists($t_path))
			{
				@mkdir ($t_path, 0777);
			}
			@copy($source_pic, $pic_thumbnail_fullpath);
			@chmod($pic_thumbnail_fullpath, 0755);
			return true;
		}
		else
		{
			if ($pic_ratio > $t_ratio)
			{
				$dest_width = round($t_height * $pic_ratio, 0);
			}
			else
			{
				$dest_height = round($t_width / $pic_ratio, 0);
			}
		}
	}

	$img_new = @imagecreatetruecolor($dest_width, $dest_height);
	if (!$img_new)
	{
		return false;
	}

	@imagealphablending($img_new, false);
	@imagecopyresampled($img_new, $img_tmp, 0, 0, $x_offset, $y_offset, $dest_width, $dest_height, $pic_width, $pic_height);
	@imagesavealpha($img_new, true);
	@imagedestroy($img_tmp);

	if (($t_path != '') && !file_exists($t_path))
	{
		@mkdir ($t_path, 0777);
	}

	// If you want all thumbnails to be forced as JPG you can decomment these lines
	/*
	imagejpeg ($img_new, $pic_thumbnail_fullpath, '75');
	return true;
	exit;
	*/

	switch ($pic_filetype)
	{
		case 'jpg':
			@imagejpeg($img_new, $pic_thumbnail_fullpath, '75');
			break;
		case 'png':
			@imagepng($img_new, $pic_thumbnail_fullpath);
			break;
		case 'gif':
			@imagegif($img_new, $pic_thumbnail_fullpath);
			break;
		default:
			return false;
			exit;
	}
	@chmod($pic_thumbnail_fullpath, 0755);
	@imagedestroy($img_new);
	return true;
}

/*
* create_thumb_forced
* Thumbnails creation forced to a specific size: if image is larger, it will be resized, extra space will be black; if image is smaller, it will be placed over a black frame.
*/
function create_thumb_forced($source_pic, $t_width, $t_height, $t_suffix = '', $t_path = '', $t_quality = '75', $force_size = false, $allowed_extensions = 'gif,jpg,jpeg,png')
{
	$allowed_extensions = explode(',', $allowed_extensions);
	$file_details = array();
	$pic_fullpath = str_replace(array(' '), array('%20'), $source_pic);
	$file_details = $this->get_file_details($source_pic);
	$pic_filename = $file_details['name_full'];
	$pic_title = $file_details['name'];
	$pic_filetype = $file_details['ext'];
	$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);
	$pic_thumbnail = $pic_title . $t_suffix . '.' . $pic_filetype;
	if (!in_array($pic_filetype, $allowed_extensions))
	{
		return false;
	}

	$pic_size = $this->get_full_image_info($source_pic);
	if($pic_size == false)
	{
		return false;
	}

	$pic_width = $pic_size['width'];
	$pic_height = $pic_size['height'];
	$pic_filetype_new = strtolower($pic_size['type']);
	$pic_thumbnail_fullpath = (($t_path == '') ? './' : $t_path) . $pic_thumbnail;

	switch($pic_filetype_new)
	{
		case 'gif':
			$img_tmp = @imagecreatefromgif($source_pic);
			break;
		case 'jpg':
			$img_tmp = @imagecreatefromjpeg($source_pic);
			break;
		case 'png':
			$img_tmp = @imagecreatefrompng($source_pic);
			break;
		default:
			return false;
			break;
	}

	$pic_ratio = $pic_width / $pic_height;
	$t_ratio = $t_width / $t_height;
	$dest_width = (int) $t_width;
	$dest_height = (int) $t_height;

	if (($pic_width <= $t_width) && ($pic_height <= $t_height))
	{
		$dest_width = $pic_width;
		$dest_height = $pic_height;
	}
	else
	{
		if ($pic_ratio > $t_ratio)
		{
			$dest_height = round($t_width / $pic_ratio, 0);
		}
		else
		{
			$dest_width = round($t_height * $pic_ratio, 0);
		}
	}

	// Generate thumbnail properly
	$img_t_new = @imagecreatetruecolor($dest_width, $dest_height);
	if (!$img_t_new)
	{
		return false;
	}
	@imagealphablending($img_t_new, false);
	@imagecopyresampled($img_t_new, $img_tmp, 0, 0, 0, 0, $dest_width, $dest_height, $pic_width, $pic_height);
	@imagesavealpha($img_t_new, true);

	// Put thumbnail on black canvas
	$img_new = @imagecreatetruecolor($t_width, $t_height);
	if (!$img_new)
	{
		return false;
	}
	$t_x_offset = round(($t_width - $dest_width) / 2, 0);
	$t_y_offset = round(($t_height - $dest_height) / 2, 0);
	@imagealphablending($img_new, false);
	@imagecopyresampled($img_new, $img_t_new, $t_x_offset, $t_y_offset, 0, 0, $t_width, $t_height, $t_width, $t_height);
	@imagesavealpha($img_new, true);

	if (($t_path != '') && !file_exists($t_path))
	{
		@mkdir($t_path, 0777);
	}

	// If you want all thumbnails to be forced as JPG you can decomment these lines
	/*
	imagejpeg($img_new, $pic_thumbnail_fullpath, '75');
	return true;
	exit;
	*/

	switch ($pic_filetype)
	{
		case 'jpg':
			@imagejpeg($img_new, $pic_thumbnail_fullpath, '75');
			break;
		case 'png':
			@imagepng($img_new, $pic_thumbnail_fullpath);
			break;
		case 'gif':
			@imagegif($img_new, $pic_thumbnail_fullpath);
			break;
		default:
			return false;
			exit;
	}
	@chmod($pic_thumbnail_fullpath, 0755);
	return true;
}

/**
* No Thumbnail function
*/
function image_no_thumbnail($filename = 'no_thumb.jpg')
{
	global $images;
	$filename = (empty($filename) ? 'no_thumb.jpg' : $filename);
	header('Content-type: image/jpeg');
	header('Content-Disposition: filename=' . $filename);
	readfile($images['no_thumbnail']);
	exit;
}

/**
* No Thumbnail function
*/
function image_output($pic_fullpath, $pic_title_reg, $pic_filetype, $pic_prefix = 'thumb_')
{
	global $images;
	$pic_name_output = $pic_prefix . $pic_title_reg . '.' . $pic_filetype;
	switch ($pic_filetype)
	{
		case 'gif':
			$file_header = 'Content-type: image/gif';
			break;
		case 'jpg':
			$file_header = 'Content-type: image/jpeg';
			break;
		case 'png':
			$file_header = 'Content-type: image/png';
			break;
		default:
			image_no_thumbnail($pic_name_output);
			exit;
			break;
	}
	header($file_header);
	header('Content-Disposition: filename=' . $pic_name_output);
	readfile($pic_fullpath);
	exit;
}

?>