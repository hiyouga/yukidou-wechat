<?php
error_reporting(0);
header("Content-type;text/html; charset=utf-8");
require_once 'database.php';
if($_POST){
	if($_POST['name']){
		$name = $_POST['name'];
		$firstch = getFirstCharter($name);
	}else{
		die("<p style=\"text-align: center;color: #f00;\">请输入漫画名称！</p>");
	}
	if($_POST['author']){
		$author = $_POST['author'];
	}else{
		$author = 'unknown';
	}
	$team = $_POST['team'];
	if($_POST['raw']){
		preg_match("#http[^  ]+#", $_POST['raw'], $rawsl);
		$sharelink = $rawsl[0];
		preg_match("#[a-zA-z0-9]+$#", $_POST['raw'], $rawps);
		$password = $rawps[0];
	}
	if((!empty($_POST['sharelink']))&&(!empty($_POST['password']))){
		$sharelink = $_POST['sharelink'];
		$password = $_POST['password'];
	}
	if(empty($sharelink)||empty($password)){
		die("<p style=\"text-align: center;color: #f00;\">链接输入有误或匹配失败，请尝试备用输入</p>");
	}
	$sql = "INSERT INTO netdisk (name, author, team, sharelink, password, firstch) VALUES ('$name','$author','$team','$sharelink','$password','$firstch')";
	//die($sql);
	$res = mysqli_query($link, $sql);
}
mysqli_close($link);

function getFirstCharter($str)
{
	if (empty($str)) {
		return '';
	}
	$fchar = ord($str{0});
	if ($fchar >= ord('A') && $fchar <= ord('z'))
		return strtoupper($str{0});
	/*$s1 = iconv('UTF-8', 'gb2312', $str);
	$s2 = iconv('gb2312', 'UTF-8', $s1);
	$s = $s2 == $str ? $s1 : $str;*/
	$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')); 
	$s = mb_convert_encoding($str, 'GB2312', $encode);
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if ($asc >= -20319 && $asc <= -20284)
		return 'A';
	if ($asc >= -20283 && $asc <= -19776)
		return 'B';
	if ($asc >= -19775 && $asc <= -19219)
		return 'C';
	if ($asc >= -19218 && $asc <= -18711)
		return 'D';
	if ($asc >= -18710 && $asc <= -18527)
		return 'E';
	if ($asc >= -18526 && $asc <= -18240)
		return 'F';
	if ($asc >= -18239 && $asc <= -17923)
		return 'G';
	if ($asc >= -17922 && $asc <= -17418)
		return 'H';
	if ($asc >= -17417 && $asc <= -16475)
		return 'J';
	if ($asc >= -16474 && $asc <= -16213)
		return 'K';
	if ($asc >= -16212 && $asc <= -15641)
		return 'L';
	if ($asc >= -15640 && $asc <= -15166)
		return 'M';
	if ($asc >= -15165 && $asc <= -14923)
		return 'N';
	if ($asc >= -14922 && $asc <= -14915)
		return 'O';
	if ($asc >= -14914 && $asc <= -14631)
		return 'P';
	if ($asc >= -14630 && $asc <= -14150)
		return 'Q';
	if ($asc >= -14149 && $asc <= -14091)
		return 'R';
	if ($asc >= -14090 && $asc <= -13319)
		return 'S';
	if ($asc >= -13318 && $asc <= -12839)
		return 'T';
	if ($asc >= -12838 && $asc <= -12557)
		return 'W';
	if ($asc >= -12556 && $asc <= -11848)
		return 'X';
	if ($asc >= -11847 && $asc <= -11056)
		return 'Y';
	if ($asc >= -11055 && $asc <= -10247)
		return 'Z';
	return null;
}
?>
<!doctype html>
<html lang="zh">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="author" content="hoshi_hiyouga, hiyouga#buaa.edu.cn" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>添加漫画 | 祐希堂汉化组</title>
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
			line-height: 300%;
			width: 90%;
			max-width: 700px;
			text-align: center;
		}
		input[type="text"], select{
			width: 80%;
		}
		input[type="submit"]{
			color: #fff;
			display: inline-block;
			background-color: #03a9f4;
			cursor: pointer;
			height: 35px;
			line-height: 35px;
			padding: 0 1.5rem;
			font-size: 15px;
			font-weight: 0;
			font-family: 'Microsoft Yahei', 'Roboto', sans-serif;
			letter-spacing: .8px;
			text-decoration: none;
			text-transform: uppercase;
			vertical-align: middle;
			white-space: nowrap;
			outline: none;
			border: none;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			border-radius: 2px;
			-webkit-transition: all .3s ease-out;
			transition: all .3s ease-out;
			box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.225);
		}
		input[type="submit"]:hover{
			background-color: #23b9fc;
			text-decoration: none;
			box-shadow: 0 4px 10px 0px rgba(0, 0, 0, 0.225);
		}
		#bak{
			font-size: 0.9rem;
			margin: 20px auto;
			width: 70%;
			line-height: 200%;
			border: 1px #000 solid;
			padding: 15px;
		}
	</style>
</head>
<body>
	<main>
		<form method="post" action="">
			<span>漫画名称：（中英日文名称请以空格分隔）</span><br />
			<input type="text" name="name" placeholder="搭档Buddy×Body 我的搭档是软妹 バディ!!!" /><br />
			<span>作者名：（多名作者请以空格分隔）</span><br />
			<input type="text" name="author" placeholder="西园フミコ" /><br />
			<span>汉化组名：</span><br />
			<select name="team">
				<option value="祐希堂汉化组" selected="selected">祐希堂汉化组</option>
				<option value="九十九汉化组">九十九汉化组</option>
				<option value="小叶后宫汉化组">小叶后宫汉化组</option>
				<option value="映像">映像</option>
			</select><br />
			<span>分享链接：（只支持私密分享链接）</span><br />
			<input type="text" name="raw" placeholder="链接: https://pan.baidu.com/s/******** 密码: ****" />
			<div id="bak">
				<span>备用：（仅在匹配失败时使用）</span><br />
				下载链接：<input type="text" name="sharelink" placeholder="https://pan.baidu.com/s/********" /><br />
				提取密码：<input type="text" name="password" placeholder="****" />
			</div>
			<input type="submit" value="提交" />
		</form>
	</main>
</body>
</html>