<?php
$MCjson = file_get_contents('data.json');
$data = json_decode($MCjson, true);
$title = "";
if(isset($_GET['href'])) {
    $id = $_GET['href']; 
    if(!isset($data[$id])){
        if(file_exists("./".$id.".php")){
            if($id=="index"){
                $title="ホーム";
                $mode="home";
            }else{
            $title=$id;
            $mode="id";
        }   
        }else{
            $title="404 ERROR";
            $mode="404";
        }
        
    }else{
        $title=$data[$id]["title"];
        $mode="work";
    }
}else{
    $title="ホーム";
    $mode="home";
}
?><!DOCTYPE html><html lang="ja"><head><link rel="icon" type="image/png" href="/icon.png"><title><?php echo $title;?>|yosshipaopao</title><link type="text/css" rel="stylesheet" href="/css/loading.css" /><link type="text/css" id="mainstyle" rel="stylesheet" href="/css/style.css" /><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script><script type="text/javascript" src="/js/backup.js"></script></head><body><div class="background"></div><button id="stylebutton" onclick="changeStyleSheet()"></button><?php
  if(!isset($_GET["css"])){echo "<div id=\"loading\"><div class=\"spinner\"></div></div>";}
  else{echo "<iframe id=\"nyancat\" src=\"/nyancat.php\"></iframe>";}
 ?><div id="parent"><?php include('./child1.php') ?><div id="child2"><?php
    if($mode=="home"){
        include("./main.php");
    }else if($mode=="work"){
        include("./work.php");
    }else if($mode=="id"){
        include("./".$id.".php");
    }else{ 
        include("./error/404.php");
    }
    ?></div></div><footer><p>&copy;yosshipaopao</p></footer></body></html>