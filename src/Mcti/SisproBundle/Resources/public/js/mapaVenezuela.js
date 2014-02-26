/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: SEPTIEMBRE DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$(document).ready(function()
{     
    $("#venBoton").hide();
    $("#venBoton").mouseover(function(){
        $(this).attr("src",$("#baseImg").val()+"img/venezuela_roja.png");
    });
    
    $("#venBoton").mouseleave(function(){
        $(this).attr("src",$("#baseImg").val()+"img/venezuela_gris.png");
    });
    
    cargarMapeo("E00");
    
    // AL PULSAR EL BOTON VENEZUELA
    $("#venBoton").click(function(){
        $(this).effect("puff",{}, 1000);
                
        $("#entidadClic").effect("pulsate",{}, 50,
          function(){
            $("#entidadClic").attr("src", $("#baseImg").val()+"img/mapas/blank.png");
            $("#entidadClic").removeAttr("entidad");
            $("#entidadClic").show();
            $("#mapa").html('');
        });
        
        $("#entidadFondo").effect("pulsate",{}, 50,
          function(){
            $("#entidadFondo").attr("src", $("#baseImg").val()+"img/mapas/E00.png");
            $("#entidadFondo").hide().fadeIn('slow');    
        });
        
        $("#Informacion").effect("puff",{}, 500,
          function(){
            $(this).html("");
            $(this).show();
        });  
        
        $("#datosINE").effect("puff",{}, 500,
          function(){
            $(this).html("");
            $(this).show();    
        });
        
        cargarMapeo("E00");
        $("#nombreEntidad").hide();
        $("#nombreEntidad").html("República Bolivariana de Venezuela").fadeIn('slow');        
    });
    programarArea();
    
});   // FINAL DEL DOCUMENT READY

// FUNCIONES ESPECIALES

function cargarMapeo(entidad)
{
 // CARGAMOS EL MAPEO DE LOS ESTADOS
     $.ajax({
          type:'POST',
          url: 'mapaVenezuela/cargarMapeo',
          data: { 'entidad' : entidad },
         // beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
                 $("#mapa").html(data);
                 programarArea();                 
          })
                
     /*.always(function() {
          $("#cargandoModal").hide();
          })*/
     
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
        
}

function programarArea()
{
    $("area").mouseover(function() {
	$("#entidad").attr("src", $("#baseImg").val()+"img/mapas/"+$(this).attr("id")+".png");
			});
    $("area").mouseleave(function() {
	$("#entidad").attr("src", $("#baseImg").val()+"img/mapas/blank.png");
			});
    // AL SELECCIONAR UNA ENTIDAD
    $("area").click(function() {
        if ($(this).attr("id").length<4) // SI LA ENTIDAD ES UN ESTADO
        {
           var ruta=$("#baseImg").val()+"img/mapas/"+$(this).attr("id")+"m.png";
	   $("#entidadFondo").effect("puff",{}, "slow",
               function(){
           $("#entidadFondo").attr("src", ruta);
           $("#entidadFondo").hide().fadeIn('slow');                 
               });
           $("#mapa").html('');
           cargarMapeo($(this).attr("id"));               
        }
        else  // SI LA ENTIDAD ES UN MUNICIPIO
        {
           var ruta=$("#baseImg").val()+"img/mapas/"+$(this).attr("id")+".png";
           $("#entidadClic").attr("src", ruta);
           cargarMunicipio($(this).attr("id")); // FUNCION SE ENCUENTRA EN proyectos.js
        }
            
        $("#entidadClic").attr("entidad", $(this).attr("id"));
        $("#nombreEntidad").hide();
        $("#nombreEntidad").html($(this).attr("title")).fadeIn('slow');
        
     /*   $("#Informacion").hide(); 
        $("#datosINE").hide();*/

        $("#venBoton").show();
        $("#venBoton").effect("shake",{},100);

        $("#entidad").attr("src",$("#baseImg").val()+"img/mapas/blank.png");
      
      });
}

/*
function cargarWiki()
{
        // CARGAMOS LOS DATOS DE LA ENTIDAD 
    $.ajax({
        type:'POST',
        url:'mapa_venezuela/cargar_wiki',
        data:{
              'entidad':$(this).attr("id")
             },
        beforeSend:function(){$("#cargandoModal").show();},
        complete: function(){
                    $("#cargandoModal").hide();},
        error: function(){
                    var Mensaje='Ha Ocurrido un Error al Intentar Cargar la Información.';
                    CajaDialogo('Error', Mensaje);},
        success: function(data){
                              $("#datosINE").html(data);
                              $("#datosINE").fadeIn('slow');
                               },
        dataType:'html'});
}

function actualizar()
{
   $.ajax({
   type:'POST',
   url:'mapa_venezuela/cargar_info',
   data:{
         'entidad':$("#entidadClic").attr("entidad"),
         'menu':trim($("#spanB1").attr("menu")),
         'forma':$("#hideB2").val()
        },
   beforeSend:function(){$("#cargandoModal").show();},
   complete: function(){
               $("#cargandoModal").hide();},               
   error: function(){
               var Mensaje='Ha Ocurrido un Error al Intentar Cargar la Información.';
               CajaDialogo('Error', Mensaje);},
   success: function(data){                                
                         $("#Informacion").html(data);
                         $("#Informacion").effect("slide",{}, 1000);
                         //$("#Informacion").fadeIn('slow');
                          },
   dataType:'html'});     
}*/