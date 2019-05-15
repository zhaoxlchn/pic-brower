<?php
$pw=!empty($_GET['pw'])?$_GET['pw']:false;
if($pw==='zhao2534'){
    setcookie("userLogin","1",time()+2592000);
    exit('登陆成功！');
}
header("Content-type:application/json");
$get=!empty($_POST['dir'])?$_POST['dir']:"./";

//todo:读取当前传递过来的目录下所有目录以及文件
$files=scandir($get);
//todo:重新拼接当前目录的上级目录地址
$get_dir_arr=preg_split('/\//',$get);
$parent_dir="";
for($i=0;$i<count($get_dir_arr)-2;$i++){
    $parent_dir.=$get_dir_arr[$i]."/";
}
//todo：屏蔽根目录显示上级目录
preg_match_all('/^(\.\/){1}$/',$get,$matches,PREG_PATTERN_ORDER);
$dir_html=empty($matches[0])?"<li onclick='js.post(\"{$parent_dir}\")'>上一级目录</li>":"";

$pic_html="";
foreach ($files as $file){
    //todo：筛选图片
    $pattern='/(jpg|png|gif)/is';
    preg_match_all($pattern,$file,$matches,PREG_PATTERN_ORDER);
    if(!empty($matches[1])){
        $pic_html.="<li data-fancybox='gallery' href='{$get}/$file'><img src='{$get}/$file'></li>";
    }
    //todo:筛选文件夹
    if(is_dir($get."/".$file)){
        //todo:去掉读取出来的上上级、上级目录
        $pattern='/^\.{1,2}$/';
        preg_match_all($pattern,$file,$matches,PREG_PATTERN_ORDER);
        if(empty($matches[0]))$dir_html.="<li onclick='js.post(\"{$get}{$file}/\")'>{$file}</li>";
    }
}
if(empty($_COOKIE['userLogin'])){
    $pic_html=$dir_html='';
}
echo json_encode(['pic_html'=>$pic_html,'dir_html'=>$dir_html,'now_dir'=>$get]);