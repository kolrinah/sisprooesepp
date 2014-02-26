/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: JULIO DE 2013                                             *
 *  FRAMEWORK PHP UTILIZADO: SYMFONY Version 2.3.1                   *
 *                           http://www.symfony.com                  *
 *  TELEFONOS PARA SOPORTE: 0416-9052533 / 0212-5153033              *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

$(document).ready(function()
{       
    $('#proyectos').dataTable( {
	"sPaginationType": "full_numbers",
        "aaSorting": [[ 0, "asc" ],[ 1, "asc" ]],
        "oLanguage": {
                      "sProcessing": "Procesando...",
                      "sLengthMenu": "Mostrar _MENU_ Registros por página",
                      "sZeroRecords": "No se hallaron conicidencias.",
                      "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                      "sInfoEmpty": "Ningún Registro",
                      "sInfoFiltered": "(Total de Registros Filtrados: _MAX_)",
                      "sInfoPostFix": "",
                      "sSearch": "Buscar:",                      
	              "oPaginate": {
	                	"sFirst":    "Primero",
	                	"sPrevious": "Anterior",
	                	"sNext":     "Siguiente",
	                	"sLast":     "Último"	} }
			} );
                        
    $('.row').removeClass('oculto');    
    $('select[aria-controls]').addClass("span1");
    
    
} );
// FINAL DEL DOCUMENT READY

function nuevoProyecto()
{
    $.ajax({
            type:'GET',
            url:'nuevo',
            beforeSend:function(){$("#cargandoModal").show();},        
            dataType:'html'})
     .done(function(data) {
            $('#body').html(data);     
          })
     .always(function() {
            $("#cargandoModal").hide();
          })
     .fail(function(jqXHR, textStatus, errorThrown) {
            var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
            cajaDialogo(traductor(textStatus), mensaje);                
          });
    return true;
}

function guardarProyecto()
{
  if (!testForm01())
  {
    var mensaje='Llene los campos correctamente.';
    cajaDialogo("Alerta", mensaje);
    return false;
  }
  
  $.ajax({
       type:'POST',
       url:'nuevo',
       data: $("form").serialize(),
       beforeSend:function(){$("#cargandoModal").show();},        
       dataType:'json'})
  
   .done(function(data) {    
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){
                                editarProyecto(data.idProyecto, '02');                                
                                $( this ).dialog( "close" ); }};                            
                            var mensaje='Proyecto Registrado Satisfactoriamente.';
                            cajaDialogo('Guardar', mensaje, botones);                            
                        }else
                        { // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }        
     })         
     
   .always(function() {
      $("#cargandoModal").hide();
      })
    
   .fail(function(jqXHR, textStatus, errorThrown) {
      var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
      cajaDialogo(traductor(textStatus), mensaje);                
      });
  return true;
}

function eliminarProyecto(idProyecto)
{
  var mensaje='¿Está seguro que desea Eliminar este Proyecto?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'eliminarProyecto',
                        data: { 'id' : idProyecto },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){
                                window.location.reload( true );
                                $( this ).dialog( "close" ); }};                            
                            var mensaje='Proyecto Eliminado satisfactoriamente.';
                            cajaDialogo('Borrado', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje, botones);   
}

function editarProyecto(idProyecto, form)
{
    if(typeof(form) === 'undefined') form = '01';
    $.ajax({
          type:'GET',
          url:(form === '01')?'editarForm01':'editar',
          data: { 'idProyecto' : idProyecto, 'form': form },
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
          $('#body').html(data);
          $('#selector'+form).attr('checked','checked');          
          })
                
     .always(function() {
          $("#cargandoModal").hide();
          })
     
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function agregarObjetivoEspecifico(idProyecto)
{
    $.ajax({
            type:'GET',
            url:'objetivoEspecifico/agregar',
            data:{ 'idProyecto': idProyecto },
            beforeSend:function(){$("#cargandoModal").show();},        
            dataType:'html'})
     .done(function(data) {
            $('#ventanaModal').html(data);
            $('#ventanaModal').show();
            centrarModal('formObjetivoEspecifico');    
          })
     .always(function() {
            $("#cargandoModal").hide();
          })
     .fail(function(jqXHR, textStatus, errorThrown) {
            var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
            cajaDialogo(traductor(textStatus), mensaje);                
          });
    return true;
}

function guardarObjetivoEspecifico()
{
    if (!testFormObjetivoEspecifico())
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
  
    $.ajax({
       type:'POST',
       url:'objetivoEspecifico/agregar',
       data: $("form").serialize(),
       beforeSend:function(){$("#cargandoModal").show();},        
       dataType:'json'})
  
     .done(function(data) {    
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){
                                cancelarModal(); 
                                editarProyecto($('#idProyecto').val(), '10');                               
                                $( this ).dialog( "close" ); }};                            
                            var mensaje='Objetivo Específico Registrado Satisfactoriamente.';
                            cajaDialogo('Guardar', mensaje, botones);                            
                        }else
                        { // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }        
     })         
     
   .always(function() {
      $("#cargandoModal").hide();
      })
    
   .fail(function(jqXHR, textStatus, errorThrown) {
      var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
      cajaDialogo(traductor(textStatus), mensaje);                
      });
  return true;
}

