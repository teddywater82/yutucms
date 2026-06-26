<?php
if (isset($ADMINKEY)) { }else{ exit('404');   }   include('../Php/Admin/cookie.php');?>
<?php

	if($_POST['fl']=='title'){
		$JCSQL_title=file('../JCSQL/Admin/Plug/Zhanqun/title.txt');
		$count=count($JCSQL_title);
		$rand=rand(0, $count-1);
		echo $JCSQL_title[$rand];
	}
	if($_POST['fl']=='keywords'){
		$keywords=file('../JCSQL/Admin/Plug/Zhanqun/keywords.txt');
		$count=count($keywords);
		echo $keywords[rand(0, $count-1)].','.$keywords[rand(0, $count-1)].','.$keywords[rand(0, $count-1)].','.$keywords[rand(0, $count-1)];
	}	
	if($_POST['fl']=='description'){
		$description=file('../JCSQL/Admin/Plug/Zhanqun/description.txt');
		$count=count($description);
		echo $description[rand(0, $count-1)].','.$description[rand(0, $count-1)].','.$description[rand(0, $count-1)].','.$description[rand(0, $count-1)];
	}	
?>