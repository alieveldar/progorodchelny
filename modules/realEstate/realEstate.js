function preRealestateMap(center, events) { var H=620; var W=900; ViewBlank("Жилые комплексы Казани", '<div id="Map" style="width:'+W+'px; height:'+H+'px;">Загрузка карты</div>'); setTimeout(function() { initRealestateMap(center, events); }, 1000); }

function initRealestateMap(center, events){
	var center = center.split(',');
	var Map = new DG.Map('Map');
    Map.setCenter(new DG.GeoPoint(center[0],center[1]), 12);
    Map.controls.add(new DG.Controls.Zoom());
    
    for(i = 0; i < events.length; i++){
    	if(!events[i][2]) continue;	
    	var id = events[i][0];
    	var name = events[i][1];
    	var coords = events[i][2].split(',');
    	var link = events[i][3];
    	var spec = events[i][4];
    	var icon = events[i][5];
    	var icof = events[i][6];
    	
    	if (icon && spec==1) {


	    	var marker = new DG.Markers.Common({ geoPoint: new DG.GeoPoint(coords[0],coords[1]),
	    		icon:new DG.Icon(icon, new DG.Size(70, 70*icof), function() { return new DG.Point(-7, -51)}),
	    		hint:name,
	    		clickCallback : function(clickEvent, marker){
                    var markerId = clickEvent.getId();
	    			var eventId = $('#'+markerId).attr('data-event');
	    			var eventLn = $('#'+markerId).attr('data-link');
					document.location="/"+eventLn+"/view/"+eventId;
	    		}
	    	});
	   		Map.markers.add(marker); var markerId = marker.getContainerId(); $('#'+markerId).attr('data-event', id); $('#'+markerId).attr('data-link', link);
			$('#'+markerId).parent().addClass('custom'); $('#'+markerId).attr('data-icon', 1);


	    } else {


	    	/* var myBalloon = new DG.Balloons.Common({ geoPoint: new DG.GeoPoint(coords[0],coords[1]), contentHtml: '<a href="/'+link+'/view/'+id+'">'+name+'</a>'}); Map.balloons.add(myBalloon); */
	    	var marker = new DG.Markers.Common({ geoPoint: new DG.GeoPoint(coords[0],coords[1]), hint:name,
	    		clickCallback : function(clickEvent, marker){
                    var markerId = clickEvent.getId();
	    			var eventId = $('#'+markerId).attr('data-event');
	    			var eventLn = $('#'+markerId).attr('data-link');
					document.location="/"+eventLn+"/view/"+eventId;
	    		}
	    	});
	   		Map.markers.add(marker); var markerId = marker.getContainerId(); $('#'+markerId).attr('data-event', id); $('#'+markerId).attr('data-link', link);
			/*$('#'+markerId).parent().addClass('custom');*/ $('#'+markerId).attr('data-icon', 1);


	    }
	    
    }              
}


function initMap(ev){
    center = ev[2].split(',');
    var Map = new DG.Map('Map');
    Map.setCenter(new DG.GeoPoint(center[0],center[1]), 14);
    Map.controls.add(new DG.Controls.Zoom());
    var id = ev[0];
    var name = ev[1];
    var icon = ev[3];
    var marker = new DG.Markers.Common({
        geoPoint: new DG.GeoPoint(center[0],center[1]),
        icon : icon ? new DG.Icon(icon, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ) : null,
        hint : name,
        clickCallback : function(clickEvent, marker){
            var this_ = this;
            var iconurl = this_.getIcon().url;
            var markerId = this_.getContainerId();
            var icon = $('#'+markerId).attr('data-icon');
            var eventId = $('#'+markerId).attr('data-event');
            if(icon){
                $('#'+markerId).parent().addClass('custom');
                this_.setIcon(new DG.Icon('/template/standart/loader00.gif', new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
            }
            JsHttpRequest.query('/modules/eventmap/GetEvent-JSReq.php',{'id':eventId},function(result,errors){
                if(icon) this_.setIcon(new DG.Icon(iconurl, new DG.Size(35, 35), function() { return new DG.Point(-7, -51)} ));
                else $('#'+markerId).parent().removeClass('custom');
                ViewBlank(result['name'], result['text']);
            },true);
        }
    });
    Map.markers.add(marker);
    var markerId = marker.getContainerId();
    $('#'+markerId).attr('data-event', id);
    if(icon){
        $('#'+markerId).parent().addClass('custom');
        $('#'+markerId).attr('data-icon', 1);
    }
}