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

});

// FUNCIONES ESPECIALES
// 


// DEVUELVE LA FECHA DEL DIA DE HOY
function _diaHoy(opcion)
{
   var Hoy= new Array(2);
   var fecha=new Date();
   Hoy[1]= fecha.getFullYear();
   Hoy[0]=(fecha.getDate()<10)? '0'+fecha.getDate(): fecha.getDate();
   Hoy[0]+=((fecha.getMonth()+1)<10)? '/0'+(fecha.getMonth()+1):'/'+(fecha.getMonth()+1);
   Hoy[0]+= '/'+Hoy[1];
   if (opcion==1){return Hoy[1];}
   else {return Hoy[0];}
}

// DEVUELVE LA FECHA MAYOR ENTRE LAS DOS SUMINISTRADAS
function _fechaMayor(FechaIni, FechaFin)
{
  //Obtiene dia, mes y año  
   var fecha1 = new _fecha( FechaIni );     
   var fecha2 = new _fecha( FechaFin );

  //Obtiene Objetos Date
  var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
  var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia ); 
	//Resta fechas y redondea  
  return (miFecha1.getTime()>miFecha2.getTime())? FechaIni:FechaFin;  
}

// DEVUELVE LA DIFERENCIA EN DIAS DE DOS FECHAS
function _diferenciaFechas(FechaIni, FechaFin)
{
   //Obtiene dia, mes y año  
   var fecha1 = new _fecha( FechaIni );     
   var fecha2 = new _fecha( FechaFin );

   //Obtiene Objetos Date
   var miFecha1 = new Date( fecha1.anio, fecha1.mes, fecha1.dia );
   var miFecha2 = new Date( fecha2.anio, fecha2.mes, fecha2.dia ); 
   //Resta fechas y redondea  
   var diferencia = (miFecha2.getTime() - miFecha1.getTime())/1000*60;  
   //var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));  
   //return dias;   	
   return diferencia;		
}

// CONSTRUCTOR DE CADENA (Formato "dd/mm/YYYY" A FECHA
function _fecha(cadena) 
{  
 //Separador para la introduccion de las fechas  
 var separador = "/"; 
 //Separa por dia, mes y año  
 if ( cadena.indexOf( separador ) != -1 ) 
 {
	 var posi1 = 0;  
	 var posi2 = cadena.indexOf( separador, posi1 + 1 );  
	 var posi3 = cadena.indexOf( separador, posi2 + 1 );  
	 this.dia = cadena.substring( posi1, posi2 );  
	 this.mes = cadena.substring( posi2 + 1, posi3 );  
	 this.mes =this.mes -1
   this.anio = cadena.substring( posi3 + 1, cadena.length );  
 } 
 else
 {  
	 this.dia = 0;  
	 this.mes = 0;  
	 this.anio = 0;     
 } 
 
 return true;
}

function cancelarModal()
{
  $('#ventanaModal').html('');
  $('#ventanaModal').hide();
  return true;
}

function centrarModal(id)
{
   var id = id || 'ventanaModal';
   
   var el = document.getElementById(id);
   var margenIzq = ((-1)*(parseInt(el.style.width)/2)).toString();
   var margenTop= ((-1)*(parseInt(el.style.height)/2)).toString();
  // Asignamos la nueva posición al elemento para que aparezca en el centro.   
   el.style.marginLeft = margenIzq + 'px';
   el.style.marginTop = margenTop + 'px';
   el.style.left = '50%';
   el.style.top = '50%';
   el.style.position = 'fixed';
   return true;
}

function cajaDialogo(tipo, mensaje, botones)
{
  var caja;
  var mensaje= mensaje || "Atención";
  var tipo = tipo || "Mensaje";
  var titulo;
  var imagen;
  var botones=botones || {Cerrar: function(){$( this ).dialog( "close" );}};
  switch(tipo)
  {
    case "Guardar":
         titulo="Guardar";
         imagen=$('#baseImg').val()+"img/guardar.png";
         break;
    case "Alerta":
         titulo="Atención";
         imagen=$('#baseImg').val()+"img/alerta.png";
         break;
    case "Borrado":
         titulo="Operación Exitosa";
         imagen=$('#baseImg').val()+"img/borrado64.png";
         break;
    case "Exito":
         titulo="Operación Exitosa";
         imagen=$('#baseImg').val()+"img/exito.png";
         break;     
    case "Pregunta":
         titulo="Pregunta";
         imagen=$('#baseImg').val()+"img/pregunta.png";
         break;
    case "Error":
         titulo="Error";
         imagen=$('#baseImg').val()+"img/error.png";
         break;
    default:
         titulo=tipo;
         imagen=$('#baseImg').val()+"img/warning.png";
  }    
  caja='<div title="'+titulo+'" id="caja">';
  caja+='<table width=100%"><tr>';
  caja+='<td style="vertical-align: middle; width: 80px; text-align: center; padding:15px 5px 0 0">';
  caja+='<img src="'+imagen+'" /></td>';
  caja+='<td style="vertical-align: middle; padding:15px 0 0 10px; font-size:1.2em">';
  caja+=mensaje+'</td></tr></table></div>';             
  caja=$(caja);
  caja.dialog({
        modal: true,
        zIndex:1000,
        draggable:true,
        resizable: false,
        minHeight:200,
        width:400,
        buttons:botones});
  return true;  
}

