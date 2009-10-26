<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip, false);
init_userprefs($userdata);
// End session management

$smiley_creator_path = IP_ROOT_PATH . 'images/smiles/smiley_creator/';
$sm_fontcolor = request_var('fontcolor', '#000000');
$sm_shadowcolor = request_var('shadowcolor', '#000000');
$sm_shadow = request_var('shieldshadow', 0);
$smilie = request_var('smilie', 1);
$text = urldecode(request_var('text', ''));

$anz_smilie = -1;
$hdl = opendir($smiley_creator_path);
while($res = readdir($hdl))
{
	if(strtolower(substr($res, (strlen($res) - 3), 3)) == 'png')
	{
		$anz_smilie++;
	}
}
closedir($hdl);


if($phpversion_nr >= 4.30)
{
	$gd_info = gd_info();
}
else
{
	$gd_info['FreeType Support'] = 1;
}

if((!$gd_info['FreeType Support']) || (!file_exists($schriftdatei)))
{
	$schriftwidth = 6;
	$schriftheight = 8;
}
else
{
	if((!$schriftheight) || (!$schriftwidth))
	{
		$schriftwidth = imagefontwidth($schriftdatei);
		$schriftheight = imagefontheight($schriftdatei);
	}
}
$schriftheight += 2;


$text = stripslashes($text);
$text = str_replace('&lt;', '<', $text);
$text = str_replace('&gt;', '>', $text);

while(substr_count($text, '<'))
{
	$text = @ereg_replace(substr($text, strpos($text, '<'), (strpos($text, '>') - strpos($text, '<') + 1)), '', $text);
}

if(!$text)
{
	$text = $lang['SC_error'];
}

if(strlen($text) > 33)
{
	$worte = split(' ', $text);

	if(is_array($worte))
	{
		$i = 0;
		foreach($worte as $wort)
		{
			if((strlen($output[$i] . ' ' . $wort) < 33) && (!substr_count($wort, "[SM")))
			{
				$output[$i] .= ' ' . $wort;
			}
			else
			{
				if($i <= 11)
				{
					if($zeichenzahl < strlen($output[$i]))
					{
						$zeichenzahl = strlen($output[$i]);
					}
					$i++;
					$output[$i] = $wort;
				}
			}
		}
	}
	else
	{
		$zeichenzahl = 33;
		$output[0] = substr($text, 0, 30) . '...';
	}
}
else
{
	$zeichenzahl = strlen($text);
	$output[0] = $text;
}

if(sizeof($output) > 12)
{
	$output[12] = substr($output[12], 0, 30) . '...';
}

$width = ($zeichenzahl * $schriftwidth) + 6;
$height = (sizeof($output) * $schriftheight) + 34;

if($width < 60)
{
	$width = 60;
}

mt_srand((double) microtime() * 3216549);
if($smilie == 'random')
{
	$smilie = mt_rand(1, $anz_smilie);
}
if(!$smilie)
{
	$smilie = mt_rand(1, $anz_smilie);
}


$smilie = imagecreatefrompng($smiley_creator_path . 'smilie' . $smilie . '.png');
$schild = imagecreatefrompng($smiley_creator_path . 'schild.png');
$img = imagecreate($width,$height);

$bgcolor = imagecolorallocate ($img, 111, 252, 134);
$txtcolor = imagecolorallocate ($img, hexdec(substr(str_replace('#', '', $sm_fontcolor), 0, 2)), hexdec(substr(str_replace('#', '', $sm_fontcolor), 2, 2)), hexdec(substr(str_replace('#', '', $sm_fontcolor), 4, 2)));
$txt2color = imagecolorallocate ($img, hexdec(substr(str_replace('#', '', $sm_shadowcolor), 0, 2)), hexdec(substr(str_replace('#', '', $sm_shadowcolor), 2, 2)), hexdec(substr(str_replace('#', '', $sm_shadowcolor), 4, 2)));
$bocolor = imagecolorallocate ($img, 0, 0, 0);
$schcolor = imagecolorallocate ($img, 255, 255, 255);
$schatten1color = imagecolorallocate ($img, 235, 235, 235);
$schatten2color = imagecolorallocate ($img, 219, 219, 219);

$smiliefarbe = imagecolorsforindex($smilie, imagecolorat($smilie, 5, 14));

