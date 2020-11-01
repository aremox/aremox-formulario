<form action="<?php get_the_permalink(); ?>" method="post" id="form_aremox_formulario"
class="cuestionario" enctype="multipart/form-data">
<fieldset class="uk-fieldset">
    <legend class="uk-legend">Formulario de Contacto</legend>
    <?php wp_nonce_field('grabar_aremox_formulario', 'aremox_formulario_nonce'); ?>
        <div class="uk-margin">
            <input class="uk-input uk-button-default" type="text" minlength="4" placeholder="Escriba aquí su nombre ..." name="nombre" id="nombre" required>
        </div>
        <div class="uk-margin">
            <input class="uk-input uk-button-default" type="email" placeholder="Escriba aquí su correo electrónico ..." name="correo" id="correo" required>
        </div>
        <div class="uk-margin">
            <input class="uk-input uk-button-default" type="tel" pattern="[0-9]{9}" maxlength="9"  minlength="9" placeholder="Escriba aquí su teléfono (987654321) ..." name="telefono" id="telefono" required>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label uk-align-left" >Seleccione una opción:</label>
            <select class="uk-select uk-button-default" name="tipo" id="tipo" required>
                <option value="Realizar_consulta">Realizar consulta</option>
                <option value="Nueva_incidencia">Nueva incidencia</option>
                <option value="Enviar_propuesta">Enviar propuesta</option>
                <option value="Alta_empresa">Alta de empresa</option>
            </select>
        </div>
        <div class="uk-margin">
            <textarea class="uk-textarea uk-button-default" id="texto" minlength="9" name="texto" rows="5" cols="50" placeholder="Escriba aquí su comentario o mensaje ..."></textarea>
        </div>  

        <div uk-grid calss="uk-margin-remove-vertical uk-padding-remove-vertical">  
            <div id="upload" class="uk-width-3-3">
                <div class="js-upload uk-placeholder uk-text-center uk-background-primary uk-text-secondary" id="subirFichero"  style="display:none;">
                    <span uk-icon="icon: cloud-upload"></span>
                    <span class="uk-text-middle">Arrastra aquí un fichero o</span>
                    <div uk-form-custom>
                        <input type="file" id="fichero" name="file" multiple>
                        <span class="uk-link">selecciona uno</span>
                    </div>
                </div>
            
            </div>

            <div class="uk-width-1-3 " id="lista_ficheros" uk-grid>
            </div>
        </div>
        <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>

        <input id="ficheros" name="ficheros" type="hidden" value="[]">

        <div class="uk-margin">
            <label for="aceptacion">La información facilitada se tratará 
            acorde a la ley, segun puede consulta <button class="uk-button uk-button-link" type="button" uk-toggle="target: #modal-close-outside">aquí</button>.</label><br/>

            <input class="uk-checkbox  uk-form-primary uk-button-default" type="checkbox" id="aceptacion" name="aceptacion"
value="1" required> Entiendo y acepto las condiciones
        </div>
        <div cclass="uk-margin" uk-margin>
            <input class="uk-button uk-button-default g-recaptcha" data-sitekey="reCAPTCHA_site_key" data-callback='onSubmit' data-action='submit' type="submit" value="Enviar">
        </div>
        </fieldset>
    </form>'



  