function editarObjetivoEspecifico(idOe)
{    
    $.ajax({
          type:'GET',
          url:'objetivoEspecifico/editar',
          data: { 'idOe' : idOe },
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
          $('#ventanaModal').html(data);
          $('#ventanaModal').show();
          centrarModal('formObjetivoEspecifico');
          })
                
     .always(function() {
          $("#cargandoModal").hide();
          })
     
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function eliminarObjetivoEspecifico(idObjetivoEspecifico)
{
  var mensaje='¿Está seguro que desea Eliminar este Objetivo Específico?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'objetivoEspecifico/eliminar',
                        data: { 'id' : idObjetivoEspecifico },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                editarProyecto($('#idProyecto').val(), '10');
                                }};                            
                            var mensaje='Objetivo Específico Eliminado satisfactoriamente.';
                            cajaDialogo('Borrado', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje, botones);   
}

function agregarActividad(idOe)
{
    $.ajax({
            type:'GET',
            url:'actividad/agregar',
            data:{ 'idOe': idOe },
            beforeSend:function(){$("#cargandoModal").show();},        
            dataType:'html'})
     .done(function(data) {
            $('#ventanaModal').html(data);
            $('#ventanaModal').show();
            fechar();
            centrarModal('formActividad');    
          })
     .always(function() {
            $("#cargandoModal").hide();
          })
     .fail(function(jqXHR, textStatus, errorThrown) {
            var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
            cajaDialogo(traductor(textStatus), mensaje);                
          });
    return true;
}

function guardarActividad()
{
    if (!testFormActividad())
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
  
    $.ajax({
       type:'POST',
       url:'actividad/agregar',
       data: $("form").serialize(),
       beforeSend:function(){$("#cargandoModal").show();},        
       dataType:'json'})
  
     .done(function(data) {    
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){
                                cancelarModal(); 
                                editarProyecto($('#idProyecto').val(), '10');                               
                                $( this ).dialog( "close" ); }};                            
                            var mensaje='Actividad Registrada Satisfactoriamente.';
                            cajaDialogo('Guardar', mensaje, botones);                            
                        }else
                        { // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }        
     })         
     
   .always(function() {
      $("#cargandoModal").hide();
      })
    
   .fail(function(jqXHR, textStatus, errorThrown) {
      var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
      cajaDialogo(traductor(textStatus), mensaje);                
      });
  return true;
}

function editarActividad(idActividad)
{
    $.ajax({
          type:'GET',
          url:'actividad/editar',
          data: { 'idActividad' : idActividad },
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
          $('#ventanaModal').html(data);
          $('#ventanaModal').show();
          centrarModal('formActividad');
          fechar();
          })
                
     .always(function() {
          $("#cargandoModal").hide();
          })
     
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function eliminarActividad(idActividad)
{
  var mensaje='¿Está seguro que desea Eliminar esta Actividad?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'actividad/eliminar',
                        data: { 'id' : idActividad },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                editarProyecto($('#idProyecto').val(), '10');
                                }};                            
                            var mensaje='Actividad Eliminada satisfactoriamente.';
                            cajaDialogo('Borrado', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje, botones);   
}

function actualizarProyecto(form)
{
    if(typeof(form) == 'undefined') form = '01';
    if (!testForm(form))
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }

    $.ajax({
         type:'POST',
         url:(form === '01')?'editarForm01':'editar',
         data: $("form").serialize(),
         beforeSend:function(){$("#cargandoModal").show();},        
         dataType:'json'})
    
     .done(function(data) {    
                          if (data.error==='') // OPERACION EXITOSA
                          {
                              var botones={Cerrar: function(){                                
                                  $( this ).dialog( "close" ); }};                            
                              var mensaje='Cambios Guardados Satisfactoriamente.';
                              cajaDialogo('Exito', mensaje, botones);                            
                          }else 
                          { // ERROR DESCONOCIDO                                    
                              var botones={Cerrar: function(){
                                  $( this ).dialog( "close" );}};
                              var mensaje=data.error;
                              cajaDialogo('Error', mensaje, botones);                            
                          } 
       })         
       
     .always(function() {
        $("#cargandoModal").hide();
        })
      
     .fail(function(jqXHR, textStatus, errorThrown) {
        var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
        cajaDialogo(traductor(textStatus), mensaje);                
        });
    return true;
}

function actualizarObjetivoEspecifico()
{
    if (!testFormObjetivoEspecifico())
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
    
    $.ajax({
         type:'POST',
         url:'objetivoEspecifico/editar',
         data: $("form").serialize(),
         beforeSend:function(){$("#cargandoModal").show();},        
         dataType:'json'})
    
     .done(function(data) {    
                          if (data.error==='') // OPERACION EXITOSA
                          {
                              var botones={Cerrar: function(){                                
                                  $( this ).dialog( "close" );
                                  cancelarModal(); 
                                  editarProyecto($('#idProyecto').val(), '10'); }};                            
                              var mensaje='Cambios Guardados Satisfactoriamente.';
                              cajaDialogo('Exito', mensaje, botones);                            
                          }else 
                          { // ERROR DESCONOCIDO                                    
                              var botones={Cerrar: function(){
                                  $( this ).dialog( "close" );}};
                              var mensaje=data.error;
                              cajaDialogo('Error', mensaje, botones);                            
                          } 
       })         
       
     .always(function() {
        $("#cargandoModal").hide();
        })
      
     .fail(function(jqXHR, textStatus, errorThrown) {
        var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
        cajaDialogo(traductor(textStatus), mensaje);                
        });
    return true;
}
        
