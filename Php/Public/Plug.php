<?php
switch($Php){
	case 'Plug/Zhanqun/index':$C_T_0 = 'Plug/Zhanqun/index';break;//站群插件首页
	case 'Plug/Zhanqun/add':$C_T_0 = 'Plug/Zhanqun/add';break;//站群插件首页
	case 'Plug/Zhanqun/SEO':$C_T_0 = 'Plug/Zhanqun/SEO';break;//站群插件关键词随机
	case 'Plug/Zhanqun/mod':$C_T_0 = 'Plug/Zhanqun/mod';break;//站群插件修改
	default:$C_T_0 = 'Login';break;//默认登录页面	
	}
include('../Php/Admin/'.$C_T_0.'.php'); 
?>