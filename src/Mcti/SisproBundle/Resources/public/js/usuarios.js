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
    $('#usuarios').dataTable( {
	"sPaginationType": "full_numbers",
        "aaSorting": [[ 6, "asc" ],[ 0, "asc" ]],
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

function nuevoUsuario(admin)
{
    var admin = admin || 0; // Establecemos valor por omisión para admin
    $.ajax({
            type:'GET',
            url:'usuarios/nuevo',
            //data:{    },
            beforeSend:function(){$("#cargandoModal").show();},        
            dataType:'html'})
     .done(function(data) {
            $('#ventanaModal').html(data);
            $('#ventanaModal').show();
            centrarModal('formNuevo');
            // SI ES ADMIN CAMBIAMOS EL COMPORTAMIENTO DE LOS BOTONES DE SELECCION DE ROL
            if (admin=='1') setRol();
            
            // COLOCAMOS TODOS LOS CAMPOS READONLY MENOS EL CORREO
            $('#mcti_sisprobundle_usuariotype_correo').removeAttr('readonly');
            $('#mcti_sisprobundle_usuariotype_nombre').attr('readonly','readonly');
            $('#mcti_sisprobundle_usuariotype_apellido').attr('readonly','readonly');
            $('#estructura').attr('disabled','disabled');
            $('#mcti_sisprobundle_usuariotype_cargo').attr('readonly','readonly');
            $('#mcti_sisprobundle_usuariotype_telefono').attr('readonly','readonly');                  
            $('#mcti_sisprobundle_usuariotype_correo').focus();
            
            $('#estructura').change(function(){
                      $('#mcti_sisprobundle_usuariotype_estructura').val($(this).val());});
            centrarModal('formNuevo');      
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

function buscarUsuario()
{
    if (!(isEmail($('#mcti_sisprobundle_usuariotype_correo').val())))
    {    
      var mensaje='Debe Introducir Datos Válidos';  
      cajaDialogo("Alerta", mensaje);    
      return false; 
    }
    var correo= trim($('#mcti_sisprobundle_usuariotype_correo').val().toLowerCase());
    $('#mcti_sisprobundle_usuariotype_correo').val(correo);
    $('#mcti_sisprobundle_usuariotype_nombre').val('');
    $('#mcti_sisprobundle_usuariotype_apellido').val('');
    $('#mcti_sisprobundle_usuariotype_estructura').val('');
    
    $.ajax({
         type:'POST',
         url:'usuarios/buscar',
         data:{'correo': correo },
         beforeSend:function(){$("#cargandoModal").show();},
         dataType:'json'})
            
     .done(function(data) {
         switch(data.status)
         {
           case 0: // NO SE PUDO ESTABLECER CONEXION CON LDAP
                    var botones={Cerrar: function(){
                    $( this ).dialog( "close" );}};
                    cajaDialogo('Error', data.mensaje, botones);
                    break;                     
           case 1: // NO EXISTE EN BD NI EN LDAP                              
                    var botones={Cerrar: function(){
                    $( this ).dialog( "close" );}};
                    cajaDialogo('Error', data.mensaje, botones);
                    break;
           case 2: // NO EXISTE EN BD PERO SI EN LDAP
                    $('#mcti_sisprobundle_usuariotype_nombre').val(data.nombre);                              
                    $('#mcti_sisprobundle_usuariotype_apellido').val(data.apellido);
                    $('#mcti_sisprobundle_usuariotype_correo').attr('readonly','readonly');
                    $('#mcti_sisprobundle_usuariotype_nombre').attr('readonly','readonly');
                    $('#mcti_sisprobundle_usuariotype_apellido').attr('readonly','readonly');
                    $('#estructura').removeAttr('disabled');
                    $('#mcti_sisprobundle_usuariotype_cargo').removeAttr('readonly');
                    $('#mcti_sisprobundle_usuariotype_telefono').removeAttr('readonly'); 
                    $('#buscar').addClass('oculto');
                    $('#guardar').removeClass('oculto');
                    var botones={Cerrar: function(){
                    $( this ).dialog( "close" );}};
                    cajaDialogo('Exito', data.mensaje, botones);
                    break;
           case 3: // NO EXISTE EN BD Y NO ES @MCTI        
                    $('#mcti_sisprobundle_usuariotype_correo').attr('readonly','readonly');
                    $('#mcti_sisprobundle_usuariotype_nombre').removeAttr('readonly');
                    $('#mcti_sisprobundle_usuariotype_apellido').removeAttr('readonly');
                    $('#estructura').removeAttr('disabled');
                    $('#mcti_sisprobundle_usuariotype_cargo').removeAttr('readonly');
                    $('#mcti_sisprobundle_usuariotype_telefono').removeAttr('readonly');
                    $('#buscar').addClass('oculto');
                    $('#guardar').removeClass('oculto');                             
                    break;                       
           case 4: // EXISTE EN BD ERROR
                    $('#mcti_sisprobundle_usuariotype_nombre').val(data.nombre);                              
                    $('#mcti_sisprobundle_usuariotype_apellido').val(data.apellido);
                    var botones={Cerrar: function(){
                    $( this ).dialog( "close" );}};
                    cajaDialogo('Error', data.mensaje, botones);
                    break;
            default:
                    alert ('ANA HS');
         } 
         centrarModal('formNuevo');  } )
                
     .always(function() {
         $("#cargandoModal").hide();
         })
    
     .fail(function(jqXHR, textStatus, errorThrown) {
         var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
         cajaDialogo(traductor(textStatus), mensaje);                
         });                    
  return true;  
}

function guardarUsuario()
{
  if (formularioInvalido())
  {
    var mensaje='Introduzca datos válidos.';
    cajaDialogo("Alerta", mensaje);
    return false;
  }
  
  $.ajax({
       type:'POST',
       url:'usuarios/nuevo',
       data: $("form").serialize(),
       beforeSend:function(){$("#cargandoModal").show();},        
       dataType:'html'})
  
   .done(function(data) {                         
       if (data.substring(0,6)==='Error:')
       {
          cajaDialogo("Error", data);
          $('#ventanaModal').hide();
          return true;
       }
       if (data.substring(0,6)==='Exito.')
       {
            var boton={Cerrar: function(){
                 $('#ventanaModal').hide();
                 window.location=$('#baseURL').val()+'admin/usuarios';                 
                 $( this ).dialog( "close" ); }};            
            cajaDialogo('Exito', data, boton);           
            return true;
       }       
       $('#ventanaModal').html('');
       $('#ventanaModal').html(data);
       $('#ventanaModal').show();
       $('#buscar').addClass('oculto');
       $('#guardar').removeClass('oculto');       
       centrarModal('formNuevo');
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

function editarUsuario(id, admin)
{
    var admin = admin || 0; // Establecemos valor por omisión para admin
    $.ajax({
          type:'GET',
          url:'usuarios/editar',
          data: { 'id' : id },
          beforeSend:function(){$("#cargandoModal").show();},        
          dataType:'html'})
    
     .done(function(data) {
          $('#ventanaModal').html(data);
          $('#ventanaModal').show();

          centrarModal('formNuevo');
          // Detenemos el comportamiento Submit del formulario
          $("form").submit(function(e) {
              e.preventDefault();
          });
          // SI ES ADMIN CAMBIAMOS EL COMPORTAMIENTO DE LOS BOTONES DE SELECCION DE ROL
          if (admin=='1') setRol();
            
          $('#estructura').val($('#mcti_sisprobundle_usuariotype_estructura').val());          
          $('#estructura').change(function(){
                    $('#mcti_sisprobundle_usuariotype_estructura').val($(this).val());});
          })
                
     .always(function() {
          $("#cargandoModal").hide();
          })
     
     .fail(function(jqXHR, textStatus, errorThrown) {
          var mensaje='Operación cancelada.<br/>' + traductor(errorThrown);
          cajaDialogo(traductor(textStatus), mensaje);                
          });
    
}

function actualizarUsuario()
{
  if (formularioInvalido())
  {
    var mensaje='Introduzca datos válidos.';
    cajaDialogo("Alerta", mensaje);
    return false;
  }
  
  $.ajax({
        type:'POST',
        url:'usuarios/editar',
        data: $("form").serialize(),
        beforeSend:function(){$("#cargandoModal").show();},        
        dataType:'html'})
     
  .done(function(data) {           
        $('#ventanaModal').html(data);
        $('#ventanaModal').show();
        if (data.substring(0,6)==='Error:')
        {
           cajaDialogo("Error", data);
           $('#ventanaModal').hide();
           return false;
        }
        if (data.substring(0,6)==='Exito.')
        {
            var boton={Cerrar: function(){
                 $('#ventanaModal').hide();
                 window.location=$('#baseURL').val()+'admin/usuarios';                 
                 $( this ).dialog( "close" ); }};            
            cajaDialogo('Exito', data, boton);           
            return true;
        }
        centrarModal('formNuevo');

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

function reiniciarClave(correo)
{
  var mensaje='¿Está seguro que desea reiniciar la clave de: '+ correo + '?';    
  var botones={No: function(){$( this ).dialog( "close" );},
               Sí: function(){ 
                  $.ajax({
                        type:'POST',
                        url:'usuarios/reiniciar',
                        data: { 'correo' : correo },
                        beforeSend:function(){$("#cargandoModal").show();},        
                        dataType:'html'})
                     
                  .done(function(data) {           
                        $('#ventanaModal').html(data);
                        $('#ventanaModal').show();
                        if (data.substring(0,6)==='Error:')
                        {
                           cajaDialogo("Error", data);
                           $('#ventanaModal').hide();
                           return false;
                        }
                        if (data.substring(0,6)==='Exito.')
                        {
                           cajaDialogo("Exito", data);
                           $('#ventanaModal').hide();
                           return true;
                        }
                        centrarModal('formNuevo');
                
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

// CON ESTA FUNCION HACEMOS QUE LOS CHECKBOX SOLO PERMITAN LA SELECCION DE UN ELEMENTO
function setRol()
{    
   $(":checkbox[id*='_role_']").each(function() { 
      $(this).change(function(){            
          if($(this).is(':checked'))
          {
             me = $(this).attr('id'); 
             usr = $(":checkbox[id$='_role_1']").attr('id');
             $(":checkbox[id*='_role_'][id!='"+usr+"'][id!='"+me+"']").removeAttr('checked');               
          }          
      });
   });   
}

function formularioInvalido()
{
  $('p').removeClass('campoInvalido').removeAttr('title');
  if (!isEmail($('#mcti_sisprobundle_usuariotype_correo').val()))
  {
      $('#mcti_sisprobundle_usuariotype_correo').parent().addClass('campoInvalido') 
                        .attr('title','Introduzca una dirección de correo válida');
  }
  
  if (!isNombre($('#mcti_sisprobundle_usuariotype_nombre').val()))
  {
      $('#mcti_sisprobundle_usuariotype_nombre').parent().addClass('campoInvalido')
                        .attr('title','Nombre incorrecto');
  }
  
  if (!isNombre($('#mcti_sisprobundle_usuariotype_apellido').val()))
  {
      $('#mcti_sisprobundle_usuariotype_apellido').parent().addClass('campoInvalido') 
                        .attr('title','Apellido incorrecto');
  }
  
  if  (isNaN(parseInt($('#mcti_sisprobundle_usuariotype_estructura').val())))
  {
      $('#mcti_sisprobundle_usuariotype_estructura').parent().addClass('campoInvalido')
                        .attr('title','Debe seleccionar la Unidad o Ente');
  }
  
  if (!(isEmail($('#mcti_sisprobundle_usuariotype_correo').val())) ||
      !(isNombre($('#mcti_sisprobundle_usuariotype_nombre').val())) ||
      !(isNombre($('#mcti_sisprobundle_usuariotype_apellido').val())) || 
      (isNaN(parseInt($('#mcti_sisprobundle_usuariotype_estructura').val()))) ) 
  {
      return true;
  }
  return false;
}