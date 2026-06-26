<?php
/***读取数据库中的数据并且给予变量中***/
$AdminBasic = json_decode(file_get_contents("./JCSQL/Admin/Basic/AdminBasic.php"),true);
//PC模版
$WebMobanPC		=	$AdminBasic['WebMobanPC'];
//手机模版
$WebMobanWAP	=	$AdminBasic['WebMobanWAP'];
//网站标题
$WebTitle		=	$AdminBasic['WebTitle'];
//网站关键字
$WebKeywords	=	$AdminBasic['WebKeywords'];
//网站描述
$WebDescription	=	$AdminBasic['WebDescription'];
//网站公共
$WebGongao	=	$AdminBasic['WebGongao'];
//网站公共开关
$WebGongaoOpen	=	$AdminBasic['WebGongaoOpen'];
//网站logo链接
$WebLogo		=	$AdminBasic['WebLogo'];
//网站邮箱
$WebEmail		=	$AdminBasic['WebEmail'];
//网站统计
$WebCnzz = file_get_contents("./JCSQL/Admin/Basic/AdminStatistics.php");
//站群插件
//$webss=explode(".",$_SERVER['HTTP_HOST']);$ws=$webss['1'].'.'.$webss['2'];




$webniubi='1';//1:主域名站群2:子域名站群

if($webniubi=='1'){
	if(strpos($_SERVER ['HTTP_HOST'],'www.') !== false){ 
	preg_match("#\.(.*)#i","http://".$_SERVER ['HTTP_HOST'],$webss);$webss = $webss[1];	
	}else{
	 $webss=$_SERVER['HTTP_HOST'];
	}	
}

if($webniubi=='2'){
	 $webss=$_SERVER['HTTP_HOST'];
}



$file ="./JCSQL/Admin/Plug/Zhanqun/index.php";

if(file_exists($file))
{
$AdminWebDomain = json_decode(file_get_contents($file),true);

foreach ($AdminWebDomain as $value) {
	if($webss==$value['WebDomain']){
	$WebDomain=$value['WebDomain'];
	$WebMobanPC=$value['WebMobanPC'];
	$WebMobanWAP=$value['WebMobanWAP'];
	$WebTitle=$value['WebTitle'];
	$WebKeywords=$value['WebKeywords'];
	$WebDescription=$value['WebDescription'];
	$WebLogo=$value['WebLogo'];
	$WebEmail=$value['WebEmail'];	
		}

 }
}
else
{
    //不存在站群数据文件
}








?>