function initMaps(nameblock, source) 
{
	//alert('1');
	// раздел документа, куда помещаем карту
	var mapDiv = document.getElementById(nameblock);
	// выставляем координаты центра Дальнего Востока
	var latlng = new google.maps.LatLng(59.42, 124.40);
	// опции начальной загрузки карты
	var styles = [
	{
	featureType: "all",
	elementType: "labels",
    stylers: [{ width: 350 }]
	}
	];
	var options = 	{
					center: latlng, // центр Дальнего Востока
					zoom: 5, // уровень масштаба карты
					mapTypeId: google.maps.MapTypeId.ROADMAP, // тип карты 
					styles: styles
					};
	
	var map = new google.maps.Map(mapDiv, options); // создаём екземпляр объекта карты с которым будем работать
	var cities = []; // здесь будут хранится маркеры населённых пунктов

		
	// вызываем фунцию $.ajax для получения от сервера по GET-каналу данных про населённые пункты в формате JSON
	$.ajax({
		url: source, // откуда получаем
		type:"GET", // канал получения данных
		dataType:"json", // формат данных
		success: function(data) // функция, которая вызывается после успешной загрузки данных
				{
				 $.each(data, function(i, item) // по всем строкам массива (которые суть информация о населённом пункте) отрабатываем функцию
				 // поля файла cities.csv :
				 // name - название населённого пункта;
				 // fsize - размер шрифта
				 				 
							{
							$.ajax({
							url:'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='+item.name,
							success:function(datageo) {
							var flagcountry = false;
							if(datageo['status'] == 'OK') {
														
							for (var k=0; k<datageo.results.length; k++)
							{
								var short_name = datageo.results[k].address_components[0].short_name;
							
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
												labelClass: "labels", // the CSS class for the label
								        		});
								if (!isNaN(parseInt(item.fsize)))
								marker.labelStyle['font-size'] = item.fsize+"px";
								cities.push(marker);
							}
							
														}
														
													}

									});
							 
	  						});
				}
		});
		
	// при zoom вызывается обработчик события, в котором создаём кластер населённых пунктов
	google.maps.event.addListener(map, 'idle', 
	function() { var markerClusterer = new MarkerClusterer(map, cities)});
    

}

