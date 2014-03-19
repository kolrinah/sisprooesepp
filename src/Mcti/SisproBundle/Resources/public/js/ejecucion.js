/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  SISTEMA DE SEGUIMIENTO DE PROYECTOS DEL MPPCTI Y ENTES ADSCRITOS *
 *  DESARROLLADO POR: ING.REIZA GARCÍA                               *
 *                    ING.HÉCTOR MARTÍNEZ                            *
 *  PARA:  OFICINA ESTRATÉGICA DE SEGUIMIENTO Y EVALUACION DE        *
 *         POLÍTICAS PÚBLICAS (OESEPP)                               *
 *   DEL:  MINISTERIO DEL PODER POPULAR PARA CIENCIA, TECNOLOGÍA     *
 *         E INNOVACIÓN (MPPCTI)                                     * 
 *  FECHA: DICIEMBRE DE 2013                                             *
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

/*
 * PREPARA EL FORMULARIO PARA REGISTRAR
 */
function registrar(entidad, id)
{ 
    $.ajax({
            type:'GET',
            url:'revisarProyecto/registrar'+entidad,
            data:{ 'id': id },
            beforeSend:function(){$("#cargandoModal").show();},        
            dataType:'html'})
     .done(function(data) {
            $('#ventanaModal').html(data);
            $('#ventanaModal').show();
            fechar();
            centrarModal('form'+entidad);
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

/*
 * GUARDAR REGISTRO
 */
function guardar(entidad)
{
    if (!testForm(entidad))
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
    
    $.ajax({
       type:'POST',
       url:'revisarProyecto/registrar'+entidad,
       data: $("form").serialize(),
       beforeSend:function(){$("#cargandoModal").show();},        
       dataType:'json'})
  
     .done(function(data) {    
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){
                                cancelarModal(); 
                                //Actualizamos el panel
                                $('#badge'+entidad).html(data.badge);
                                if (data.badge > 0) $('#badge'+entidad).removeClass('oculto');        
                                else $('#badge'+entidad).addClass('oculto');
                                $('#panel'+entidad+' > .accordion-inner').html(data.panel);
                                $( this ).dialog( "close" ); }};                            
                            var mensaje='Registro Satisfactorio.';
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

/*
* EDITAR REGISTRO
*/
function editar(entidad, id)
{
    $.ajax({
          type:'GET',
          url:'revisarProyecto/editar'+entidad,
          data: { 'id' : id },
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
          $('#ventanaModal').html(data);
          $('#ventanaModal').show();
          centrarModal('form'+entidad);
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

function actualizar(entidad)
{
    if (!testForm(entidad))
    {
      var mensaje='Llene los campos correctamente.';
      cajaDialogo("Alerta", mensaje);
      return false;
    }
    
    $.ajax({
         type:'POST',
         url:'revisarProyecto/editar'+entidad,
         data: $("form").serialize(),
         beforeSend:function(){$("#cargandoModal").show();},        
         dataType:'json'})
    
     .done(function(data) {    
                          if (data.error==='') // OPERACION EXITOSA
                          {
                              var botones={Cerrar: function(){                                
                                  $( this ).dialog( "close" );
                                  cancelarModal();                                   
                                  $('#panel'+entidad+' > .accordion-inner').html(data.panel); }};
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

/*
 *  ELIMINAR REGISTRO
 */
function eliminar(entidad, id)
{
  var mensaje='¿Está seguro que desea Eliminar este Registro?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'revisarProyecto/eliminar'+entidad,
                        data: { 'id' : id },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                $('#badge'+entidad).html(data.badge);
                                if (data.badge>0) $('#badge'+entidad).removeClass('oculto');
                                else $('#badge'+entidad).addClass('oculto');
                                $('#panel'+entidad+' > .accordion-inner').html(data.panel);
                                }};                            
                            var mensaje='Registro Eliminado satisfactoriamente.';
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

/*
 *  DAR POR CULMINADO EL PROYECTO
 */
function culminar(id)
{
  var mensaje='¿Está seguro que desea dar por culminado el Proyecto?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'revisarProyecto/culminar',
                        data: { 'id' : id },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'json'})
                     
                  .done(function(data) {
                        if (data.error==='') // OPERACION EXITOSA
                        {
                            var botones={Cerrar: function(){                                
                                $( this ).dialog( "close" );
                                cancelarModal(); 
                                window.location=$('#baseURL').val()+'ejecucion';
                                }};                            
                            var mensaje='Proyecto Culminado Satisfactoriamente.';
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
}

/*
 * FUNCIONES ESPECIALES
 */

// COLOCAR LOS SELECTORES DE FECHAS
function fechar()
{
  $( "#fecha" ).datepicker({
            showOn: "both",
            buttonImage: $('#baseImg').val()+"img/cal.gif",
            buttonText:"clic para Seleccionar",
            buttonImageOnly: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat:"dd/mm/yy",
            currentText:"Hoy",
            nextText:"Sig",
            defaultDate:_diaHoy(),
            //minDate:_diaHoy(),
            maxDate:_diaHoy(),
            dayNames:[ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
            dayNamesMin:[ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
            monthNames:["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"] /*,
            onSelect: function( selectedDate ) {
                $( "#fechaFin" ).datepicker( "option", "minDate", selectedDate )} */
        });
}

function cambiaMoneda(obj)
{
    var id = obj.attr('id').substring(6);
    $('#simboloMoneda'+id).html($('#simbolo'+obj.val()).val());
}

function cambiaActividad(obj)
{
    $('#uM').html($('#uM'+obj.val()).val());
}

// SELECTOR DE VALIDADORES DE CAMPO DE FORMULARIO
function testForm(entidad)
{
    switch (entidad)
    {
        case 'RecursoRecibido':     return testFormRecursoRecibido();
                                    break;
       
        case 'RecursoEjecutado':    return testFormRecursoEjecutado();
                                    break; 
                                    
        case 'RegistroProblema':    return testFormRegistroProblema();
                                    break;

        case 'MetaAlcanzada':       return testFormMetaAlcanzada();
                                    break;                                     
                                    
        default:                    return true;
    }    
}

// VALIDACIÓN DEL FORMULARIO DE REGISTRO DE RECURSO RECIBIDO
function testFormRecursoRecibido()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 
  
   //VERIFICAMOS CAMPO DE TEXTO OBSERVACIONES
   $('#observaciones').val($('#observaciones').val().replace(/(<([^>]+)>)/ig,""));
   if ( (trim($('#observaciones').val()).length < 4 ) || (trim($('#observaciones').val()).length > 200) )
   {
      $('#observaciones').parent().addClass('campoInvalido') 
       .attr('title','Debe Introducir una descripción correcta (> 4 caracteres y < 150 caracteres)');
      test = false;
   } 
   
   //VERIFICAMOS CAMPO DE MONTO
   if (trim($('#monto').val()) === '' || parseInt(nroPuro($('#monto').val())) === 0 )
   {
         $('#monto').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un monto correcto');
         test = false;
   }  
   
   //VERIFICAMOS CAMPO FECHA
   if (trim($('#fecha').val()) === '' )
   {
         $('#fecha').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una fecha correcta');
         test = false;
   } 
   
   //VERIFICAMOS FUENTE DE FINACIAMIENTO
   if (trim($('#fuente').val()) === '0' )
   {
         $('#fuente').parent().addClass('campoInvalido') 
                         .attr('title','Debe Seleccionar una Fuente');
         test = false;
   }    
   
   return test;
}

// VALIDACIÓN DEL FORMULARIO DE REGISTRO DE RECURSO EJECUTADO
function testFormRecursoEjecutado()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 
  
   //VERIFICAMOS CAMPO DE TEXTO OBSERVACIONES
   $('#observaciones').val($('#observaciones').val().replace(/(<([^>]+)>)/ig,""));
   if ( (trim($('#observaciones').val()).length < 4 ) || (trim($('#observaciones').val()).length > 200) )
   {
       $('#observaciones').parent().addClass('campoInvalido') 
         .attr('title','Debe Introducir una descripción correcta (> 4 caracteres y < 150 caracteres)');
       test = false;
   } 
   
   //VERIFICAMOS CAMPO DE MONTO
   if (trim($('#monto').val()) === '' || parseInt(nroPuro($('#monto').val())) === 0 )
   {
         $('#monto').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un monto correcto');
         test = false;
   }  
   
   //VERIFICAMOS CAMPO FECHA
   if (trim($('#fecha').val()) === '' )
   {
         $('#fecha').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir una fecha correcta');
         test = false;
   } 
   
   //VERIFICAMOS CAMPO ACTIVIDAD
   if (trim($('#actividad').val()) === '0' )
   {
         $('#actividad').parent().addClass('campoInvalido') 
                         .attr('title','Debe Seleccionar una Fuente');
         test = false;
   }    
   
   return test;
}

// VALIDACIÓN DEL FORMULARIO DE REGISTRO DE PROBLEMA
function testFormRegistroProblema()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 
  
   //VERIFICAMOS CAMPO DE TEXTO OBSERVACIONES
   $('#observaciones').val($('#observaciones').val().replace(/(<([^>]+)>)/ig,""));
   if ( (trim($('#observaciones').val()).length < 4 ) || (trim($('#observaciones').val()).length > 200) )
   {
      $('#observaciones').parent().addClass('campoInvalido') 
       .attr('title','Debe Introducir una descripción correcta (> 4 caracteres y < 150 caracteres)');
      test = false;
   } 
        
   //VERIFICAMOS TIPO PROBLEMA
   if (trim($('#problema').val()) === '0' )
   {
         $('#problema').parent().addClass('campoInvalido') 
                         .attr('title','Debe Seleccionar un tipo de Problema');
         test = false;
   }    
   
   return test;
}

// VALIDACIÓN DEL FORMULARIO DE META ALCANZADA
function testFormMetaAlcanzada()
{
   var test = true;
   $('p').removeClass('campoInvalido').removeAttr('title'); 
  
   //VERIFICAMOS CAMPO DE TEXTO OBSERVACIONES
   $('#observaciones').val($('#observaciones').val().replace(/(<([^>]+)>)/ig,""));
   if ( (trim($('#observaciones').val()).length < 4 ) || (trim($('#observaciones').val()).length > 200) )
   {
      $('#observaciones').parent().addClass('campoInvalido') 
        .attr('title','Debe Introducir una descripción correcta (> 4 caracteres y < 150 caracteres)');
      test = false;
   } 
        
   //VERIFICAMOS ACTIVIDAD ASOCIADA
   if (trim($('#actividad').val()) === '0' )
   {
         $('#actividad').parent().addClass('campoInvalido') 
                         .attr('title','Debe Seleccionar una Actividad Asociada');
         test = false;
   }  
   
   //VERIFICAMOS CAMPO DE META
   if (trim($('#meta').val()) === '' || parseInt(nroPuro($('#meta').val())) === 0 )
   {
         $('#meta').parent().addClass('campoInvalido') 
                         .attr('title','Debe Introducir un monto correcto');
         test = false;
   }     
   
   return test;
}