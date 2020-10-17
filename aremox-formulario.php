<?php
/*
 * Plugin Name: AremoX Formulario
 * Plugin URI: http://openwebinars.net/cursos/crear-plugins-para-wordpress/
 * Description: Plugin para Formulario
 * Version: 1.0
 * Author: Iván Arenas
 * Author URI: http://josearcos.me
 * License: GPL2
 *
 */

/*
 * Assign global variables
 */

$plugin_url = WP_PLUGIN_URL . '/aremox-formulario';
$plugin_dir = WP_PLUGIN_DIR . '/aremox-formulario';
$options = array();


// Cuando el plugin se active se crea la tabla para recoger los datos si no existe
register_activation_hook(__FILE__, 'aremox_formulario_init');
 
/**
 * Crea la tabla para recoger los datos del formulario
 *
 * @return void
 */
function aremox_formulario_init() 
{
    $current_user = wp_get_current_user();
    $upload_dir   = wp_upload_dir();
    $plugin_dir = WP_PLUGIN_DIR . '/aremox-formulario';
 
if (  ! empty( $upload_dir['basedir'] ) ) {
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/tmp/';
        if ( ! file_exists( $aremox_dirname ) ) {
        wp_mkdir_p( $aremox_dirname );
        if (!@copy($plugin_dir."/seguridad/.htaccess", $aremox_dirname.".htaccess")) {
            $errors= error_get_last();
            echo "COPY ERROR: ".$errors['type'];
            echo "<br />\n".$errors['message'];
            echo "Error al copiar .htaccess...\n";
        }
    }
}
  

    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    // Crea la tabla sólo si no existe
    // Utiliza el mismo prefijo del resto de tablas
    $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario';
    // Utiliza el mismo tipo de orden de la base de datos
    $charset_collate = $wpdb->get_charset_collate();
    // Prepara la consulta
    $query = "CREATE TABLE IF NOT EXISTS $tabla_aremox_formulario (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(40) NOT NULL,
        correo varchar(100) NOT NULL,
        telefono varchar(100) NOT NULL,
        tipo varchar(150) NOT NULL,
        texto text NOT NULL,
        ficheros varchar(350),
        aceptacion smallint(4) NOT NULL,
        ip varchar(300),
        created_at datetime NOT NULL,
        UNIQUE (id)
        ) $charset_collate;";
    // La función dbDelta permite crear tablas de manera segura se
    // define en el archivo upgrade.php que se incluye a continuación
    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($query); // Lanza la consulta para crear la tabla de manera segura
}


/* 
*
* Borra la tabla al desactivar
*
*/
register_deactivation_hook(__FILE__, 'aremox_formulario_disable');

function aremox_formulario_disable() {
    $upload_dir   = wp_upload_dir();

    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario';
    $query = "DROP TABLE IF EXISTS $tabla_aremox_formulario;";
    $wpdb->query($query);
    delete_option("my_plugin_db_version");

    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario';
        if ( file_exists( $aremox_dirname ) ) {
            recurseRmdir( $aremox_dirname );
    }
}




/*
 * Add a link to our plugin in the admin menu
 * under 'Settings > OpenWebinars Badges'
 */

function aremox_formulario_menu() {

    /*
     * Use the add_options page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function );
     */
  
    add_menu_page(
      'AremoX Formulario',
      'Consulta de formularios',
      'manage_options',
      'aremox-formulario',
      'aremox_formulario_options_page',
      'dashicons-feedback',1);
  }
  add_action( 'admin_menu', 'aremox_formulario_menu' );



  
  /*
 * Limiting the plugin usage to Editor or Admins.
 * Incluiding other plugin files needed to work.
 */
function aremox_formulario_options_page() {
    if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
      }
    
     
      global $plugin_url;
      global $options;
      global $wpdb;

      if ( isset( $_POST['aremox_email_submit'] ) ) {
        $hidden_field = esc_html( $_POST['aremox_form_submitted'] );

    
        if ( $hidden_field == 'Y' ) {
          $aremox_email = esc_html( $_POST['aremox_email'] );
    
       //   $openwebinars_badges = openwebinars_badges_get_badges( $openwebinars_email );
    
          /*
           * Store form options in database
           */
          $options['aremox_email']    = $aremox_email;
         // $options['openwebinars_badges']    = $openwebinars_badges;
          $options['last_updated']          = time();
    
          update_option( 'aremox_formulario', $options );
    
        }
      }

      $options = get_option( 'aremox_formulario' );

      if( $options != '' ) {
        $aremox_email = $options['aremox_email'];
       // $openwebinars_badges = $options['openwebinars_badges'];
      }else{
        $aremox_email = "";
      }

      $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario';


    
    
    $aremox_formulario = $wpdb->get_results("SELECT * FROM $tabla_aremox_formulario ORDER BY created_at DESC");

      require( 'inc/options-page-wrapper.php' );
    
}

