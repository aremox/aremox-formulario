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
 
if (  ! empty( $upload_dir['basedir'] ) ) {
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/tmp';
        if ( ! file_exists( $aremox_dirname ) ) {
        wp_mkdir_p( $aremox_dirname );
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
        rmdir( $aremox_dirname );
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


      $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario';


    
    
    $aremox_formulario = $wpdb->get_results("SELECT * FROM $tabla_aremox_formulario");

      require( 'inc/options-page-wrapper.php' );
    
}

function aremox_formulario_shortcode() {

    $insertado = false;
    $upload_dir   = wp_upload_dir();
    $aremox_dirname = $upload_dir['basedir'].'/aremox-formulario/tmp/';
         

    if(isset($_FILES['files'])){

        for($i=0;$i<count($_FILES['files']['name']);$i++){
            foreach($_FILES['files'] as $v=>$file) {
                $errors = array();
                $file_name = strtolower(sanitize_text_field($_FILES['files']['name'][$i]));
                $file_size = $_FILES['files']['size'][$i];
                $file_tmp = $_FILES['files']['tmp_name'][$i];
                $file_type = $_FILES['files']['type'][$i];
                $file_ext = strtolower(end(explode('.',$_FILES['files']['name'][$i])));
 
                $extensions = array("jpeg","jpg","png","docx","doc","pdf");
 
                if(in_array($file_ext,$extensions) === true){
                    move_uploaded_file($file_tmp,$aremox_dirname.$file_name);
                    echo $aremox_dirname.$file_name;
                    print_r(scandir($aremox_dirname));
                }
            }
        }
    }
  
    global $wpdb; // Este objeto global permite acceder a la base de datos de WP
    // Si viene del formulario  graba en la base de datos
    // Cuidado con el último igual de la condición del if que es doble
    if ($_POST['nombre'] != ''
        AND is_email($_POST['correo'])
        AND strlen ($_POST['telefono']) == '9'
        AND $_POST['tipo'] != ''
        AND strlen($_POST['texto']) >= '9'      
        AND $_POST['aceptacion'] == '1'
        AND wp_verify_nonce($_POST['aremox_formulario_nonce'], 'grabar_aremox_formulario')
    ) {
        var_dump($_POST);
        echo "<br>";
        var_dump($_FILES);
        $tabla_aremox_formulario = $wpdb->prefix . 'aremox_formulario'; 
        $nombre = sanitize_text_field($_POST['nombre']);
        $correo = sanitize_text_field($_POST['correo']);
        $telefono = (int)$_POST['telefono'];
        $tipo = sanitize_text_field($_POST['tipo']);
        $texto = sanitize_text_field($_POST['texto']);
        $aceptacion = (int)$_POST['aceptacion'];
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
                'aceptacion' => $aceptacion,
                'ip' => $ip,
                'created_at' => $created_at,
            )
        );
      
        $insertado = true;
        
    }

        ob_start();

        if($insertado == true){
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

  