function actualizarActividad()
{
    if (!testFormActividad())
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
    
    $.ajax({
         type:'POST',
         url:'actividad/editar',
         data: $("form").serialize(),
         beforeSend:function(){$("#cargandoModal").show();},        
         dataType:'json'})
    
     .done(function(data) {    
                          if (data.error==='') // OPERACION EXITOSA
                          {
                              var botones={Cerrar: function(){                                
                                  $( this ).dialog( "close" );
                                  cancelarModal(); 
                                  editarProyecto($('#idProyecto').val(), '10'); }};                            
                              var mensaje='Cambios Guardados Satisfactoriamente.';
                              cajaDialogo('Exito', mensaje, botones);                            
                          }else 
                          { // ERROR DESCONOCIDO                                    
                              var botones={Cerrar: function(){
                                  $( this ).dialog( "close" );}};
                              var mensaje=data.error;
                              cajaDialogo('Error', mensaje, botones);                            
                          } 
       })         
       
     .always(function() {
        $("#cargandoModal").hide();
        })
      
     .fail(function(jqXHR, textStatus, errorThrown) {
        var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
        cajaDialogo(traductor(textStatus), mensaje);                
        });
    return true;
}

function enviarProyecto()
{
  var mensaje='¿Está seguro que desea Enviar el Proyecto? '+
              'Luego de hacerlo no podrá realizar modificaciones.';
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'editar',
                        data: $("form").serialize(),
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                window.location=$('#baseURL').val()+'proyectos';
                                }};                            
                            var mensaje='Proyecto Enviado satisfactoriamente.';
                            cajaDialogo('Exito', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje, botones); 
   
   return true;
}

function solicitarPermisoEdicionProyecto()
{
  if (trim($('#observaciones').val()).length < 10)
  {
    var mensaje='Explique brevemente las razones de su solicitud.';
    cajaDialogo("Alerta", mensaje);
    return false;
  }    
  var mensaje='¿Está seguro que desea Solicitar a la OESEPP permiso '+
              'para realizar modificaciones sobre este Proyecto?';
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'editar',
                        data: $("form").serialize(),
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                window.location=$('#baseURL').val()+'proyectos';
                                }};                            
                            var mensaje='Solicitud enviada satisfactoriamente.';
                            cajaDialogo('Exito', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje, botones); 
   
   return true;
}

function permisoEdicionProyecto(x)
{
  var mensaje1, mensaje2;  
  if (x === 1) // Permiso Aprobado
  {
      mensaje1= '¿Está seguro de otorgar permisos de edición sobre este Proyecto?';
      mensaje2= 'Permisos de edición otorgados satisfactoriamente.';
  }
  else
  {
      mensaje1= '¿Está seguro de negar permisos de edición sobre este Proyecto?';
      mensaje2= 'Los Permisos de edición han sido negados.';
  }      
  
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'editar',
                        data: {'data[idProyecto]':$('#idProyecto').val(),
                               'data[form]':'11ar',
                               'data[aprobado]': x },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                window.location=$('#baseURL').val()+'proyectos';
                                }};                            
                            var mensaje=mensaje2;
                            cajaDialogo('Exito', mensaje, botones);                            
                        }else
                        {
                            // ERROR DESCONOCIDO                                    
                            var botones={Cerrar: function(){
                                $( this ).dialog( "close" );}};
                            var mensaje=data.error;
                            cajaDialogo('Error', mensaje, botones);                            
                        }                        
                   })
                                
                  .always(function() {
                       $("#cargandoModal").hide();
                       })
                  
                  .fail(function(jqXHR, textStatus, errorThrown) {
                                var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
                                cajaDialogo(traductor(textStatus), mensaje);                
                                });               
                 $(this).dialog('close');} };
   cajaDialogo('Pregunta', mensaje1, botones); 
   
   return true;
}

