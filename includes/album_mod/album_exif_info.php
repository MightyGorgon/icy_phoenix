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
* Pgh-Biker
*
*/

// CTracker_Ignore: File checked by human
if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function make_exif($xkey, $xval)
{
	global $config;
	$exif_info = array(
//		'FILE_FileName' => 'File Name',
		'FILE_FileDateTime' => 'File Date and Time',
		'FILE_FileSize' => array(
			'Name' => 'File Size',
			'Units'  => ' bytes'
		),
//		'FILE_MimeType' => 'Mime Type',
		'COMPUTED_Height' => array(
			'Name' => 'Image Height',
			'Units'  => ' pixels'
		),
		'COMPUTED_Width' => array(
			'Name' => 'Image Width',
			'Units' => ' pixels'
		),
		'COMPUTED_ApertureFNumber' => 'Aperture F Number',
		'COMPUTED_UserComment' => 'User Comment',
		'IFD0_ImageDescription' => 'Image Description',
		'IFD0_Make' => 'Camera Make (Manufacturer)',
		'IFD0_Model' => 'Camera Model',
		'IFD0_XResolution' => array(
			'Name' => 'X Resolution',
			'Units' => ' Pixels per'
		),
		'IFD0_YResolution' => array(
			'Name' => 'Y Resolution',
			'Units' => ' Pixels per'
		),
		'IFD0_ResolutionUnit' => array(
			'Name' => 'Resolution Unit',
			2 => ' Inch',
			3 => ' Centimeter'
		),
		'IFD0_Software' => 'Software or Firmware',
		'IFD0_Artist' => 'Artist',
		'IFD0_Copyright' => 'Copyright',
		'EXIF_ExposureTime' => array(
			'Name' => 'Exposure Time',
			'Units' => ' seconds'
		),
//		'EXIF_FNumber' => 'F Number',
		'EXIF_ExposureProgram' => array(
			'Name' => 'Exposure Program',
			0 => 'Not defined',
			1 => 'Manual',
			2 => 'Normal program',
			3 => 'Aperture priority',
			4 => 'Shutter priority',
			5 => 'Creative program (biased toward depth of field)',
			6 => 'Action program (biased toward fast shutter speed)',
			7 => 'Portrait mode (for closeup photos with the background out of focus)',
			8 => 'Landscape mode (for landscape photos with the background in focus)'
		),
		'EXIF_ISOSpeedRatings' => 'ISO Speed Ratings',
		'EXIF_DateTimeOriginal' => 'Date and Time of Original',
		'EXIF_DateTimeDigitized' => 'Date and Time when Digitized',
		'EXIF_ExposureBiasValue' => array(
			'Name' => 'APEX Exposure Bias Value',
			'Units' => ' EV'
		),
		'EXIF_MaxApertureValue' => 'APEX Maximum Aperture Value',
		'EXIF_MeteringMode' => array(
			'Name' => 'Metering Mode',
			0 => 'Unknown',
			1 => 'Average',
			2 => 'Center Weighted Average',
			3 => 'Spot',
			4 => 'Multi Spot',
			5 => 'Pattern',
			6 => 'Partial',
			255 => 'Other'
		),
		'EXIF_LightSource' => array(
			'Name' => 'Light Source',
			0 => 'Unknown',
			1 => 'Daylight',
			2 => 'Fluorescent',
			3 => 'Tungsten (incandescent light)',
			4 => 'Flash',
			9 => 'Fine weather',
			10 => 'Cloudy weather',
			11 => 'Shade',
			12 => 'Daylight fluorescent (D 5700 – 7100K)',
			13 => 'Day white fluorescent (N 4600 – 5400K)',
			14 => 'Cool white fluorescent (W 3900 – 4500K)',
			15 => 'White fluorescent (WW 3200 – 3700K)',
			17 => 'Standard light A',
			18 => 'Standard light B',
			19 => 'Standard light C',
			20 => 'D55',
			21 => 'D65',
			22 => 'D75',
			23 => 'D50',
			24 => 'ISO studio tungsten',
			255 => 'Other'
		),
		'EXIF_Flash' => array(
			'Name' => 'Flash Mode',
			0 => 'Flash did not fire',
			1 => 'Flash fired',
			5 => 'Strobe return light not detected',
			7 => 'Strobe return light detected',
			9 => 'Flash fired, compulsory flash mode',
			13 => 'Flash fired, compulsory flash mode, return light not detected',
			15 => 'Flash fired, compulsory flash mode, return light detected',
			16 => 'Flash did not fire, compulsory flash suppression mode',
			24 => 'Flash did not fire, auto mode',
			25 => 'Flash fired, auto mode',
			29 => 'Flash fired, auto mode, return light not detected',
			31 => 'Flash fired, auto mode, return light detected',
			32 => 'No flash function',
			65 => 'Flash fired, red-eye reduction mode',
			69 => 'Flash fired, red-eye reduction mode, return light not detected',
			71 => 'Flash fired, red-eye reduction mode, return light detected',
			73 => 'Flash fired, compulsory flash mode, red-eye reduction mode',
			77 => 'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected',
			79 => 'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected',
			89 => 'Flash fired, auto mode, red-eye reduction mode',
			93 => 'Flash fired, auto mode, return light not detected, red-eye reduction mode',
			95 => 'Flash fired, auto mode, return light detected, red-eye reduction mode'
		),
		'EXIF_FocalLength' => array(
			'Name' => 'Focal Length',
			'Units' => 'mm'
		),
		'EXIF_SensingMethod' => array(
			'Name' => 'Sensing Method',
			1 => 'Not defined',
			2 => 'One-chip colour area sensor',
			3 => 'Two-chip colour area sensor',
			4 => 'Three-chip colour area sensor',
			5 => 'Colour sequential area sensor',
			7 => 'Trilinear sensor',
			8 => 'Colour sequential linear sensor'
		),
		'EXIF_CustomRendered' => array(
			'Name' => 'Custom Renderd Mode',
			0 => 'Normal Process',
			1 => 'Custom Process',
		),
		'EXIF_ExposureMode' => array(
			'Name' => 'Exposure Mode',
			0 => 'Auto exposure',
			1 => 'Manual exposure',
			2 => 'Auto bracket'
		),
		'EXIF_WhiteBalance' => array(
			'Name' => 'White Balance',
			0 => 'Auto white balance',
			1 => 'Manual white balance'
		),
		'EXIF_DigitalZoomRatio' => array(
			'Name' => 'Digital Zoom Ratio',
			'Units' => ' ( Zero = Digital Zoom Not Used )'
		),
		'EXIF_FocalLengthIn35mmFilm' => array(
			'Name' => 'Equivalent Focal Length In 35mm Film',
			'Units' => 'mm'
		),
		'EXIF_SceneCaptureType' => array(
			'Name' => 'Scene Capture Type',
			0 => 'Standard',
			1 => 'Landscape',
			2 => 'Portrait',
			3 => 'Night scene'
		),
		'EXIF_GainControl' => array(
			'Name' => 'Gain Control',
			0 => 'None',
			1 => 'Low gain up',
			2 => 'High gain up',
			3 => 'Low gain down',
			4 => 'High gain down'
		),
		'EXIF_Contrast' => array(
			'Name' => 'Contrast',
			0 => 'Normal',
			1 => 'Soft',
			2 => 'Hard'
		),
		'EXIF_Saturation' => array(
			'Name' => 'Saturation',
			0 => 'Normal',
			1 => 'Low saturation',
			2 => 'High saturation'
		),
		'EXIF_Sharpness' => array(
			'Name' => 'Sharpness',
			0 => 'Normal',
			1 => 'Soft',
			2 => 'Hard'
		),
		'EXIF_SubjectDistanceRange' => array(
			'Name' => 'Subject Distance Range',
			0 => 'Unknown',
			1 => 'Macro',
			2 => 'Close view',
			3 => 'Distant view'
		),
	);

	$i = 0;
	$rexif = array();

	while (!empty($xkey[$i]) )
	{
		if ( ereg("([0-9]{1,})/([0-9]{1,})", $xval[$i], $num) )
		{
			if ( $num[1] > 1 ) $xval[$i] = round( ($num[1] / $num[2]), 6);
		}
		if ( is_array($exif_info[$xkey[$i]]) && $xkey[$i] != 'IFD0_ResolutionUnit' )
		{
			if ( isset($exif_info[$xkey[$i]]['Units']) )
			{
				if ( $xkey[$i+2] == 'IFD0_ResolutionUnit' )
				{
					$rexif[$exif_info[$xkey[$i]]['Name']] = ($xval[$i] . $exif_info[$xkey[$i]]['Units'] . $exif_info[$xkey[$i+2]][$xval[$i+2]]);
				}
				else if ( $xkey[$i+1] == 'IFD0_ResolutionUnit' )
				{
					$rexif[$exif_info[$xkey[$i]]['Name']] = ($xval[$i] . $exif_info[$xkey[$i]]['Units'] . $exif_info[$xkey[$i+1]][$xval[$i+1]]);
				}
				else
				{
					$rexif[$exif_info[$xkey[$i]]['Name']] = ($xval[$i] . $exif_info[$xkey[$i]]['Units']);
				}
			}
			else
			{
				$rexif[$exif_info[$xkey[$i]]['Name']] = $exif_info[$xkey[$i]][$xval[$i]];
			}
		}
		else if ( isset($exif_info[$xkey[$i]]) && $xkey[$i] != 'IFD0_ResolutionUnit' )
		{
			$rexif[$exif_info[$xkey[$i]]] = $xval[$i];
			if ( $xkey[$i] == 'FILE_FileDateTime' )
			{
				$rexif[$exif_info[$xkey[$i]]] = create_date('Y:m:d H:i:s',  $xval[$i], $config['board_timezone']);
			}
		}
		$i++;
	}
	return $rexif;
}

