var ficheros = [];

jQuery( "#tipo" ).change(function() {
    var tipo = document.getElementById('tipo');
    if (tipo.value == 'Nueva_incidencia'){
        document.getElementById('subirFichero').style.display='block';
    }else{
        document.getElementById('subirFichero').style.display='none';
    }
});


function getFile(event) {
    var i;
    jQuery('#lista_ficheros').empty();
for (i = 0; i < event.target.files.length; i++) {

    var files = event.target.files
        , file = files[i];

        var lista = document.getElementById('lista_ficheros');
        var item = document.createElement('li');
        lista.appendChild(item);
        item.innerHTML=item.innerHTML + file.name;

    console.log('Name of the file', file.name);
    console.log('Size of the file', file.size);
}
}


var bar = document.getElementById('js-progressbar');

UIkit.upload('.js-upload', {

        url: '/wp-json/aremox/v1/imagen/',
        multiple: true,
        concurrent: 5,

        beforeSend: function () {
          //  console.log('beforeSend', arguments);

        },
        beforeAll: function () {
           // console.log('beforeAll', arguments);
        },
        load: function () {
           // console.log('load', arguments);
        },
        error: function () {
           // console.log('error', arguments);
        },
        complete: function (e) {
         //   console.log('complete', e);
      

        },

        loadStart: function (e) {
           // console.log('loadStart', arguments);

            bar.removeAttribute('hidden');
            bar.max = e.total;
            bar.value = e.loaded;
        },

        progress: function (e) {
          // console.log('progress', arguments);

            bar.max = e.total;
            bar.value = e.loaded;
        },

        loadEnd: function (e) {
          // console.log('loadEnd', arguments);

            bar.max = e.total;
            bar.value = e.loaded;
        },

        completeAll: function () {
            bar.setAttribute('hidden', 'hidden');
            document.getElementById("upload").className = "uk-width-2-3";
            uploaded_filename = JSON.parse(arguments[0].response);
            var i;
            //ficheros.push(document.getElementById("ficheros").value);

   /* jQuery('#lista_ficheros').empty();*/
for (i = 0; i < uploaded_filename.length; i++) {

    var files = uploaded_filename
        , file = files[i];

        var spinner = document.createElement('div');
        spinner.setAttribute("id", "spinner" + file.fnc);  
        spinner.setAttribute("class","borrar-elemento uk-button-link uk-width-1-2 uk-margin-remove-vertical");
        spinner.innerHTML=spinner.innerHTML  + '<div uk-spinner="ratio: 0.5"></div>';

        spinner.style.display='none'

        var icono = document.createElement('a');
        icono.setAttribute("id", file.fnc);
        icono.setAttribute("class","borrar-elemento uk-button-link uk-width-1-2 uk-margin-remove-vertical");
     //   icono.setAttribute("class", "uk-padding-large uk-padding-remove-vertical uk-text-justify");
        icono.setAttribute("uk-icon", "icon: trash");
//        icono.style.textAlign="right";
        icono.addEventListener("click", borrarHttp, false);

        var item = document.createElement('div'); 
        item.setAttribute("id", "name" + file.fnc);      
        item.setAttribute("class", "uk-text-left uk-width-1-2 uk-margin-remove-vertical");

        var lista = document.getElementById('lista_ficheros');
        lista.appendChild(item);
        item.innerHTML=item.innerHTML  + file.name;
        lista.appendChild(icono);
        lista.appendChild(spinner);


        ficheros.push(file.fnc);
        


    console.log('Name of the file', file.name);
    console.log('Size of the file', file.size);
    console.log('Id of the file', file.id);
    console.log('FNC of the file', file.fnc);

}

document.getElementById("ficheros").value = ficheros;
console.log(ficheros);
        }

    });

function borrar(id){
    console.log(id);
    console.log(this.id);

    removeItem(ficheros,id);
    document.getElementById("ficheros").value = ficheros;
    console.log(ficheros);
    
    var icono = document.getElementById(id);
    icono.parentNode.removeChild(icono);
    var nombre = document.getElementById("name"+id);
    nombre.parentNode.removeChild(nombre);
    var nombre = document.getElementById("spinner"+id);
    nombre.parentNode.removeChild(nombre);

    if(ficheros.length < 1){
        document.getElementById("upload").className = "uk-width-3-3";
    }
}

function borrarHttp(event){
    id = this.id;
    document.getElementById('spinner'+id).style.display='block';
    document.getElementById(id).style.display='none'
    const url = '/wp-json/aremox/v1/imagen?id='+id;
    const http = new XMLHttpRequest()

    http.open("DELETE", url)
    http.onreadystatechange = function(id){

    if(this.readyState == 4 && this.status == 200){
        var resultado = JSON.parse(this.responseText)
        borrar(resultado.id);
    }
}
http.send()
}

function removeItem(arr, value) {
    var i = 0;
    while (i < arr.length) {
      if (arr[i] === value) {
        arr.splice(i, 1);
      } else {
        ++i;
      }
    }
    return arr;
  }