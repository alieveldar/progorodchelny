<?
$Page["Right2Content"]="";
$Page["RightContent"]="";

$Page["Content"]="
<div id='MapDIV'>Поиск положения</div>
<script>
$('#MapDIV').html('IF');
if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
			var lat=position.coords.latitude;
			var long=position.coords.longitude;
			
			$('#MapDIV').html('<img src=\"http://static.maps.api.2gis.ru/1.0?zoom=15&size=500,350&markers='+long+','+lat+'\">');
});
		
	

} else {
	$('#MapDIV').html('None geolocation');
}
</script>"; ?>