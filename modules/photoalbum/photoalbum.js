$(document).ready(function() {
	var Current, i; var img_count = $('.Carusel .Slider a').size(); var img_max = img_count > 9 ? 9 : img_count; 	
	$('.Carusel .Slider a').each(function(index){
		if($(this).attr('class') == 'current') Current = index;
	});
	if(img_max < 5) $('.Carusel .Container').css({'width' : ($('.Carusel .Slider img:first').width() + 6) * img_max, 'margin':'0 auto'});
	else $('.Carusel .Slider img').width($('.Carusel .Container').width() / img_max - 6);
	if(Current < Math.floor(img_max / 2)) { for(i = 0; i < Math.floor(img_max / 2) - Current; i++) $('.Carusel .Slider a:eq('+(img_count - 1)+')').prependTo($('.Carusel .Slider')); }
	else if(Current > Math.floor(img_max / 2)) { for(i = 0; i < Current - Math.floor(img_max / 2); i++) $('.Carusel .Slider a:eq(0)').appendTo($('.Carusel .Slider')); }
	
	var uploaders = {};
	$('.uploader').each(function(index){
		var uploader = this;
		uploaders[index] = new qq.FineUploader({
			element: uploader,
			multiple: false,
			request: {
				endpoint: '/modules/standart/multiupload/server/handler3.php',
				paramsInBody: false,
			},
			callbacks: {
		    	onComplete: function(id, fileName, responseJSON) {
		    		if(responseJSON.success) {
		    			$('.uploaderFiles', $(uploader).parents('td')).append('<span class="imgCon"><img src="/userfiles/temp/'+responseJSON.uploadName+'" class="img" /><img src="/template/standart/exit.png" class="remove" onclick="imgRemove($(this))" /><input type="hidden" name="pic" value="'+responseJSON.uploadName+'" /></span>');
		    			$(uploader).parents('.uploaderCon').hide();
		    		}
		    	}
		    },
		    debug: true
	    });
	});
});

function imgRemove(o){
	o.parents('td').find('.uploaderCon').show();
	o.parents('.imgCon').remove();
}

var Map;
function initMap(id){
	var coords = $('span.maps_' + id).text() ? $('span.maps_' + id).text() : $('span.maps_default').text();
	coords = coords.split(',');
	// Создаем объект карты, связанный с контейнером:
	Map = new DG.Map('Map');
	// Устанавливаем центр карты, и коэффициент масштабирования:
    Map.setCenter(new DG.GeoPoint(coords[0],coords[1]), $('span.maps_' + id).text() ? 15 : 11);
    // Добавляем элемент управления коэффициентом масштабирования:
    Map.controls.add(new DG.Controls.Zoom());
    if($('span.maps_' + id).text()) SetMapPoint(id);
    if(/(editphoto)|(addphoto)/.exec(window.location.href)){
    	Map.geoclicker.disable();  
	    Map.addEventListener(Map.getContainerId(), 'DgClick', function(e){
	    	var balloons = Map.balloons.getAll();
	    	for(var i = 0; i < balloons.length; i++) balloons[i].hide();
	    	
	    	var balloon = new DG.Balloons.Common({
			   geoPoint: new DG.GeoPoint(e.getGeoPoint().getLon(),e.getGeoPoint().getLat()),
			   contentHtml: '<div class="dgInfocardGeo"><div class="loaderContainer"><img alt="" src="http://maps.api.2gis.ru/images/station-info-loader.gif" height="32px" width="32px"> <span class="loading">Загрузка данных</span></div></div>'
			});
			
			Map.balloons.add(balloon);
	    	Map.geocoder.get(e.getGeoPoint(), {
				types: ['city', 'settlement', 'district', 'street', 'house'],
				limit: 1,
				// Обработка успешного поиска
				success: function(geocoderObjects) {
					var geocoderObject = geocoderObjects[0];
					var attributes = geocoderObject.getAttributes();
	                info = '<div class="dgInfocardGeo">';
	                info += '<div class="dg-map-geoclicker-address">';
	                if(attributes && attributes.index) info += attributes.index+', ';
	                info += geocoderObject.getName()+'</div>';
	                if(attributes && attributes.purpose) info += '<span class="dg-map-geoclicker-purpose">'+attributes.purpose+'</span>';                
	                info += '<a href="javascript:void(0)" class="dg-map-geoclicker-firmcount" onclick="SetMapPoint('+id+',\''+balloon.getId()+'\');">Выбрать эту точку</a>';
	                info += '</div>';
	                balloon.setContent(info);
				},
				failure: function(code, message) {
					balloon.setContent('<div class="dgInfocardGeo">Не удалось найти адрес в этой точке.<br><a href="javascript:void(0)" class="dg-map-geoclicker-firmcount" onclick="SetMapPoint('+id+',\''+balloon.getId()+'\');">Выбрать эту точку</a></div>');
				}
			});
	    });
    }          
}


function SetMapPoint(id, balloonId){	
	if(balloonId){
		var balloon = Map.balloons.get(balloonId);
		var geoPoint = balloon.getPosition();		
		$('input.maps_' + id).val(geoPoint.getLon() + ',' + geoPoint.getLat());
	}
	else{
		var coords = ($('span.maps_' + id).text()).split(',');
		var geoPoint = new DG.GeoPoint(coords[0],coords[1]);
		Map.setCenter(geoPoint, 15);	
	}	
    
	Map.markers.removeAll();
	Map.balloons.removeAll();
	var marker = new DG.Markers.Common({geoPoint: geoPoint });	
	Map.markers.add(marker);
	var markerId = marker.getContainerId();
	if($('.pic_'+id+'').text()){
		$('#'+markerId).parent().addClass('custom');
		marker.setIcon(new DG.Icon('/userfiles/picnews/' + $('.pic_'+id+'').text(), new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
	}	
}

function ItemDelete(id, link, act) { ActionAndUpdate(id, act, link); }

function ActionAndUpdate(id, act, link) { JsHttpRequest.query('/modules/photoalbum/photoalbum-JSReq.php',{'id':id,'act':act,'link':link},function(result,errors){ if(result){ if (act=="DELPHOTO" || act=="DELALBUM"){ $("#Act"+id).parents('.Item').remove(); } }},true); }
