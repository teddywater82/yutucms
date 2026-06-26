<?php
//$GetMb_id
//$GetMb_page


$MYSQLVODS = json_decode(file_get_contents('./JCSQL/Home/'.$GetMb_page.'.txt'),true);

$VideoTypeName=$vodtype[$GetMb_page];
$VideoTypeName=$VideoTypeName['name'];
$tpl->assign('VideoTypeJCSQL', $MYSQLVODS);
$tpl->assign('VideoType', $GetMb_page);
$tpl->assign('ListName', $VideoTypeName);
$tpl->assign('ListUrl',$Host1.'video_list'.$Host2.$GetMb_page.$Host3.'1'.$Host4);
$tpl->assign('DUrl', $Host1.$GetMb_tmp.$Host2.$GetMb_id.$Host3.$GetMb_page.$Host4);
$tpl->assign('CUrl', $Host1.'video_conter'.$Host2.$GetMb_id.$Host3.$GetMb_page.$Host4);
$count=count($MYSQLVODS)-1;
$FFCZ =null;
for ($x=0; $x<=$count; $x++) {

  $MYSQLVODSs=$MYSQLVODS[$x];
  if($MYSQLVODSs['d_id'] ==$GetMb_id ){
	  $tpl->assign('Id', $MYSQLVODSs['d_id']);
	  $tpl->assign('Name', $MYSQLVODSs['d_name']);
	  $tpl->assign('Pic', $MYSQLVODSs['d_pic']);
	  // 检测是否已支付参数
	  $paid_param = '';
	  if (isset($_GET['paid']) && $_GET['paid'] == '1') {
		  $paid_param = '&paid=1';
		  if (isset($_GET['trade_no'])) {
			  $paid_param .= '&trade_no=' . urlencode($_GET['trade_no']);
		  }
	  }
	  $paly='<iframe src="/Static/Home/VideoJS/index.php?Play='.$MYSQLVODSs['d_playurl'].'&Cover='.$MYSQLVODSs['d_pic'].'&video_id='.$MYSQLVODSs['d_id'].'&video_name='.urlencode($MYSQLVODSs['d_name']).'&video_page='.$GetMb_page.$paid_param.'" frameborder="0" scrolling="no" width="100%" height="100%"></iframe>';
	  $tpl->assign('Play',$paly);
	  $tpl->assign('Time', $MYSQLVODSs['d_time']);
	  $FFCZ='4';
  }
} 
if($FFCZ ==null){ Header("Location:?");  }

$tpl->show($this_WebMoban.'/html/'.$GetMb_tmp);

?>