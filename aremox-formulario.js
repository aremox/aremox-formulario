/*jQuery( "#tipo" ).change(function() {
    var tipo = document.getElementById('tipo');
    if (tipo.value == 'Nueva_incidencia'){
        document.getElementById('subirFichero').style.display='block';
    }else{
        document.getElementById('subirFichero').style.display='none';
    }
});*/

document.getElementById('fichero').addEventListener('change', getFile);

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

        url: '',
        multiple: true,

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
          //  alert('Upload Completed');
        }

    });