<?php if (isset($_GET['dir'])){
	$imgNo = $_GET['srcNo'];
	$dir = $_GET['dir'];
	if ($dir == "prev"){
		$imgNo--;
		//echo "descending";
	} else {
		$imgNo++;
		//echo $_GET['dir'];
	}
	$im = file_get_contents("/home/pi/timelapsePhotos/piikuva_".$imgNo.".jpg");
	$base64Img = base64_encode($im);
	$filetime = filectime("/home/pi/timelapsePhotos/piikuva_".$imgNo.".jpg");
	$retTime = date('F d Y h:i A', $filetime);
	//$filepath = {$imageSrc}.{$entry};
	//$answer = "data:image/jpg;base64,".base64_encode($im).;
	$retArr = array('img' => $base64Img, 'name' => "piikuva_".$imgNo.".jpg", 'ctime' => $retTime );
	//echo $base64Img;
	echo json_encode($retArr);
} else {
	echo "not for you my friend";
}


?>
