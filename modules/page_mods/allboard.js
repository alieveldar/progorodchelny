$(function() {
	$('.AdvertsRub a').click(function(e){
		//$('.AdvertsRub ul').not($('ul', $(this).parent('li'))).slideUp();
		$('ul', $(this).parent('li')).stop(true, true).slideToggle();
	});
});

function getFileName(){ var name=$("#advert").val(); $("#FileName").html(name); }