
(function (require,$) {
  	
    $( document ).ajaxComplete(function(){
       $( "#submitHeatMap" ).click( function() {
		var parametros = {};
		parametros.url = encodeURIComponent($('#url').val());
		parametros.module = 'MapaCalor';
		parametros.action = 'recuperarPuntos';
		
		var ajax = new ajaxHelper();
		ajax.addParams(parametros,'get');
   		ajax.setUrl('index.php?module=MapaCalor&action=recuperarPuntos&idSite=1');
    		ajax.setCallback(function (response) {
	        	$('#heatMap').html(response);
		});
		ajax.setFormat('html'); // the expected response format
    		ajax.send();

	});
	
	$( "#submitUrlMC" ).off().on('click',function() {
		var flag = true;
		$('.required').each(function(){
			$(this).removeClass("empty");
			if($(this).val() == ''){
				$(this).addClass("empty");
				flag = false;
			}
		});
		if(flag == true){
		var parametros = {};
		parametros.url = encodeURIComponent($('#url').val());
		parametros.module = 'MapaCalor';
		parametros.action = 'anhadirUrl';
		
		var ajax = new ajaxHelper();
		ajax.addParams(parametros,'get');
   		ajax.setUrl('index.php?module=MapaCalor&action=anhadirUrl&idSite=1');
    		ajax.setCallback(function (response) {
	        	$('#content').html(response);
		});
		ajax.setFormat('html'); // the expected response format
    		ajax.send();
		}
	});	
 
	$( '[id^=eliminarUrl]' ).click(function(){
		
		if(confirm("Desea eliminar la url?") == true){
		var id = $(this).attr('value');
		
		var parameters = {};
		parameters.module = 'API';
		parameters.action = 'eliminarUrl';
		parameters.id = id;

		var ajax = new ajaxHelper();
		ajax.addParams(parameters,'get');
   		ajax.setUrl('index.php?module=MapaCalor&action=eliminarUrl&idSite=1');
    		ajax.setCallback(function (response) {
	        	$('#content').html(response);
		});
		ajax.setFormat('html'); // the expected response format
    		ajax.send();
		}		
	});

	$( '[id^=urlMapaCalor]' ).click(function(){
		
		var url = $(this).attr('value');
		
		var parameters = {};
		parameters.module = 'MapaCalor';
		parameters.action = 'recuperarPuntos';
		parameters.url = url.replace(/^.*\/\/[^\/]+/, '');

		var ajax = new ajaxHelper();
		ajax.addParams(parameters,'get');
   		ajax.setUrl('index.php?module=MapaCalor&action=recuperarPuntos');
    		ajax.setCallback(function (response) {
	        	$('#mapaCalor').html(response);
		});
		ajax.setFormat('html'); // the expected response format
    		ajax.send();
	});
 
    })
   })(require, jQuery); 

/*
(function (require) {
	var broadcast = require('broadcast');
	broadcast.propagateNewPage('module=Proba&action=recibir',true);	
})(require);
*/