$template->assign_block_vars('switch_exif_enabled.exif_switch', array());
$i = 0;
$xkey = array();
$xval = array();

while (list($xk1,$xv1) = each($xif))
{
	$xkey[$i] = $xk1;
	$xval[$i] = $xv1;
	if (is_array($xif[$xk1]))
	{
		while (list($xk2,$xv2) = each($xif[$xk1]))
		{
			$xkey[$i] = $xk1.'_'.$xk2;
			$xval[$i] = $xv2;
			$i++;
		}
	}
	else
	{
		$i++;
	}
}

$exif = make_exif($xkey, $xval);
$i = 0;
$key = array();
$val = array();

while (list($k1,$v1) = each($exif))
{
	$key[$i] = $k1;
	$val[$i] = $v1;
	$i++;
}

$x = intval(($i/2)+.5);

for ($n = 0; $n < $x; $n++)
{
	$template->assign_block_vars('switch_exif_enabled.exif_switch.exif_data', array(
		'EXIFc1' => (!empty($key[$n])) ? $key[$n].':' : '',
		'EXIFd1' => '&nbsp;'.$val[$n],
		'EXIFc2' => (!empty($key[$n+$x])) ? $key[$n+$x].':' : '',
		'EXIFd2' => '&nbsp;'.$val[$n+$x]
		)
	);
}

?>