// Traductor de mensajes de sistema
function traductor(texto)
{
  texto=trim(texto.toLowerCase());
  switch(texto)  
  {
    case "error":
         return "Error";
         break;
    case "timeout":
         return "Tiempo de Espera Agotado";
         break;
    case "abort":
         return "Operación Abortada";
         break;
    case "parsererror":
         return "Error de Sintaxis";
         break;
    case "not found":
        return "Ruta no encontrada";
        break;
    case "internal server error":
        return "Error Interno del Servidor";
        break;
    default:
        return texto;
  }
}

function nroPuro(nro)
{
   return parseFloat(nro.replace(/\./g,'').replace(/\,/g,'.'));
}

function formatNumber(num, decLength, decSep, milSep)
{
    if(num == '') return '';
    var arg, entero, nrStr, sign = true;
    var cents = '';
    if(typeof(num) == 'undefined') return;
    if(typeof(decLength) == 'undefined') decLength = 2;
    if(typeof(decSep) == 'undefined') decSep = ',';
    if(typeof(milSep) == 'undefined') milSep = '.';
    if(milSep == '.') arg=/\./g;
    else if(milSep == ',') arg=/\,/g;
    if(typeof(arg) != 'undefined') num = num.toString().replace(arg, '');
    num = num.toString().replace(/,/g,'.');
    if(num.indexOf('.') != -1) entero = num.substring(0, num.indexOf('.'));
    else entero = num;
    if(isNaN(num))return "0";
    if(decLength > 0)
    {        
        sign = (num == (num = Math.abs(num)));                
        nrStr = parseFloat(num).toFixed(decLength);
        nrStr = nrStr.toString();
        if (nrStr.indexOf('.') != -1) cents=nrStr.substring(nrStr.indexOf('.')+1);        
    }
    num = parseInt(entero);    
    if(milSep != '')
    { 
        num = num.toString();
        for(var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) 
            num = num.substring(0, num.length - (4 * i + 3)) + milSep + num.substring(	num.length - (4 * i + 3));        
    }	
    else
    {
        for(var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) 
             num = num.substring(0, num.length - (4 * i + 3)) + num.substring(num.length - (4 * i + 3));
    }
    if (isNaN(nroPuro(num)))num = '0';
    if(decLength > 0) return (((sign) ? '' : '-') + num + decSep + cents); else return (((sign) ? '' : '-') + num);
}

function onlyDigits(e, value, allowDecimal, allowNegative, allowThousand, decSep, thousandSep, decLength)
{
	var _ret = true, key;
	if(window.event) { key = window.event.keyCode; isCtrl = window.event.ctrlKey; }
	else if(e) { if(e.which) { key = e.which; isCtrl = e.ctrlKey; }}
	if(key === 8) return true;
	if(isNaN(key)) return true;
	if(key < 44 || key > 57) { return false; }
	keychar = String.fromCharCode(key);
	if(decLength === 0) allowDecimal = false;
	if(!allowDecimal && keychar === decSep || !allowNegative && keychar === '-' || !allowThousand && keychar === thousandSep) return false;
	return _ret;
}

function isCI(ci)
{
  if (isNaN(ci) || ci==='' || ci.length<4) return false;
  return true;    
}

function isEmail(email)
{
    var ereg=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;   
    return ereg.test(email);
}

function isNombre(nombre)
{
    var ereg=/^([A-Za-zÑÁÉÍÓÚñáéíóúÜü]{1}[A-Za-zÑÁÉÍÓÚñáéíóúÜü]+[\s]*)+$/;
    return ereg.test(nombre);    
}

function nroFormatoUS(nro)
{
   var n= parseFloat(nro.replace(/\./g,'').replace(/\,/g,'.'));
   if (isNaN(n)===true || trim(nro)==='') return false;
   return n;    
}

function trim(myString)
{
   return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}