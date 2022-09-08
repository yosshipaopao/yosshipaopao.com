<?php
$MCjson = file_get_contents('../data.json');
$data = json_decode($MCjson, true);

//var_dump($data);

$titles=$data["titles"];

$urls = [];

foreach($titles as $title){
    $path = "https://i.ytimg.com/vi/".$data[$title]["youtube"]."/0.jpg";
    echo $path;
    if(!file_exists($title.".jpg")){
        $file_name = $title.'.jpg';
        
        $image = file_get_contents($path);
        
        $save_path = $file_name;
        
        file_put_contents($save_path, $image);
        
        echo "finish of ".$title;
    }
}