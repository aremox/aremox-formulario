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
										$url = get_site_url();
										if(in_array($file_ext,$extensions) === true){
											//echo "<li><a href='$url/descarga/?id=$id&fichero=$fichero'  target='_blank' >Fichero $indice</li>";
											echo "<li><a href='#TB_inline?&width=600&height=550&inlineId=modal-$indice' class='thickbox'>Fichero $indice</a></li>";
										}else{
											echo "<li><a href='$url/descarga/?id=$id&fichero=$fichero' download='$fichero'>Fichero $indice</li>";
										}
										
										$url = get_site_url();
										$img = "$url/descarga/?id=$id&fichero=$fichero"; 
										echo "<div id='modal-$indice' style='display:none;'>
										<img src='$img' alt='Fichero $indice' style='width:100%; height:100%;
										object-fit: cover;'>
										</div>";
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
							<form name="aremox_email_form" action="" method="post">

								<input type="hidden" name="aremox_form_submitted" value="Y">
									
								<label for="aremox_email">Mail de destinatario</label>
								<input name="aremox_email" id="aremox_email" type="text" value="<?php echo $aremox_email; ?>"/>	<br>
								<label for="aremox_email">Mail del sitio web</label><br>
								<input name="aremox_email_origen" id="aremox_email_origen" type="text" value="<?php echo $aremox_email_origen; ?>"/>
								<p>
									<input class="button-primary" type="submit" name="aremox_email_submit" value="<?php esc_attr_e( 'Save' ); ?>" />
								</p>		
							</form>
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


