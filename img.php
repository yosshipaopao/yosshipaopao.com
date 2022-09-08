<?php  
$dir = "./img"; 
 
// open a known directory, and proceed to read its contents  
if (is_dir($dir))  
{
if ($dh = opendir($dir)) {
while (($file = readdir($dh)) !== false) {
    if(strpos($file,".jpg")){
echo "<a href='img/$file'><img src='img/$file' /></a>";
}}
closedir($dh);  
}  
}  
?>