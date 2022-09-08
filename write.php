<?php
$MCjson = file_get_contents("data.json");
$MCjson = mb_convert_encoding($MCjson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$data = json_decode($MCjson,true);

if(isset($_POST["href"]) && !isset($data[$_POST["href"]])){
    $input = array(“href” => $_POST["href"]);
    array_unshift($data["titles"],$_POST["href"]);
    
    $data[$_POST["href"]]=$_POST;
   file_put_contents("data.json", json_encode($data, JSON_UNESCAPED_UNICODE));
}
//var_dump($data);
echo $_POST["pass"];
if($_POST["pass"]=="Sakagamipaopao0628"){
echo '<form action="" method="post"><input type="text" name="pass" value="Sakagamipaopao0628" hidden><p>href<input type="text" name="href"></p><p>title<input type="text" name="title"></p><p>youtube<input type="text" name="youtube"></p><p>ytmain<input type="text" name="yt_main"></p><p>syusai<input type="text" name="syusai"></p><p>song<input type="text" name="song"></p><p>vocal<input type="text" name="vocal"></p><p>info<input type="text" name="info"></p><input type="submit" value="送信"></form></div>';
}else{
    echo '<form action="" method="post"><input type="text" name="pass"><input type="submit"></form>';
}
?>
