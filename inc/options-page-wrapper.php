<?php add_thickbox(); ?>
<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Consulta de formularios enviados', 'WpAdminStyle' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h2><span><?php esc_attr_e( 'Tabla de formularios', 'WpAdminStyle' ); ?></span></h2>

						<div class="inside">
                        <table class="wp-list-table widefat fixed striped"><thead><tr><th>Nombre</th><th>Correo</th>
                                    <th>Teléfono</th><th>Tipo</th><th>Texto</th><th>Adjuntos</th>
                                    <th>created_at</th></tr></thead>
                                    <tbody id="the-list">
							<?php foreach ( $aremox_formulario as $registro ) {
									$id = (int)$registro->id;
                                    $nombre = esc_textarea($registro->nombre);
									$correo = esc_textarea($registro->correo);
									$telefono = (int)$registro->telefono;
									$tipo = esc_textarea($registro->tipo);
									$texto = esc_textarea($registro->texto);
									$ficheros = explode(',',esc_textarea($registro->ficheros));
									$created_at = esc_textarea($registro->created_at);

                                    echo "<tr>
                                        <td>$nombre</td>
                                        <td>$correo</td><td>$telefono</td><td>$tipo</td>
										<td>$texto</td><td><ul style='margin: 0;'>";
									foreach ( $ficheros as $i => $fichero ) {
										$indice = $i +1;
										$file_ext = extension($fichero);
										$extensions = array("jpeg","jpg","png","bmp");
										if(in_array($file_ext,$extensions) === true){
										print('	<form action="" method="POST" enctype="multipart/form-data" style="text-align:center;padding: 100px 0;">
    <button  type="submit" onclick="this.form.submit();" style="background:red;padding:10px;color:#fff;cursor:pointer;"><b>Download File</b></button>
	<input type="hidden" name="download" />
	<input type="hidden" name="id" value="'.$id.'" />
	<input type="hidden" name="fichero" value="'.$fichero.'" />
</form>');
										}else{
											echo "<li><a href='#?id=$id&fichero?$fichero' download='$fichero'>Fichero $indice</li>";
										}
									/*	echo "<li><a href='#TB_inline?&width=600&height=550&inlineId=modal-$indice' class='thickbox'>Fichero $indice</a></li>";
										$url = get_site_url();
										$img = "$url/wp-json/aremox/v1/imagen?id=$id&fichero=$fichero"; 
										echo "<div id='modal-$indice' style='display:none;'>
										<img src='$img' alt='Fichero $indice' style='width:100%; height:100%;
										object-fit: cover;'>
										</div>";*/
									}
									echo "</ul></td><td>$created_at</td>
                                        </tr>";
                            }
                            ?>
                            </tbody></table>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">

						<h2><span><?php esc_attr_e(
									'Opciones', 'WpAdminStyle'
								); ?></span></h2>

						<div class="inside">
							<p><?php esc_attr_e(
									'Opciones como el mail u otras cosas'
								); ?></p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables -->

			</div>
			<!-- #postbox-container-1 .postbox-container -->

		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->


