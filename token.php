<?php
error_reporting(0);
header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
define('APPID', '');
define('APPSEC', '');
$_ch = curl_init();
$_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid="
	.APPID
	."&secret="
	.APPSEC;
$_headers = array('Accept: application/json');
curl_setopt($_ch, CURLOPT_URL, $_url);
curl_setopt($_ch, CURLOPT_HTTPHEADER, $_headers);
curl_setopt($_ch, CURLOPT_TIMEOUT_MS, 5000);
curl_setopt($_ch, CURLOPT_RETURNTRANSFER, 1);
$res = json_decode(curl_exec($_ch), 1);
curl_close($_ch);
define('ACTOKEN', $res['access_token']);
//echo ACTOKEN;