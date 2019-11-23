<?php
error_reporting(0);

if(!empty($_GET['echostr'])){
    die($_GET['echostr']);
}

header('Content-type: text/xml; charset=utf-8');

$data = file_get_contents("php://input");

if(!empty($data)){
	createXML(parseXML($data));
}else{
	die('<error>None data posted!</error>');
}

function parseXML($xmltext){
	$parsed = array();
	$xml = new DOMDocument();
	$xml->loadXML($xmltext);
	$parsed['ToUserName'] = $xml->getElementsByTagName('ToUserName')[0]->nodeValue;
	$parsed['FromUserName'] = $xml->getElementsByTagName('FromUserName')[0]->nodeValue;
	$parsed['CreateTime'] = $xml->getElementsByTagName('CreateTime')[0]->nodeValue;
	$parsed['MsgType'] = $xml->getElementsByTagName('MsgType')[0]->nodeValue;
	$parsed['Content'] = $xml->getElementsByTagName('Content')[0]->nodeValue;
	$parsed['MsgId'] = $xml->getElementsByTagName('MsgId')[0]->nodeValue;
	return $parsed;
}

function createXML($elements){
	$dom = new DOMDocument('1.0', 'utf-8');
	
	$xml_main = $dom->appendChild(new DOMElement('xml'));
	$xml_ToUserName = $xml_main->appendChild(new DOMElement('ToUserName'));
	$xml_ToUserName->appendChild(new DOMCdataSection($elements['FromUserName']));
	$xml_FromUserName = $xml_main->appendChild(new DOMElement('FromUserName'));
	$xml_FromUserName->appendChild(new DOMCdataSection($elements['ToUserName']));
	$xml_CreateTime = $xml_main->appendChild(new DOMElement('CreateTime', time()));
	$xml_MsgType = $xml_main->appendChild(new DOMElement('MsgType'));
	$xml_MsgType->appendChild(new DOMCdataSection('text'));
	$xml_Content = $xml_main->appendChild(new DOMElement('Content'));
	$xml_Content->appendChild(new DOMCdataSection(searchLink($elements['Content'])));

	print $dom->saveXML();
}

function searchLink($keywords){
	require_once 'database.php';
	if(empty($keywords)){
	    return -1;
	}else{
		//首字母
		if(strlen($keywords) == 1 && ord($keywords{0}) >= ord('A') && ord($keywords{0}) <= ord('z')){
			$firstch = strtoupper($keywords{0});
			$sql = "SELECT * FROM netdisk WHERE firstch = '$firstch'";
			$res = mysqli_query($link, $sql);
			$match = "首字母为 $firstch 的部分漫画列表：\n";
			$i = 1;
			while(($row = mysqli_fetch_assoc($res)) && $i <= 5){
				$match .= $i.'.'.stname($row['name'])."\n";
				$i++;
			}
			$match .= "详细目录请见：\nhttp://www.hiyouga.top/html/weixin/list.php?tp=firstch&kw=". $keywords;
			mysqli_free_result($res);
			return $match;
		}
		//汉化组
		$sql = "SELECT * FROM netdisk WHERE team = '$keywords'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res)){
			$blogurl = array('祐希堂汉化组' => 'http://www.lofter.com/lpost/1f33bd40_12637a63', '九十九汉化组' => 'http://tsukumokami.lofter.com/', '映像' => 'http://girigirioculus.lofter.com/');
			$match = "$keywords 的部分汉化作品目录：\n";
			$u = 5;
			while(($row = mysqli_fetch_assoc($res)) && $u){
				$match .= stname($row['name'])."\n";
				$u--;
			}
			$match .= "汉化组博客地址：\n" . $blogurl[$keywords] . "\n";
			$match .= "详细目录请见：\nhttp://www.hiyouga.top/html/weixin/list.php?tp=team&kw=". urlencode($keywords);
			mysqli_free_result($res);
			return $match;
		}
		//作者
		$sql = "SELECT * FROM netdisk WHERE author LIKE '%$keywords%'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res)){
			$match = "$keywords 的部分作品目录：\n";
			$u = 5;
			while(($row = mysqli_fetch_assoc($res)) && $u){
				$match .= stname($row['name'])."\n";
				$u--;
			}
			$match .= "详细目录请见：\nhttp://www.hiyouga.top/html/weixin/list.php?tp=author&kw=". urlencode($keywords);
			mysqli_free_result($res);
			return $match;
		}
		//漫画
		$sql = "SELECT * FROM netdisk WHERE name LIKE '%$keywords%'";
		$res = mysqli_query($link, $sql);
		if(mysqli_num_rows($res)){
			$match = "查询到\n";
			while($row = mysqli_fetch_assoc($res)){
				$match .= stname($row['name'])."\n".'下载链接：'."\n".$row['sharelink']."\n".'提取密码：'.$row['password']."\n";
			}
			mysqli_free_result($res);
			return $match;
		}
		return  "未查询到结果，请重试。\n若要查看汉化组作品列表，请输入汉化组名称，例如：祐希堂汉化组。\n若要查询某个作者作品列表，请输入作者姓名。\n作者与漫画名称支持模糊查询。\n搜索说明：http://t.cn/Ruupf4S";
		//\n作品目录：http://t.cn/RnMc1El
	}
	mysqli_close($link);
}

function stname($str){
	$threshold = 50;
	if(mb_strlen($str) > $threshold){
		$str = mb_substr($str, 0, $threshold) . '…';
	}
	return $str;
}