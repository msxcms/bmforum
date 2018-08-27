<?php 
function makethumb($uploadfile, $dstFile) {
	global $bmfopt, $watermark_err, $lang_wm;
	eval(load_hook('int_markpic_makethumb'));
	$lang_wm = explode("|", $lang_wm);
	
	$waterimg = "images/watermark.png";
	if (!is_file($waterimg)) {
		$watermark_err=$lang_wm[1];
		return false;
	}
	if (!function_exists('getimagesize')) {
		$watermark_err=$lang_wm[2];
		return false;
	}
	if (version_compare(PHP_VERSION, "4.3.0") < 0) {
		$watermark_err=$lang_wm[3];
		return false;
	}
	if (version_compare(PHP_VERSION, "4.4.4") < 0 || mark_is_ani($uploadfile)) {
		$not_gif = 1;
	}
	$upload_info=@getimagesize($uploadfile);
	if (!$upload_info[0] || !$upload_info[1]) {
		return false;
	}
	$watermark_size=explode('x', strtolower($bmfopt['wmsize']));
	if ($upload_info[0]<$watermark_size[0] || $upload_info[1]<$watermark_size[1]) return $lang_wm[4];
	switch ($upload_info['mime']) {
		case 'image/jpeg':
			$tmp=@imagecreatefromjpeg($uploadfile);
			break;
		case 'image/gif':
			if (!function_exists('imagecreatefromgif') || $not_gif == 1) {
				$watermark_err=$lang_wm[5];
				return false;				
			} else $tmp=@imagecreatefromgif($uploadfile);
			break;
		case 'image/png':
			$tmp=@imagecreatefrompng($uploadfile);
			break;
		default:
			$watermark_err=$lang_wm[6];
			return false;				
	}
	$marksize=@getimagesize($waterimg);
	$width=$marksize[0];
	$height=$marksize[1];
	$pos_padding=($bmfopt['wmpadding'] && $bmfopt['wmpadding']>0) ? $bmfopt['wmpadding'] : 5; 	//Padding
	switch ($bmfopt['wmposition']) {
		// right-bottom
		case '0':
			$pos_x=$upload_info[0]-$width-$pos_padding;
			$pos_y=$upload_info[1]-$height-$pos_padding;
			break;
		// left-top
		case '1': 
			$pos_x=$pos_padding;
			$pos_y=$pos_padding;
			break;
		// left-bottom
		case '2':
			$pos_x=$pos_padding;
			$pos_y=$upload_info[1]-$height-$pos_padding;
			break;
		// right-top
		case '3':
			$pos_x=$upload_info[0]-$width-$pos_padding;
			$pos_y=$pos_padding;
			break;
		// mid
		case '4':
			$pos_x=($upload_info[0]-$width)/2;
			$pos_y=($upload_info[1]-$height)/2;
			break;
		// random
		default:
			$pos_x=rand(0,($upload_info[0]-$width));
			$pos_y=rand(0,($upload_info[1]-$height));
			break;
	}
	if($imgmark=@imagecreatefrompng($waterimg)) {
		if ($bmfopt['wmtrans']) {
			@imagecopymerge($tmp, $imgmark, $pos_x, $pos_y, 0, 0, $width, $height, $bmfopt['wmtrans']);
		} else {
			@imagecopy($tmp, $imgmark, $pos_x, $pos_y, 0, 0, $width, $height);
		}
	}
	switch ($upload_info['mime']) {
		case 'image/jpeg':
			@imagejpeg($tmp,$dstFile,80);
			@imagedestroy($tmp);
			break;
		case 'image/gif':
			@imagegif($tmp,$dstFile);
			@imagedestroy($tmp);
			break;
		case 'image/png':
			@imagepng($tmp,$dstFile);
			@imagedestroy($tmp);
			break;
		default :
			return;
	}
	return $lang_wm[7];
}
function mark_is_ani($filename)
{
	$filecontents = readfromfile($filename);

	$str_loc = 0;
	$count = 0;
	while ($count < 2) {
		$where1 = strpos($filecontents, "\x00\x21\xF9\x04", $str_loc);
		if ($where1 === FALSE)	{
			break;
		} else {
			$str_loc = $where1+1;
			$where2 = strpos($filecontents, "\x00\x2C", $str_loc);
			if ($where2 === FALSE) break;
			else {
				if ($where1+8 == $where2) {
					$count++;
				}
				$str_loc = $where2+1;
			}
		}
	}

	if ($count > 1) return 1;
		else return 0;
}

?>