function estadoCambia()
{  
  $.ajax({
        type:'POST',
        url:'getMunicipios',
        data: {'id':$('#idEstado').val()}, 
        beforeSend:function()
                   {
                     limpiaCampo('idMunicipio');  
                     limpiaCampo('idParroquia'); 
                     limpiaPoblado();
                   },
        dataType:'html'})     
  .done(function(data) { 
        if ($('#idEstado').val()!=0)        
        {    
           $('#idMunicipio').html(data);
           $('#idMunicipio').removeAttr('disabled');                    
        } })  
  
  .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function municipioCambia()
{   
  $.ajax({
        type:'POST',
        url:'getParroquias',
        data: {'id':$('#idMunicipio').val()}, 
        beforeSend:function()
                   {
                     limpiaCampo('idParroquia');
                     limpiaPoblado();
                     autocompletar();
                   },
        dataType:'html'})     
  .done(function(data) {           
        if ($('#idMunicipio').val()!=0)        
        {    
           $('#idParroquia').html(data);
           $('#idParroquia').removeAttr('disabled');
           $('#poblado').removeAttr('disabled').focus();
        } }) 
  
  .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function estructuraCambia()
{  
  var idUsuario = $('#idEstructura').val();  
  limpiaCampo('idUsuario');  
  limpiaCampo('cargo'); 
  limpiaCampo('telefono');   
  if ($('#idEstructura').val() ==='0' ) return true;
    
  $.ajax({
        type:'POST',
        url:'getUsuarios',
        data: {'idEstructura':$('#idEstructura').val(),
               'idUsuario':idUsuario  }, 
        beforeSend:function() { },
        dataType:'html'})     
  .done(function(data) { 
        if ($('#idEstructura').val()!=='0')        
        {    
           $('#idUsuario').html(data);
           $('#idUsuario').removeAttr('disabled');                    
        } })  
  
  .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function usuarioCambia()
{  
  limpiaCampo('cargo'); 
  limpiaCampo('telefono');   
  if ($('#idUsuario').val() ==='0' ) return true;
    
  $.ajax({
        type:'POST',
        url:'getUsuario',
        data: {'idUsuario':$('#idUsuario').val() },                
        beforeSend:function() { },
        dataType:'json'})     
  .done(function(data) { 
           $('#cargo').html(data.cargo);
           $('#telefono').html(data.telefono);
         })  
  
  .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function limpiaCampo(campo)
{    
    $('#'+campo).html('');
    $('#'+campo).val('');
    $('#'+campo).attr('disabled', 'disabled');
}

function limpiaPoblado()
{   
   if ($('#poblado').hasClass('ui-autocomplete-input'))
   {
       $('#poblado').autocomplete( "destroy" );
   }   
   $('#poblado').attr('disabled','disabled');
   $('#idPoblado').val(0);
   $('#poblado').val('Escriba el nombre del poblado y seleccione');    
}

function autocompletar()
{  
     $('#poblado').autocomplete({
     minLength:0, //Indicamos que busque a partir de haber escrito uno o mas caracteres en el input
     delay:0,
     position:{my: "left top", at: "left bottom", collision: "none"},
     source: function(request, response)
             {
                var url="getPoblados";  //url donde buscará la lista
                $.post(url,{'frase':request.term, 'idMunicipio':$('#idMunicipio').val()}, response, 'json');
             },
     focus: function( event, ui ) 
            {
                $("#idPoblado").val( ui.item.id );
                $("#poblado").val( ui.item.poblado );                
                return false;
            },                     
     select: function( event, ui ) 
             {                  
                $("#idPoblado").val( ui.item.id );
                $("#poblado").val( ui.item.poblado );                
                return false;
             }
     }).data( "ui-autocomplete" )._renderItem = function( ul, item) {
                return $( "<li></li>" )                
                .data( "item.autocomplete", item )
		.append( "<a>" + item.poblado + "</a>" )
                .appendTo( ul );
	  };     
     
     // AUTOCOMPLETAR
     var pobMsj='Escriba el nombre del poblado y seleccione';
 
     if ($("#idPoblado").val()==='0')
     {
        $("#poblado").val(pobMsj);
     }
   
     $("#poblado").focusin(function(){$("#poblado").val('');
                                      $("#idPoblado").val(0);});
                        
     $("#poblado").focusout(function(){
                    if ($("#idPoblado").val()==='0')$("#poblado").val(pobMsj);                    
                    });
     $("#poblado").keypress(function(){$("#idPoblado").val(0);});
}

function cambiaMarco(boton)
{  
   if ($('#marco'+boton).is(':checked')) 
   {
       $('#codigo'+boton).removeAttr('disabled');
       $('#year'+boton).removeAttr('disabled');
       $('#tag'+boton).removeClass('opaco');
       $('#codigo'+boton).focus();
   }
   else
   {
       limpiaCampo('codigo'+boton);
       limpiaCampo('year'+boton);
       $('#tag'+boton).addClass('opaco');
   }
}

function cambiaMoneda(obj)
{
    var id = obj.attr('id').substring(6);
    $('#simboloMoneda'+id).html($('#simbolo'+obj.val()).val());
}

function actualizaInversionTotal()
{
    inversionTotal=0;
    $('.row-fluid').find("[id*=moneda]").each(function() { 
         if ($(this).val() != 0)
         {
             var id = $(this).attr('id').substring(6);
             var precio = $('#precio'+$(this).val()).val();
             var monto = nroPuro($('#monto'+id).val());
             inversionTotal = inversionTotal + (precio * monto);
         }
    }); 
    n = inversionTotal.toString().replace(/\,/g,'').replace(/\./g,',');
    $('#inversionTotal').html(formatNumber(n));
}

function cambiaFuente(obj)
{    
    var id = obj.attr('id').substring(6);
    
    if ($(obj).is(':checked')) 
    {
       $('#moneda'+id).removeAttr('disabled');
       $('#monto'+id).removeAttr('disabled');
    }
    else
    {
       $('#moneda'+id).attr('disabled','disabled');
       $('#monto'+id).attr('disabled','disabled');
       $('#monto'+id).val('0,00');
       $('#simboloMoneda'+id).html('');
       $('#moneda'+id).val(0);
    }
    actualizaInversionTotal();
}

function cambiaTipo(boton)
{  
   if ($('#tipo'+boton).is(':checked'))
   {
       $('#tag'+boton).removeClass('opaco');      
       $('#tag2_'+boton).removeClass('oculto');
   }
   else 
   {
       $('#tag'+boton).addClass('opaco');
       $('#tag2_'+boton).addClass('oculto');
   }   
}

function cambiaAreaEstrategica(boton)
{  
   if ($('#tipo'+boton).is(':checked'))
   {
       $('#tag'+boton).removeClass('opaco');      
       $('#tag2_'+boton).removeClass('oculto');
       $(":checkbox[id*='tipo'][id!='tipo"+boton+"']").removeAttr('checked');
       $("div[id*='tag'][id!='tag"+boton+"']").addClass('opaco');
       $("span[id*='tag2_'][id!='tag2_"+boton+"']").addClass('oculto');
   }
   else 
   {
       $('#tag'+boton).addClass('opaco');
       $('#tag2_'+boton).addClass('oculto');
   }   
}

function cambiaOna(boton)
{  
   if ($('#tipo'+boton).is(':checked')) $('#tag'+boton).removeClass('opaco');
   else $('#tag'+boton).addClass('opaco');       
   
   var objesp = $('#objesp'+boton).val();   
      
   // OBTENEMOS EL DIV PADRE ONA
   var padre= $('#tipo'+boton).parents("[id*=ona]");
   var chk =false;              //$(padre).css('background-color', 'red');
   // RECORREMOS LOS ELEMENTOS INPUT CHECKBOX DENTRO DEL PADRE   
  
   $(padre).find("[id*=tipo]").each(function() { 
       if ($(this).is(':checked')) chk = true;
   });   
  
   if (chk===true) $('#'+objesp).removeClass('oculto');
   else $('#'+objesp).addClass('oculto');
}

// Carga Municipio desde su codigo ONAPRE
function cargarMunicipio(codigoOnapre)
{
    // Desmarca la opción a nivel nacional
    $('#nacional').removeAttr('checked');
    $('#tag').addClass('opaco');
    $("#municipios").removeClass('oculto');
    
    var municipios = new Array();
    // Verificamos los Municipios actualmente seleccionados para no repetirlos
    $("#municipios").find('input[type=checkbox][id*="municipio"]').each(function(){
        municipios.push($(this).val());
    });
    
    // Buscamos el municipio seleccionado
    $.ajax({
          type:'POST',
          url: 'cargarMunicipio',
          data: { 'codigoOnapre' : codigoOnapre,
                  'municipios': municipios},
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
                 $("#municipios").append(data);                                  
          })
          
     .always(function() {
          $("#cargandoModal").hide();
          })
          
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
}

function setPobTotal()
{
    suma = nroFormatoUS($('#pobFemenina').val()) +  nroFormatoUS($('#pobMasculina').val());
   
    if (!isNaN(suma)) 
    {   
        $('#pobTotal').val(suma);
    }
    else $('#pobTotal').val(0);
    $('#pobTotal').focus();
}

function showOna(id)
{  
   if ($('#ona'+id).hasClass('oculto')) 
   {
       $('#ona'+id).removeClass('oculto');       
   }
   else
   {
       $('#ona'+id).addClass('oculto');
   }
}

function showCoordenadas()
{  
   if ($('#coordenadas').is(':checked')) 
   {
       $('#latitud').removeClass('oculto');
       $('#longitud').removeClass('oculto');       
   }
   else
   {
       $('#latitud').addClass('oculto');
       $('#longitud').addClass('oculto');
       limpiaCoordenadas();
   }
}

function limpiaCoordenadas()
{    
    $('#latGra').val('');
    $('#latMin').val('');
    $('#latSeg').val('');
    $('#lonGra').val('');
    $('#lonMin').val('');
    $('#lonSeg').val('');
}

function hideMunicipios()
{
   if ($('#nacional').is(':checked')) 
   {
       $('#municipios').addClass('oculto');
       $('#municipios').html('');
       $('#tag').removeClass('opaco');        
   }
   else
   {
       $('#municipios').removeClass('oculto');  
       $('#tag').addClass('opaco');
   }    
}

function hidePlanOrg()
{
   if ($('#nacional').is(':checked')) 
   {
       $('#planOrg').removeClass('oculto');  
       $('#tag').removeClass('opaco');
       $('#tag').html('Ocultar Planificación Original')
   }
   else
   {
       $('#planOrg').addClass('oculto');       
       $('#tag').addClass('opaco');
       $('#tag').html('Mostrar Planificación Original')
   }    
}

// COLOCAR LOS SELECTORES DE FECHAS
function fechar()
{
  $( "#fechaIni" ).datepicker({
            showOn: "both",
            buttonImage: $('#baseImg').val()+"img/cal.gif",
            buttonText:"clic para Seleccionar",
            buttonImageOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat:"dd/mm/yy",
            currentText:"Hoy",
            nextText:"Sig",
            defaultDate:$('#fechaIni').val(),
            //minDate:_diaHoy(),
            maxDate:$('#fechaFin').val(),
            dayNames:[ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
            dayNamesMin:[ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
            monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            onSelect: function( selectedDate ) {
                $( "#fechaFin" ).datepicker( "option", "minDate", selectedDate )}            
        });
   
  $( "#fechaFin" ).datepicker({
            showOn: "both",
            buttonImage: $('#baseImg').val()+"img/cal.gif",
            buttonText:"clic para Seleccionar",
            buttonImageOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat:"dd/mm/yy",
            nextText:"Sig",        
            defaultDate:$('#fechaFin').val(),
            minDate:$('#fechaIni').val(), 
           // minDate:_fechaMayor($('#fechaIni').val(),_diaHoy()), 
            //maxDate: ,
            dayNames:[ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
            dayNamesMin:[ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
            monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            onSelect: function( selectedDate ) {
                $( "#fechaIni" ).datepicker( "option", "maxDate", selectedDate )}
        });
}

// SELECTOR DE VALIDADOR DE FORMULARIO DE PROYECTOS
function testForm(form)
{
    if(typeof(form) == 'undefined') form = '01';
    switch (form)
    {
        case '01': return testForm01();
                   break;
        case '02': return testForm02();
                   break;
        case '03': return true;
                   break;
        case '04': return true;
                   break;
        case '05': return true;
                   break;
        case '06': return true;
                   break;
        case '07': return testForm07();
                   break;
        case '08': return testForm08();
                   break;
        case '09': return testForm09();
                   break;
        case '10': return testForm10();
                   break;
        case '11': return testForm11();
                   break;
        default:   return true;
    }
}

// VALIDACION DEL FORMULARIO 01 DE PROYECTOS
function testForm01()
{
  var test = true;  
  $('p').removeClass('campoInvalido').removeAttr('title');
  
 /* if (trim($('#proyecto_codigo').val()).length<2)
  {
      $('#proyecto_codigo').parent().addClass('campoInvalido') 
                           .attr('title','Campo inválido');                   
      test = false;
  }*/

  if (trim($('#proyecto_nombre').val()).length<4)
  {
      $('#proyecto_nombre').parent().addClass('campoInvalido') 
                           .attr('title','Campo inválido');                   
      test = false;
  }  
  
  if (trim($('#proyecto_descripcion').val()).length<4)
  {
      $('#proyecto_descripcion').parent().addClass('campoInvalido') 
                                .attr('title','Campo inválido');                   
      test = false;
  }  
  
  if (trim($('#proyecto_problema').val()).length<4)
  {
      $('#proyecto_problema').parent().addClass('campoInvalido') 
                             .attr('title','Campo inválido');                   
      test = false;
  }  

  if (trim($('#proyecto_direccion').val()).length<4)
  {
      $('#proyecto_direccion').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido');                   
      test = false;
  }
  
  if ($('#idEstado').val()==='0')
  {
      $('#idEstado').parent().addClass('campoInvalido') 
                              .attr('title','Debe seleccionar un estado');                   
      test = false;
  }
  
  if ($('#idMunicipio').val()==='0')
  {
      $('#idMunicipio').parent().addClass('campoInvalido') 
                              .attr('title','Debe seleccionar un municipio');                   
      test = false;
  }  
  
  if ($('#idParroquia').val()==='0' || $('#idParroquia').val()===null)
  {
      $('#idParroquia').parent().addClass('campoInvalido') 
                              .attr('title','Debe seleccionar una parroquia');
      test = false;
  }  

  if ($('#idPoblado').val()==='0' || $('#idPoblado').val()===null)
  {
      $('#idPoblado').parent().addClass('campoInvalido') 
                              .attr('title','Debe seleccionar un poblado');                   
      test = false;
  }
  
  // Si Coordenadas está activo
  if ($('#coordenadas').is(':checked')) 
  {
      // CHEQUEO DE LATITUD
      if ($('#latGra').val() > 12 || $('#latGra').val() < 0 )
      {
         $('#latGra').parent().addClass('campoInvalido') 
                              .attr('title','Coordenadas no válidas para Venezuela'); 
         test=false;
      }    
      // CHEQUEO DE LONGITUD
      if ($('#lonGra').val() > 73 || $('#lonGra').val() < 58 )
      {
         $('#lonGra').parent().addClass('campoInvalido') 
                              .attr('title','Coordenadas no válidas para Venezuela'); 
         test=false;          
      }
      // CHEQUEO DE MINUTOS Y SEGUNDOS DE LATITUD
      if ($('#latMin').val() > 59 || nroFormatoUS($('#latSeg').val()) > 59.99)
      {
         $('#latMin').parent().addClass('campoInvalido') 
                              .attr('title','Coordenadas inválidas'); 
         test=false;          
      }   
      // CHEQUEO DE MINUTOS Y SEGUNDOS DE LONGITUD
      if ($('#lonMin').val() > 59 || nroFormatoUS($('#lonSeg').val()) > 59.99)
      {
         $('#lonMin').parent().addClass('campoInvalido') 
                              .attr('title','Coordenadas inválidas'); 
         test=false;                    
      }    
      
      // CHEQUE DE CAMPOS NULOS EN LATITUD
      if (isNaN($('#latGra').val()) || isNaN($('#latMin').val()) || //isNaN($('#latSeg').val()) ||
          trim($('#latGra').val())==='' || trim($('#latMin').val())==='' || trim($('#latSeg').val())==='' )
      {
         $('#latMin').parent().addClass('campoInvalido') 
                              .attr('title','Campos inválidos'); 
          test=false;
      }
      
      // CHEQUE DE CAMPOS NULOS EN LONGITUD
      if (isNaN($('#lonGra').val()) || isNaN($('#lonMin').val()) || //isNaN($('#lonSeg').val()) ||
          trim($('#lonGra').val())==='' || trim($('#lonMin').val())==='' || trim($('#lonSeg').val())==='' )
      {
         $('#lonMin').parent().addClass('campoInvalido') 
                              .attr('title','Campos inválidos'); 
          test=false;
      }      
  }
  
  // INICIALIZACION DE FORMULARIO 01 DE PROYECTO NUEVO
  var f='proyecto_';
  $('#'+f+'objetivoGeneral').val('00');
  $('#'+f+'producto').val('00');
  $('#'+f+'unidadMedida').val('00');
  $('#'+f+'indicador').val('00');
  $('#'+f+'alcance').val('00');
  $('#'+f+'puntoycirculo').val('00'); 
  
  return test;
}

// VALIDACION DEL FORMULARIO 02 DE PROYECTOS
function testForm02()
{
  var test = true;  
  $('p').removeClass('campoInvalido').removeAttr('title');
  
  if ($('#idEstructura').val()==='0')
  {
      $('#idEstructura').parent().addClass('campoInvalido') 
                        .attr('title','Debe seleccionar la Unidad Ejecutora');
      test = false;
  }
  
  if ($('#idUsuario').val()==='0')
  {
      $('#idUsuario').parent().addClass('campoInvalido') 
                     .attr('title','Debe seleccionar el Usuario Responsable');                   
      test = false;
  }
  
  //VERIFICAMOS LOS CAMPOS DE TEXTO year
  $("[id*=year][disabled!=disabled]").each(function() {
     
      if (isNaN(parseInt($(this).val())) || parseInt($(this).val())<1990
            || parseInt($(this).val())>2021 )
      {
         $(this).parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un año correcto');
         test = false;
      }      
  });
  
  //VERIFICAMOS LOS CAMPOS DE TEXTO codigo
  $("[id*=codigo][disabled!=disabled]").each(function() {     
     
      if (trim($(this).val())=='')
      {
         $(this).parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir el cógido');
         test = false;
      }       
  });  
  
  return test;
}

// VALIDACION DEL FORMULARIO 07 DE PROYECTOS
function testForm07()
{
    var test = true;
    $('p').removeClass('campoInvalido').removeAttr('title');      
    
    if (trim($('#objetivoGeneral').val()).length < 4)
    {
        $('#objetivoGeneral').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }    
    
    if (trim($('#producto').val()).length < 4)
    {
        $('#producto').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }
    
    if (trim($('#unidadMedida').val()).length < 1)
    {
        $('#unidadMedida').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }
    
    if (trim($('#unidadMedida').val()).length > 50)
    {
        $('#unidadMedida').parent().addClass('campoInvalido') 
                        .attr('title','No debe superar los 50 caracteres');
        test = false;
    }    
    
    /*if (trim($('#indicador').val()).length < 4)
    {
        $('#indicador').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }*/

    if (trim($('#alcance').val()).length < 4)
    {
        $('#alcance').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }
    
 /*   if (trim($('#puntoycirculo').val()).length < 4)
    {
        $('#puntoycirculo').parent().addClass('campoInvalido') 
                        .attr('title','Campo inválido');
        test = false;
    }   */ 
    
    // CHEQUEO DE CAMPOS NUMERICOS
    if (nroFormatoUS($('#meta').val())=== 0 || trim($('#meta').val())==='' )
    {
         $('#meta').parent().addClass('campoInvalido') 
                              .attr('title','La meta física debe ser mayor que cero'); 
          test = false;
    }
    
    if ( trim($('#pobFemenina').val())==='' )
    {
         $('#pobFemenina').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }    
    
    if ( trim($('#pobMasculina').val())==='' )
    {
         $('#pobMasculina').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }
    
    if ( trim($('#pobTotal').val())==='' )
    {
         $('#pobTotal').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }
    
    if ( ( (parseInt(nroFormatoUS($('#pobFemenina').val())) + parseInt(nroFormatoUS($('#pobMasculina').val())))
            !== parseInt(nroFormatoUS($('#pobTotal').val())) ) &&
         ( (parseInt(nroFormatoUS($('#pobFemenina').val())) + parseInt(nroFormatoUS($('#pobMasculina').val())))!== 0) )
    { 
         $('#pobTotal').parent().addClass('campoInvalido') 
                       .attr('title','La Población Total debe ser igual a Población Femenina + Población Masculina'); 
         test = false;
    }

    if ( trim($('#empleosDirectosEjecucion').val())==='' )
    {
         $('#empleosDirectosEjecucion').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }
    
    if ( trim($('#empleosDirectosOperacion').val())==='' )
    {
         $('#empleosDirectosOperacion').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }    
    
    if ( trim($('#empleosIndirectosEjecucion').val())==='' )
    {
         $('#empleosIndirectosEjecucion').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }
    
    if ( trim($('#empleosIndirectosOperacion').val())==='' )
    {
         $('#empleosIndirectosOperacion').parent().addClass('campoInvalido') 
                              .attr('title','Campo inválido'); 
          test = false;
    }        
    
    return test;
}

// VALIDACION DEL FORMULARIO 08 DE PROYECTOS
function testForm08()
{
   $('p').removeClass('campoInvalido').removeAttr('title');  
   if (  ( ($('#nacional').is(':checked')) && (trim($('#municipios').html()) ==='') )
      || ( (!$('#nacional').is(':checked')) && (trim($('#municipios').html()) !='') )
      ) 
   {       
      return true;
   }
   
   var botones={Cerrar: function(){
   $( this ).dialog( "close" );}};
   var mensaje='Si el proyecto no es a nivel nacional, debe seleccionar al menos un Municipio.';
   cajaDialogo('Alerta', mensaje, botones);
   return false;
}

// VALIDACION DEL FORMULARIO 09 DE PROYECTOS
function testForm09()
{
  var test = true;    
  $('p').removeClass('campoInvalido').removeAttr('title'); 
  
  //VERIFICAMOS LOS CAMPOS DE TEXTO monto
  $("[id*=monto][disabled!=disabled]").each(function() {
     
      if (isNaN(parseFloat(nroPuro($(this).val()))) || parseFloat(nroPuro($(this).val())) == 0 )
      {
         $(this).parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un monto correcto');
         test = false;
      }      
  });
  
  //VERIFICAMOS LOS CAMPOS SELECT moneda
  $("[id*=moneda][disabled!=disabled]").each(function() {     
     
      if ($(this).val()=='0')
      {
         $(this).parent().addClass('campoInvalido') 
                         .attr('title','Debe Seleccionar un tipo de moneda');
         test = false;
      }       
  });  
  
  return test;
}

// VALIDACIÓN DEL FORMULARIO DE OBJETIVO ESPECIFICO
function testFormObjetivoEspecifico()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 

   //VERIFICAMOS CAMPO DE TEXTO CODIGO
   if (trim($('#codigo').val()) === '' || parseInt(nroPuro($('#codigo').val())) === 0 )
   {
         $('#codigo').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un Número correcto');
         test = false;
   }   
   
   //VERIFICAMOS CAMPO DE TEXTO OBJETIVO ESPECIFICO
   if (trim($('#objetivoEspecifico').val()).length < 4 )
   {
         $('#objetivoEspecifico').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un Objetivo correcto');
         test = false;
   }    
   return test;
}

// VALIDACIÓN DEL FORMULARIO DE ACTIVIDAD
function testFormActividad()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 

   //VERIFICAMOS CAMPO DE TEXTO CODIGO
   if (trim($('#codigo').val()) === '' || parseInt(nroPuro($('#codigo').val())) === 0 )
   {
         $('#codigo').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un Número correcto');
         test = false;
   }   
   
   //VERIFICAMOS CAMPO DE TEXTO ACTIVIDAD
   if (trim($('#actividad').val()).length < 4 )
   {
         $('#actividad').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una descripción correcta');
         test = false;
   } 
   
   //VERIFICAMOS CAMPO DE TEXTO META
   if (trim($('#metaFisica').val()) === '' || parseInt(nroPuro($('#metaFisica').val())) === 0 )
   {
         $('#metaFisica').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un Número correcto');
         test = false;
   }   
   
   //VERIFICAMOS CAMPO DE TEXTO UNIDAD MEDIDA
   if (trim($('#unidadMedida').val()).length < 2 )
   {
         $('#unidadMedida').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una descripción correcta');
         test = false;
   } 
   
   //VERIFICAMOS CAMPO DE TEXTO UNIDAD MEDIDA
   if (trim($('#unidadMedida').val()).length > 50 )
   {
         $('#unidadMedida').parent().addClass('campoInvalido') 
                         .attr('title','No debe superar los 50 caracteres');
         test = false;
   }    
   
   //VERIFICAMOS CAMPO DE MONTO
   if (trim($('#monto').val()) === '' || parseInt(nroPuro($('#monto').val())) === 0 )
   {
         $('#monto').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un monto correcto');
         test = false;
   }  
   
   //VERIFICAMOS CAMPO FECHA INI
   if (trim($('#fechaIni').val()) === '' )
   {
         $('#fechaIni').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una fecha correcta');
         test = false;
   } 
   
   //VERIFICAMOS CAMPO FECHA FIN
   if (trim($('#fechaFin').val()) === '' )
   {
         $('#fechaFin').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una fecha correcta');
         test = false;
   }     
   
   return test;
}