imagesetpixel($schild, 1, 14, imagecolorallocate($schild, ($smiliefarbe['red'] + 52), ($smiliefarbe['green'] + 59), ($smiliefarbe['blue'] + 11)));
imagesetpixel($schild, 2, 14, imagecolorallocate($schild, ($smiliefarbe['red'] + 50), ($smiliefarbe['green'] + 52), ($smiliefarbe['blue'] + 50)));
imagesetpixel($schild, 1, 15, imagecolorallocate($schild, ($smiliefarbe['red'] + 50), ($smiliefarbe['green'] + 52), ($smiliefarbe['blue'] + 50)));
imagesetpixel($schild, 2, 15, imagecolorallocate($schild, ($smiliefarbe['red'] + 22), ($smiliefarbe['green'] + 21), ($smiliefarbe['blue'] + 35)));
imagesetpixel($schild, 1, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 2, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 5, 16, imagecolorallocate($schild, ($smiliefarbe['red'] + 22), ($smiliefarbe['green'] + 21), ($smiliefarbe['blue'] + 35)));
imagesetpixel($schild, 6, 16, imagecolorat($smilie, 5, 14));
imagesetpixel($schild, 5, 15, imagecolorallocate($schild, ($smiliefarbe['red'] + 52), ($smiliefarbe['green'] + 59), ($smiliefarbe['blue'] + 11)));
imagesetpixel($schild, 6, 15, imagecolorallocate($schild, ($smiliefarbe['red'] + 50), ($smiliefarbe['green'] + 52), ($smiliefarbe['blue'] + 50)));


imagecopy($img, $schild, ($width / 2 - 3), 0, 0, 0, 6, 4); // Bildteil kopieren
imagecopy($img, $schild, ($width / 2 - 3), ($height - 24), 0, 5, 9, 17); // Bildteil kopieren
imagecopy($img, $smilie, ($width / 2 + 6), ($height - 24), 0, 0, 23, 23); // Bildteil kopieren

imagefilledrectangle($img, 0, 4, $width, ($height - 25), $bocolor);
imagefilledrectangle($img, 1, 5, ($width - 2), ($height - 26), $schcolor);

if($sm_shadow == true)
{
	imagefilledpolygon($img, array((($width - 2) / 2 + ((($width - 2) / 4) - 3)), 5, (($width - 2) / 2 + ((($width - 2) / 4) + 3)), 5, (($width - 2) / 2 - ((($width - 2) / 4) - 3)), ($height - 26), (($width - 2) / 2 - ((($width - 2) / 4) + 3)), ($height - 26)), 4, $schatten1color);
	imagefilledpolygon($img, array((($width - 2) / 2 + ((($width - 2) / 4) + 4)), 5, ($width - 2), 5, ($width - 2), ($height - 26), (($width - 2) / 2 - ((($width - 2) / 4) - 4)), ($height - 26)), 4, $schatten2color);
}

$i = 0;
while($i < sizeof($output))
{
	if(((!$gd_info['FreeType Support']) || (!file_exists($schriftdatei))))
	{
		if($sm_shadowcolor)
		{
			imagestring($img, 2, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2 + 1), ($i * $schriftheight + 6), trim($output[$i]), $txt2color);
		}
		imagestring($img, 2, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2), ($i * $schriftheight + 5), trim($output[$i]), $txtcolor);
	}
	else
	{
		if($sm_shadowcolor)
		{
			imagettftext($img, $schriftheight, 0, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2 + 1), ($i * $schriftheight + $schriftheight + 4), $txt2color, $schriftdatei, trim($output[$i]));
		}
		imagettftext($img, $schriftheight, 0, (($width - (strlen(trim($output[$i])) * $schriftwidth) - 2) / 2), ($i * $schriftheight + $schriftheight + 3), $txtcolor, $schriftdatei, trim($output[$i]));
	}
	$i++;
}

imagecolortransparent($img, $bgcolor);  // Dummybg als transparenz setzen
imageinterlace($img, 1);

header('Content-Type: image/png');
Imagepng($img);   // 100 = komprimierung
//Imagepng($img,'',100);   // 100 = komprimierung
ImageDestroy($img);
ImageDestroy($schild);
ImageDestroy($smilie);

?>