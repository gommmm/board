<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function getResizeSrc($content) {
  if($content == '')
      return false;

  $img = getImagePath($content);

  if($img === false) return false;

  $img['path'] = $img['path'].'resize/';
  $img['filename'] = 'resize_'.$img['filename'];

  $img['filename'] = str_replace('bmp', 'jpg', $img['filename']);
  $src = $img['http'].$img['server'].$img['path'].$img['filename'];

  return $src;
}

function getImagePath($str) { // board 테이블의 content 내용에서 img 태그가 있으면 첫번째 img태그의 파일 경로와 파일 이름을 추출해줌.
  $pattern = '@<img [a-z=]*"(http:\/\/)?('.MAIN_URL.')?([a-z0-9.\/]*\/)?([a-z0-9.ㄱ-ㅎㅏ-ㅣ가-힣\s()_]+)" title="[a-z0-9.ㄱ-ㅎㅏ-ㅣ가-힣\s()_]+">@i';
  preg_match($pattern, $str, $matches);

  if(empty($matches)) return false;

  $img = [];
  list(, $img['http'], $img['server'], $img['path'], $img['filename']) = $matches;

  return $img;
}

function resizeImage($imgPath, $resizeImgPath, $resizeRate)
{
    if($resizeRate <= 0 || $resizeRate > 2) // - 배율은 문제가 있고, 2배 이상이면 너무 커지므로 미리 방지
        return false;

    $imgInfo = [];

    list($imgInfo['width'], $imgInfo['height'], $imgInfo['type']) = getimagesize($imgPath);

    if(empty($imgInfo)) // 이미지가 아니면
        return false;

    if($imgInfo['type'] !== 6 && ($imgInfo['type'] < 1 || $imgInfo['type'] > 3)) // 타입이 jpg, gif, png, bmp가 아니면
        return false;

    $resizeWidth = $imgInfo['width'] * $resizeRate;
    $resizeHeight = $imgInfo['height'] * $resizeRate;

    if ($imgInfo['type'] == 1) { // 이미지 타입에 따라 다른메소드를 불러와서 캔버스를 만들어야 됨.
        $img = imagecreatefromgif($imgPath);
    } else if ($imgInfo['type'] == 2) {
        $img = imagecreatefromjpeg($imgPath);
    } else if ($imgInfo['type'] == 3) {
        $img = imagecreatefrompng($imgPath);
    } else if ($imgInfo['type'] == 6) {
        $img = imagecreatefrombmp($imgPath);
    }

    $resizeImg = imagecreatetruecolor($resizeWidth, $resizeHeight);

    imagecopyresized($resizeImg, $img, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $imgInfo['width'], $imgInfo['height']);

    if ($imgInfo['type'] == 1) { // 이미지 타입에 따라 다른메소드를 불러와서 각각의 타입에 맞게 이미지를 생성함.
        imagegif($resizeImg, $resizeImgPath);
    } else if ($imgInfo['type'] == 2 || $imgInfo['type'] == 6) { // bmp 타입은 따로 지원되는 메소드가 없어서 jpg 파일로 처리했다.
        imagejpeg($resizeImg, $resizeImgPath, 80);
    } else if ($imgInfo['type'] == 3) {
        imagepng($resizeImg, $resizeImgPath);
    }

    return true;
}

function ConvertBMP2GD($src, $dest = false) {
    if(!($src_f = fopen($src, "rb"))) {
        return false;
    }

    if(!($dest_f = fopen($dest, "wb"))) {
        return false;
    }

    $header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f, 14));
    $info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vimportant",

    fread($src_f, 40));
    extract($info);
    extract($header);

    if($type != 0x4D42) { // signature "BM"
        return false;
    }

    $palette_size = $offset - 54;
    $ncolor = $palette_size / 4;
    $gd_header = "";
    // true-color vs. palette
    $gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
    $gd_header .= pack("n2", $width, $height);
    $gd_header .= ($palette_size == 0) ? "\x01" : "\x00";

    if($palette_size) {
        $gd_header .= pack("n", $ncolor);
    }
    // no transparency
    $gd_header .= "\xFF\xFF\xFF\xFF";

    fwrite($dest_f, $gd_header);

    if($palette_size) {
        $palette = fread($src_f, $palette_size);
        $gd_palette = "";
        $j = 0;

        while($j < $palette_size) {
            $b = $palette{$j++};
            $g = $palette{$j++};
            $r = $palette{$j++};
            $a = $palette{$j++};
            $gd_palette .= "$r$g$b$a";
        }

        $gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
        fwrite($dest_f, $gd_palette);
    }

    $scan_line_size = (($bits * $width) + 7) >> 3;
    $scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;

    for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
        // BMP stores scan lines starting from bottom
        fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
        $scan_line = fread($src_f, $scan_line_size);

        if($bits == 24) {
            $gd_scan_line = "";
            $j = 0;

            while($j < $scan_line_size) {
                $b = $scan_line{$j++};
                $g = $scan_line{$j++};
                $r = $scan_line{$j++};
                $gd_scan_line .= "\x00$r$g$b";
            }
        } else if ($bits == 8) {
            $gd_scan_line = $scan_line;
        } else if($bits == 4) {
            $gd_scan_line = "";
            $j = 0;

            while($j < $scan_line_size) {
                $byte = ord($scan_line{$j++});
                $p1 = chr($byte >> 4);
                $p2 = chr($byte & 0x0F);
                $gd_scan_line .= "$p1$p2";
            }

            $gd_scan_line = substr($gd_scan_line, 0, $width);
        } else if ($bits == 1) {
            $gd_scan_line = "";
            $j = 0;

            while($j < $scan_line_size) {
                $byte = ord($scan_line{$j++});
                $p1 = chr((int) (($byte & 0x80) != 0));
                $p2 = chr((int) (($byte & 0x40) != 0));
                $p3 = chr((int) (($byte & 0x20) != 0));
                $p4 = chr((int) (($byte & 0x10) != 0));
                $p5 = chr((int) (($byte & 0x08) != 0));
                $p6 = chr((int) (($byte & 0x04) != 0));
                $p7 = chr((int) (($byte & 0x02) != 0));
                $p8 = chr((int) (($byte & 0x01) != 0));
                $gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
           }

           $gd_scan_line = substr($gd_scan_line, 0, $width);
        }

        fwrite($dest_f, $gd_scan_line);
    }

    fclose($src_f);
    fclose($dest_f);

    return true;
}

// 자체 메소드가 제공되지 않아 다른 사용자가 올려놓은 메소드를 가져다 썼다. ConverBMP2GD 메소드와 같이 있어야 된다.
function imagecreatefrombmp($filename) {
    $tmp_name = tempnam("/tmp", "GD");

    if(ConvertBMP2GD($filename, $tmp_name)) {
        $img = imagecreatefromgd($tmp_name);
        unlink($tmp_name);
        return $img;
    }

    return false;
}
