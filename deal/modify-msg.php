<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<?php
	require("dbconfig.php");
    session_start();
    $n=$_POST['n'];
    $nicknames=$_SESSION['name'];
    $seltuser=mysql_query("select * from user where nicknames='$nicknames'")or die(mysql_error());
    $user_id=mysql_fetch_assoc($seltuser);
    $user_id_data=$user_id['id'];
    $shop_dm=$_POST["shop_dm"];
    $telno=$_POST["telno"];
    $shopName=$_POST["shopName"];
    $typea=$_POST["type"];
    $shop_state=$_POST["shop_state"];
    //r_dump($shop_state);

    $pakking=$_POST["pakking"];
    $wc=$_POST["wc"];
    $wifi=$_POST["wifi"];

    $mt=$_POST["mt"];
    $bdwm=$_POST["bdwm"];
    $zfb=$_POST["zfb"];
    $tb=$_POST["tb"];
    $tdd=$_POST["tdd"];

    if($pakking=='on'){
    	$pakking=1;
    }
    else{
    	$pakking=0;
    }
    if($wc=='on'){
    	$wc=1;
    }
    else{
    	$wc=0;
    }
    if($wifi=='on'){
    	$wifi=1;
    }
    else{
    	$wifi=0;
    }
    if($mt=='on'){
    	$mt=1;
    }
    else{
    	$mt=0;
    }
    if($bdwm=='on'){
    	$bdwm=1;
    }
    else{
    	$bdwm=0;
    }
    if($zfb=='on'){
    	$zfb=1;
    }
    else{
    	$zfb=0;
    }
    if($tb=='on'){
    	$tb=1;
    }
    else{
    	$tb=0;
    }
    if($tdd=='on'){
    	$tdd=1;
    }
    else{
    	$tdd=0;
    }
    $enviro_support=$pakking.$wc.$wifi;
    $sever_support=$mt.$bdwm.$zfb.$tb.$tdd;

    //上传图片
    $photos='';
	$uploaddir = "../shop_photos/";//设置文件保存目录 注意包含/       
    $type=array("jpg","gif","bmp","jpeg","png");//设置允许上传文件的类型    
    //获取文件后缀名函数   
    function fileext($filename)   
    {   
        return substr(strrchr($filename, '.'), 1);   
    }   
    //生成随机文件名函数       
    function random($length)   
    {   
        $hash = 'CR-';   
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';   
        $max = strlen($chars) - 1;   
        mt_srand((double)microtime() * 1000000);   
            for($i = 0; $i < $length; $i++)   
            {   
                $hash .= $chars[mt_rand(0, $max)];   
            }   
        return $hash;   
    } 
    $photo_num = $n; 
    for ($i=0; $i < $n; $i++) {
    	$j=$i+1; 
    	$nameimg="img".$j; 
        $fileNameImg = 'img'.$j; 
        if(!$_FILES["$fileNameImg"]['name']) {
            $photo_num--;
            continue;
        }
	    if(!in_array(strtolower(fileext($_FILES["$nameimg"]['name'])),$type))  //判断文件类型 
	    {   
	        $text=implode(",",$type); 
            $echo_massage = "您只能上传以下类型文件: ,".$text.",<br>"; 
	    }
	    else{
	    	//生成目标文件的文件名
	    	$filename[$j]=explode(".",$_FILES[$nameimg]['name']);
	    	do   
	        {   
	            $filename[$j][0]=random($j); //设置随机数长度   
	            $name[$j]=implode(".",$filename[$j]);     
	            $uploadfile[$j]=$uploaddir.$name[$j]; 
	        }   
	        while(file_exists($uploadfile[$j])); 
	        if (move_uploaded_file($_FILES[$nameimg]['tmp_name'],$uploadfile[$j])){
	            if($uploadfile[$j]){   
	            	$photos=$photos.$name[$j].",";
	            }
	        }
	    }   
    } 
    if(!$shopName){
        $echo_massage = "请输入商店名称";
    }
    if(!$telno){
        $echo_massage = "请输入联系方式";
    }
    else if(!$type){
        $echo_massage = "请输入类型";
    }
    else if(!$photos&&($photo_num)){
        $echo_massage = "图片上传失败";
    }
    else{
        $photos=substr($photos,0,-1);
        if($photos) {
            $seltuser=mysql_query("update shop set shop_mc='$shopName',telno='$telno',type='$typea',shop_state='$shop_state',photos='$photos' where shop_dm='$shop_dm'")or die("插入数据失败".mysql_error());   
        }
        else{
             $seltuser=mysql_query("update shop set shop_mc='$shopName',telno='$telno',type='$typea',shop_state='$shop_state' where shop_dm='$shop_dm'")or die("插入数据失败".mysql_error());  
        }
        if($seltuser){
            $echo_massage =  "修改店铺信息成功！";
        }
    }
?>
<div class="message_c_sad" style = 'width: 600px;height: 400px;border: 3px solid #749263;border-radius: 5px;margin: 20px auto;padding: 20px;'>
    <?php 
        echo "$echo_massage"; 
    ?>
    <p>5秒后将会为您跳转，如果您的浏览器没有自动跳转，请<a href="../page/manage-shop.php">点击这里</a></p>
</div>
<script>
 setTimeout(function(){
       window.location.href='../page/manage-shop.php';
 },5000)

</script>
