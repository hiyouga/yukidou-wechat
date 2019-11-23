<?php
require_once 'token.php';
$data = '
{
	"button":
	[
	    {
	        "name": "目录",
	        "sub_button":
	        [
	            {
	                "type": "click",
	                "name": "祐希堂汉化组",
	                "key": "BIND_DEVICE"
	            },
	            {
	                "type": "click",
	                "name": "映画",
	                "key": "BIND_INFO"
	            }
	        ]
	    }
	]
}
';
$ch = curl_init();   
curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . ACTOKEN);   
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
echo curl_exec($ch);
curl_close($ch);