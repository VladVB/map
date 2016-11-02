<div class="mapwrap" > 
    <div class="map" id="map">
    </div>
</div>
<div class="cellfooter">         
</div>
<script type="text/javascript">
     
    function initialize() {     
        
        // раздел документа, куда помещаем карту
	var mapDiv = document.getElementById('map');        
	// выставляем координаты центра 
	var latlng = new google.maps.LatLng(50.450090972, 30.523414806);        
	// опции начальной загрузки карты
	var styles = [
	{
	featureType: "all",
	elementType: "labels",
    stylers: [{ width: 350 }]
	}
	];
	var options = 	{
					center: latlng, // центр 
					zoom: 8, // уровень масштаба карты
					mapTypeId: google.maps.MapTypeId.ROADMAP, // тип карты 
					styles: styles
					};
	
	var map = new google.maps.Map(mapDiv, options); // создаём екземпляр объекта карты с которым будем работать
        
	var cities = []; // здесь будут хранится маркеры населённых пунктов
        
        
        $.post("<?php echo $this->createAbsoluteUrl('/home'); ?>",                    
                    {},
                    function(data) {                          
                         
				 $.each(data, function(i, item) // по всем строкам массива (которые суть информация о населённом пункте) отрабатываем функцию
				 // name - название населённого пункта				 				 
							{                                                                                                                   
							$.ajax({
							url:"http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address="+item.name,
							success:function(datageo) {
							var flagcountry = false;
							if(datageo['status'] == 'OK') { 														
							for (var k=0; k<datageo.results.length; k++)
							{
								var short_name = item.onmap;
								var lat = datageo['results'][k]['geometry']['location']['lat'];
								var lng = datageo['results'][k]['geometry']['location']['lng'];
								flagcountry = true;
								break;
								
							}                                                       							
							if (flagcountry == true)
							{
								flagcountry = false;
								var location = new google.maps.LatLng(lat, lng);                                                                
								var marker = new MarkerWithLabel(
												{
												position: location,
												labelContent: short_name,
												labelAnchor: new google.maps.Point(30, 0),												
								        		});
								
                                                                marker.labelStyle['font-size'] = "15px";
								cities.push(marker);
							}
							
														}
														
													}

									});
							 
	  						});
				                         
                         
                    }, "json"
                );        
        // при zoom вызывается обработчик события, в котором создаём кластер населённых пунктов
	google.maps.event.addListener(map, 'idle', 
	function() { var markerClusterer = new MarkerClusterer(map, cities, {imagePath: '../images/m'})});         
    };
    
window.onload = initialize;
</script>    