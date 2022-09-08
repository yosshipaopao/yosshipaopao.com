<h3>【<?php echo $data[$id]['info'];?>】<?php echo $data[$id]['title'];?></h3>
<p>[youtube]</p>
<div class="youtube">
	<iframe width="560" height="315 " src="https://www.youtube.com/embed/<?php echo $data[$id]['youtube']?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
<?php
if (isset($data[$id]['yt_main']) && $data[$id]['yt_main']!=""){
    echo '<div class="youtube"><iframe width="560" height="315 " src="https://www.youtube.com/embed/'.$data[$id]['yt_main'].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
}
if (isset($data[$id]['syusai'])&& $data[$id]['syusai']!=""){
    echo '<p>[主催]</p><h4>'.$data[$id]['syusai'].'</h4>';
}
if(isset($data[$id]['song'])&& $data[$id]['song']!=""){
	echo "<p>[Music]</p><h4>".$data[$id]['song']."</h4>";
}

if (isset($data[$id]['vocal']) && $data[$id]['vocal']!="") {
    echo '<p>[Vocal]</p><h4>'.$data[$id]['vocal'].'</h4>';
}?>