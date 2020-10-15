/*jQuery( "#tipo" ).change(function() {
    var tipo = document.getElementById('tipo');
    if (tipo.value == 'Nueva_incidencia'){
        document.getElementById('subirFichero').style.display='block';
    }else{
        document.getElementById('subirFichero').style.display='none';
    }
});*/

//document.getElementById('fichero').addEventListener('change', getFile);

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
            console.log('completeAll', arguments);
            bar.setAttribute('hidden', 'hidden');
            uploaded_filename = JSON.parse(arguments[0].response);
            console.log(uploaded_filename);
            var i;
   /* jQuery('#lista_ficheros').empty();*/
for (i = 0; i < uploaded_filename.length; i++) {

    var files = uploaded_filename
        , file = files[i];

        icono = document.createElement('div');
        icono.setAttribute("id", file.fnc);
        icono.setAttribute("class", "uk-padding-large uk-padding-remove-vertical uk-text-justify");
        icono.setAttribute("uk-icon", "icon: close");
//        icono.style.textAlign="right";

        var item = document.createElement('li');       

        var lista = document.getElementById('lista_ficheros');
        lista.appendChild(item);

        item.innerHTML=item.innerHTML  + file.name;
        item.setAttribute("class", "uk-text-justify");
        item.appendChild(icono);
        


    console.log('Name of the file', file.name);
    console.log('Size of the file', file.size);
    console.log('Id of the file', file.id);
    console.log('FNC of the file', file.fnc);

}
        }

    });