<!doctype html>
<html lang="zh">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="author" content="hoshi_hiyouga, hiyouga#buaa.edu.cn" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>漫画列表 | 祐希堂汉化组</title>
	<style type="text/css">
		body{
			text-align: center;
			height: 100%;
			width: 100%;
			margin: 0;
			padding: 0;
			font-size: 1.1rem;
		}
		main{
			margin: 0 auto;
			width: 90%;
			max-width: 700px;
			text-align: left;
			line-height: 150%;
		}
		ol{
			/*list-style: none;*/
			padding: 0;
		}
		li:hover{
			text-decoration: underline;
			cursor: pointer;
		}
		#cp{
			position: absolute;
			top: -9999px;
		}
	</style>
</head>
<body>
	<main>
		<p>点击即可复制名称</p>
		<ol id="list">
<?php
error_reporting(0);
require_once 'database.php';
switch($_GET['tp']){
	case 'firstch':
		$sql = "SELECT * FROM netdisk WHERE firstch = '" . $_GET['kw'] . "' ORDER BY firstch ASC";
		break;
	case 'team':
		$sql = "SELECT * FROM netdisk WHERE team = '" . urldecode($_GET['kw']) . "' ORDER BY firstch ASC";
		break;
	case 'author':
		$sql = "SELECT * FROM netdisk WHERE author LIKE '%" . urldecode($_GET['kw']) . "%' ORDER BY firstch ASC";
		break;
	default:
		$sql = "SELECT * FROM netdisk ORDER BY firstch ASC";
		break;
};
$res = mysqli_query($link, $sql);
$i = 1;
while($row = mysqli_fetch_assoc($res)){
	echo "<li id=\"list$i\" onclick=\"copy($i)\" data-text=\"".$row['name']."\">".stname($row['name'])."</li>";
	$i++;
}
mysqli_free_result($res);
mysqli_close($link);
function stname($str){
	$threshold = 50;
	if(mb_strlen($str) > $threshold){
		$str = mb_substr($str, 0, $threshold) . '…';
	}
	return $str;
}
?>
		</ol>
		<input id="cp" type="text" />
	</main>
	<script>
		function copy(i){
			var text = document.getElementById("list"+i).attributes["data-text"].value;
			var input = document.getElementById("cp");
			input.value = text;
			input.select();
			document.execCommand("copy");
			alert("复制成功");
		}
	</script>
</body>
</html>