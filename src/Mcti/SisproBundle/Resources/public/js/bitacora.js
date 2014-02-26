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
    $('#bitacora').dataTable( {
	"sPaginationType": "full_numbers",
        "aaSorting": [[ 0, "desc" ]],
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