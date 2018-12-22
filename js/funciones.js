function confirmeliminar(page,params,tit) {
	if (confirm("¿Esta ud seguro que quiere eliminar el registro "+tit+"?")){ 
			  var body = document.body;
			  form=document.createElement('form'); 
			  form.method = 'POST'; 
			  form.action = page;
			  form.name = 'jsform';
			  for (index in params)
			  {
					var input = document.createElement('input');
					input.type='hidden';
					input.name=index;
					input.id=index;
					input.value=params[index];
					form.appendChild(input);
			  }	  		  			  
			  body.appendChild(form);
			  form.submit();
		};
	}
//fin confirmar eliminar
function nuevoAjax(){
var xmlhttp=false;
if (!xmlhttp && typeof XMLHttpRequest!='undefineded'){
xmlhttp=new XMLHttpRequest();
}
return xmlhttp;
}
function buscar(nombre=""){
if (nombre == ""){
	nombre = document.getElementById('buscar').value;
}
//alert("esta recibiendo datos"+funcion+" "+nombre);
ajax=nuevoAjax();
ajax.open("POST","?buscar",true);
ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
		document.getElementById('txtsugerencias').innerHTML = ajax.responseText;
		}
	}
ajax.send("datos="+nombre);
}
/*cookies*/
function leercookie(cname) {
<!--
	var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
	-->
}
function grabarcookie(id,valor){
<!--
document.cookie=id+"="+valor;
-->
}
function eliminarcookie(key) {
<!--
document.cookie = key + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
-->
}
function grabarcookieinput(id){
	<!--
var valor = document.getElementById(id).value;
document.cookie=id+"="+valor;
//alert(document.cookie);
//window.open('index.php','_parent');
-->
}
function leercookieinput(id){
	<!--
	var valor=getCookie(id);
    if (valor!="") {
		document.getElementById(id).value = valor;
    }
	-->
}
function existecookie(id){
	var actual=leercookie(id);
	if (actual=="null") return false;
	else if (actual=="") return false;
	else return true;
}
function limpiar(id){
	<!--
var valor = document.getElementById(id);
valor.value="";
-->
}
/*fin cookies*/

function valida_existe(tabla,campo,valor){
ajax=nuevoAjax();
var url = "?valida_existe";
ajax.open("POST",url,true);
ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			if(ajax.responseText==1){
			document.getElementById("txt"+campo).innerHTML = "<b>Ya esta registrado</b>";	
			}else if(ajax.responseText==0){
			document.getElementById("txt"+campo).innerHTML = "";
			document.getElementById("txt"+campo).innerHTML = "<b>Disponible</b>";
			}
		
		}
	}
ajax.send("tabla="+tabla+"&campo="+campo+"&valor="+valor);
}

function soloNumeros(e){
	var key = window.Event ? e.which : e.keyCode
	//alert (key);
	return ((key >= 48 && key <= 57) || (key >= 96 && key <= 105) || (key==190)  || (key==110) || (key==8)  || (key==9) || (key==38) || (key==40) || (key==46));
}
function notificacionescritorio(mensaje,tipo){
notificar(document.title,'../img/'+tipo+'.png',mensaje);
}
function notificar(titulo,icono,texto,url=''){
	if(Notification.permission !== "granted"){
		Notification.requestPermission();
	}else{
		var notificacion = new Notification(titulo,
		{
		icon: icono,
		body: texto
		}
		);
		notificacion.onclick = function(){
			if (url!='')
			window.open(url);
		}
	}
}