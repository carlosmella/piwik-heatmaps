//####################### DRUPAL ##########

(function ($, Drupal, window, document, undefined) {


// To understand behaviors, see https://drupal.org/node/756722#behaviors
Drupal.behaviors.my_custom_behavior = {
  attach: function(context, settings) {
	  
//####################### DRUPAL ##########

// En caso de empregar outro CMS ou non empregar ningún, eliminar as líneas que están entre os comentarios que poñen "DRUPAL"

	  var ruta = "//prueba.local/piwik/";
	  
    $(document).ready(function() {
      	
	$('#page').on('mousedown', function(e) {
		
		
		$.post( ruta+"plugins/MapaCalor/mapaCalorAlmacenarDatos.php",{getUrls: "1"})
			.done(function(data){
				var aux = JSON.parse(data);
				var flag = false;
				$.each(aux,function(index,value){
				if(encodeURIComponent(document.location) == value) flag=true;
				});
				if(flag == true){
					if((e.pageX- $('#page').offset().left) != 0 && e.pageY != 0){
						$.post(ruta+"plugins/MapaCalor/mapaCalorAlmacenarDatos.php",{
						coordX: e.pageX - $('#page').offset().left,
						coordY: e.pageY - $('#page').offset().top,
						page: escape(document.location.pathname),
						insertClick: 1
						});
					}
				}
			},"json");
	});
	
	var arrayX = new Array();
	var arrayY = new Array();
	var arrayPage = new Array();
	var arrayDate = new Array();
	var X;
	var Y;
	var Dates;
	var Pages;
	var Urls = false;
	$.post(ruta+"plugins/MapaCalor/mapaCalorAlmacenarDatos.php",{getUrls: "1"})
			.done(function(data){
				var aux = JSON.parse(data);
				var flag = false;
				$.each(aux,function(index,value){
				if(encodeURIComponent(document.location) == value) Urls=true;
				});
	},"json");
		
	$('#page').on('mousemove',function(e){

				if(Urls == true){
					if(arrayX.length >= 100){
			
						X='';
						for(x in arrayX){
							X = X + arrayX[x] + ",";
						}
						Y='';
						for(y in arrayY){
							Y = Y + arrayY[y] + ",";
						}			
		
						Pages='';
						for(p in arrayPage){
							Pages = Pages + arrayPage[p] + ",";
						}		
						$.post(ruta+"plugins/MapaCalor/mapaCalorAlmacenarDatos.php",{
							arrayX: X,
							arrayY: Y,
							arrayPage: Pages,
							insertMousemove: 1
						})
						arrayX.length=arrayY.length=arrayPage.length=arrayDate.length = 0;
					}	
	
					if((e.pageX- $('#page').offset().left) != 0 && e.pageY != 0){
						arrayX[arrayX.length] = e.pageX - $('#page').offset().left;
						arrayY[arrayY.length] = e.pageY;
						arrayPage[arrayPage.length] =  escape(document.location.pathname);
					}
				}
	});
	
//	if(Urls == true){
	html2canvas($('#page'),{
		onrendered: function(canvas){
			var dataUrl = canvas.toDataURL("image/png");
			$.post(ruta+"plugins/MapaCalor/mapaCalorAlmacenarDatos.php",{image: dataUrl,page: escape(document.location.pathname) });
		}
	});
//	}
	
    });


//####################### DRUPAL ########## 
}

};


})(jQuery, Drupal, this, this.document);
//####################### DRUPAL ##########