function aremox_formulario_shortcode() {

    $insertado = false;
  
    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    // Si viene del formulario  graba en la base de datos
    // Cuidado con el último igual de la condición del if que es doble
    if (isset($_POST['nombre'])) {
    if ($_POST['nombre'] != ''
        AND is_email($_POST['correo'])
        AND strlen ($_POST['telefono']) == '9'
        AND $_POST['tipo'] != ''
        AND strlen($_POST['texto']) >= '9'      
        AND $_POST['aceptacion'] == '1'
        AND wp_verify_nonce($_POST['aremox_formulario_nonce'], 'grabar_aremox_formulario')
    ) {
        $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario'; 
        $nombre = sanitize_text_field($_POST['nombre']);
        $correo = sanitize_text_field($_POST['correo']);
        $telefono = (int)$_POST['telefono'];
        $tipo = sanitize_text_field($_POST['tipo']);
        $texto = sanitize_text_field($_POST['texto']);
        $aceptacion = (int)$_POST['aceptacion'];
        $ficheros = sanitize_text_field($_POST['ficheros']);
        $ip = Kfp_Obtener_IP_usuario();
        $created_at = date('Y-m-d H:i:s');
        $wpdb->insert(
            $tabla_aremox_formulario,
            array(
                'nombre' => $nombre,
                'correo' => $correo,
                'telefono' => $telefono,
                'tipo' => $tipo,
                'texto' => $texto,
                'ficheros' => $ficheros,
                'aceptacion' => $aceptacion,
                'ip' => $ip,
                'created_at' => $created_at,
            )
        );
        $id = $wpdb->insert_id;
        $insertado = 0;
        envioMail($tipo,$correo,$telefono, $texto,$ficheros, $id, $nombre );
        if($id > 0){
            $insertado = moverFichero($id, $ficheros);
        }
        
        
    }}

        ob_start();

        if($insertado > 0){
            require( 'inc/respuesta-front-end.php' );
        }else{
            require( 'inc/front-end.php' );
            require( 'inc/modal-front-end.php' );
        }
        
   
  

  
      return ob_get_clean();
  
  }
  add_shortcode('aremox_formulario', 'aremox_formulario_shortcode');

function aremox_script_styles(){


    wp_register_script('script_respuesta', plugins_url('aremox-formulario.js', __FILE__), array('jquery'),1, true);
 //   wp_register_script('script_dropzone', plugins_url('min/aremox-formulario.min.js', __FILE__), array('jquery'),1, true);
   
    wp_enqueue_script('script_respuesta');
   // wp_enqueue_script('script_dropzone');

}

add_action('wp_enqueue_scripts', 'aremox_script_styles');


  /**
 * Devuelve la IP del usuario que está visitando la página
 * Código fuente: https://stackoverflow.com/questions/6717926/function-to-get-user-ip-address
 *
 * @return string
 */
function Kfp_Obtener_IP_usuario()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED',
        'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}
add_action( 'rest_api_init', function () {
	register_rest_route( 'aremox/v1', '/imagen',
		array(
            array(
			'methods'       => 'GET', 
            'callback'      => 'montrarImagen'
            ),
            array(
                'methods'   => 'POST', 
                'callback'  => 'subir_imagen'
            ),
            array(
                'methods'   => 'DELETE', 
                'callback'  => 'borrar_imagen'
               )
		)
    );
});
function subir_imagen(){
    $upload_dir   = wp_upload_dir();
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/tmp/';
    $data = array(); 

    if(isset($_FILES['files'])){
        for($i=0;$i<count($_FILES['files']['name']);$i++){
                $errors     = array();
                $file_name  = strtolower(sanitize_text_field($_FILES['files']['name'][$i]));
                $file_size  = $_FILES['files']['size'][$i];
                $file_tmp   = $_FILES['files']['tmp_name'][$i];
                $file_type  = $_FILES['files']['type'][$i];
                $file_ext   = explode('.',$_FILES['files']['name'][$i]);
                $file_ext   = strtolower(end($file_ext));
                $id     = (string)intval( rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) );
                
 
                $extensions = array("jpeg","jpg","png","docx","doc","pdf");
 
                if(in_array($file_ext,$extensions) === true){
                    move_uploaded_file($file_tmp,$aremox_dirname.$id.".".$file_ext);
                    $data[$i]['name']   = $file_name;
                    $data[$i]['size']   = $file_size;
                    $data[$i]['id']     = $id;
                    $data[$i]['fnc']    = $id.".".$file_ext;
                }
            
        }
    }
