<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
	function GetTime($value=""){
		if (!empty($value)) {
			$time= date("Y-m-d H:i",$value);
			return $time;
		}
	}
	//定义全局文件Url
	function GetUrl($value="",$type=null){
		if (!empty($value)) {
			$arr = json_decode($value);
			switch ($type) {
				case null:
					$url = $arr->url;
					break;
				
				case 'address':
					$url = $arr->address;
					break;
			}
			return $url;
		}else{
			return "/static/common/img/hhm.jpg";
		}
	}
	function GetAddress($value=""){
		if (!empty($value)) {
			$arr = json_decode($value);
			return $arr->address;
		}
	}
	function GetQrcode($url){
	    /*生成二维码*/
	    vendor("phpqrcode.phpqrcode");
	    $data =$url;
	    $level = 'L';// 纠错级别：L、M、Q、H
	    $size =14;// 点的大小：1到10,用于手机端4就可以了
	    // $QRcode = new \QRcode();
	    // ob_start();
	    // $str = $QRcode->png($data,false,$level,$size,1);
	    // $imageString = base64_encode(ob_get_contents());
	    // ob_end_clean();
	    $object = new \QRcode();
	    //打开缓冲区
	    ob_start();
	    $object->png($url, false, $level, $size, 2);
	    //这里就是把生成的图片流从缓冲区保存到内存对象上，使用base64_encode变成编码字符串，通过json返回给页面。
	    $imageString = base64_encode(ob_get_contents());
	    //关闭缓冲区
	    ob_end_clean();
	    //把生成的base64字符串返回给前端
	    return "data:image/jpg;base64,".$imageString;
	}
	function GetMbSubstr($value,$num){

		if (strlen($value) > $num) {
		 	$value= mb_substr($value,0,$num,'utf-8')."...";
		}
		 return $value;
	}