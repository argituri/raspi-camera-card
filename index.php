
<HTML>
<title>Raspi Camera</title>
<HEAD>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>
function changePhoto(dir){
	console.log("going for " + dir);
	console.log("imgNo attr : " +  $("#cardfoto").attr("imgnumber"));
	var srcNo = $("#cardfoto").attr("imgnumber");
	console.log("Elements srcNo : " + srcNo)
	datas = {
		'dir': dir,
		'srcNo': srcNo
		}
	$.ajax({
		'url':'imgChange.php',
		'contentType': 'Application/json',
		'data': datas
		}).then((result) => {
			//console.log("typeof result : " + typeof result)
			//console.log("ready with " + result)
			//console.log("this is called : " + result.name);
			//console.log("date: " + result.ctime);
			//console.log("img : " + result.img)
			result = JSON.parse(result)
			$("#cardfoto").attr("src", "data:image/jpg;base64," + result.img)
			$("#filename").text(result.name)
			$("#date").text(result.ctime)
	})
	if (dir=='prev'){
		srcNo--
	}
	if (dir=='next'){
		console.log("Going forward in fotos")
		srcNo++
	}
	$("#cardfoto").attr("imgnumber", srcNo);
}
</script>
</HEAD>
<BODY>

<?php

if (isset($_GET["name"])) {
	exec('python camera.py', $output, $result);
	sleep(2);
}


$imageSrc = '/home/pi/timelapsePhotos/';

$files = scandir($imageSrc, 0);
$newest_file = $files[0];


//get the lastest file uploaded in excel_uploads/
$latest_ctime = 0;
$newest_file = '';
$d = dir($imageSrc);
while (false !== ($entry = $d->read())) {
$filepath = "{$imageSrc}/{$entry}";
//Check whether the entry is a file etc.:
    if(is_file($filepath) && filectime($filepath) > $latest_ctime) {
    	$latest_ctime = filectime($filepath);
    	$newest_file = $entry;
    }
}


$newest_path = $imageSrc . $newest_file;
$dispDate = date('F d Y h:i A', $latest_ctime);

$dotPos = strpos($newest_file, ".");
$underPos = strrpos($newest_file, "_")+1;
$biggest_no = substr($newest_file, $underPos, $dotPos-$underPos);

//echo "<p>strpos of  . in {$newest_file} : {$pos} </p>";
//echo "<p>Biggest No: {$biggest_no}</p>";
//echo "<p>Newest Image: {$newest_file} </p>";
//echo "<p>Is from : {$dispDate} </p>";
//echo "<p>Full Path : {$newest_path} </p>";
//$root = $_SERVER['DOCUMENT_ROOT'];

$im = file_get_contents($newest_path);

//echo "<img src='data:image/jpg;base64,".base64_encode($im)."'></img>";

?>

<div class="card" style="width: 50%;">
  <?php echo "<img src='data:image/jpg;base64,".base64_encode($im)."' class=\"card-img-top\" fileName=\"$newest_path\" id=\"cardfoto\" imgnumber='{$biggest_no}' ></img>";
?>
  <div class="card-body">
    <h5 class="card-title" id="filename"><?php echo $newest_file ?></h5>
    <p class="card-text" id="date"><?php echo $dispDate ?></p>
    <button type="button" onclick="changePhoto('prev')" class="btn btn-outline-info" id="prev">Previous</button>
    <a href="/?name=true" name="foto" class="btn btn-primary">Take a photo</a>
    <button type="button" onClick="changePhoto('next')" class="btn btn-outline-info" id="next">Next</button> 
  </div>
</div>


</BODY>
</HTML>