return $data;
}

function borrar_imagen(WP_REST_Request $request){
//print_r($request->get_params());
$fichero = sanitize_text_field($request->get_param('id'));
$upload_dir   = wp_upload_dir();
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/tmp/';
unlink($aremox_dirname.$fichero);
    return $request->get_params();
}



function convuls_customquery(){
	global $plugin_url;
      global $options;
      global $wpdb;


      $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario';


    
    
    $aremox_formulario = $wpdb->get_results("SELECT * FROM $tabla_aremox_formulario");

		// Return the data
		return $aremox_formulario;
	
}

function moverFichero($id, $nombre_ficheros){
    $plugin_dir = WP_PLUGIN_DIR . '/aremox-formulario';

    $upload_dir   = wp_upload_dir();
    $aremox_dirtmp = $upload_dir['basedir'].'/aremox-formulario/tmp/';
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/'.$id.'/';
    $resultado = 1;

    if ( ! file_exists( $aremox_dirname ) ) {
         wp_mkdir_p( $aremox_dirname );

         if (!@copy($plugin_dir."/seguridad/.htaccess", $aremox_dirname.".htaccess")) {
            $errors= error_get_last();
            echo "COPY ERROR: ".$errors['type'];
            echo "<br />\n".$errors['message'];
            echo "Error al copiar .htaccess...\n";
        }
    }
    $nombre_fichero   = explode(',',$nombre_ficheros);
    

    for($i=0;$i<count($nombre_fichero);$i++){
        
        if (file_exists($aremox_dirtmp.$nombre_fichero[$i])) {
            if (copy($aremox_dirtmp.$nombre_fichero[$i], $aremox_dirname.$nombre_fichero[$i])) {
                unlink($aremox_dirtmp.$nombre_fichero[$i]);
            } else {
                $resultado = 0;
            }
        } else { $resultado = 0;}
    }

    return 1;
}

function envioMail($tipo,$correo,$telefono, $texto,$ficheros, $id, $nombre){
    $options = get_option( 'aremox_formulario' );
    $attachments = array();
    if( $options != '' ) {
        $aremox_email = $options['aremox_email'];
      
        $upload_dir   = wp_upload_dir();
        $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/'.$id.'/';
        $nombre_fichero   = explode(',',$ficheros);
        for($i=0;$i<count($nombre_fichero);$i++){
            array_push($attachments, "$aremox_dirname$nombre_fichero[$i]");
        }
        $adjuntos = '';
        if(sizeof($attachments) > 0 ){
            $url = get_site_url();
            $adjuntos = '<p><a href="'.$url.'/wp-admin/admin.php?page=aremox-formulario" > Ver adjuntos </a></p>';
        }
        $message = "<b>Correo:</b> $correo <br><b> Telefono:</b> $telefono <br><b> Mensaje:</b> <br> $texto<br>$adjuntos";
        $headers[]= "From: Ayuntamiento de El Bohodón <arenasmorante@gmail.com>";

        wp_mail( $aremox_email, $tipo, $message, $headers, $attachments );
    }
}
function tipo_de_contenido_html() {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'tipo_de_contenido_html' );

function onMailError( $wp_error ) {
    echo "<pre>";
    print_r($wp_error);
    echo "</pre>";
}
add_action( 'wp_mail_failed', 'onMailError', 10, 1 );

function recurseRmdir($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? recurseRmdir("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  }

  function extension($fichero){
    $file_ext   = explode('.',$fichero);
    $file_ext   = strtolower(end($file_ext));
    return $file_ext;
  }

  function montrarImagen(WP_REST_Request $request){
    if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
      }
    $upload_dir   = wp_upload_dir();
    $id = sanitize_text_field($request->get_param('id'));
    $fichero = sanitize_text_field($request->get_param('fichero'));
    $file = $upload_dir['basedir'].'/aremox-formulario/'.$id.'/'.$fichero;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 //   ob_clean();
    flush();
    readfile($file);
    exit;
  }

  function descargarImagen($id, $fichero){
    if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
      }
    $upload_dir   = wp_upload_dir();
    $file = $upload_dir['basedir'].'/aremox-formulario/'.$id.'/'.$fichero;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
 //   ob_clean();
    flush();
   echo readfile($file);
   die();
  }


  function custom_redirects() {
    $parts = parse_url($_SERVER['REQUEST_URI']);
    if ($parts['path'] == '/descarga/') {
        if (isset($_GET['id']) && isset($_GET['fichero'])) {
    descargarImagen($_GET['id'], $_GET['fichero']);
    die();
    }}
 
}
add_action( 'template_redirect', 'custom_redirects' );