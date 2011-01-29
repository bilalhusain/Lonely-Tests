<?
	session_start();

	$img=imagecreatefromjpeg("texture.jpg");
	$image_text = empty($_SESSION['security_number']) ? ':)' : $_SESSION['security_number'];
 
        $red=rand(100,255);
	$green=rand(200,255);
	$blue=rand(100,255);
 
	$text_color=imagecolorallocate($img,255-$red,255-$green,255-$blue);
 
	$text=imagettftext($img, 12, rand(-10,10), rand(5,15), rand(25,45), $text_color,
                 "comic.ttf", $image_text);
 
	header("Content-type:image/jpeg");
	header("Content-Disposition:inline ; filename=captcha.jpg");
	imagejpeg($img);
?>
