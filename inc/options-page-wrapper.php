<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Consulta de formulaarios enviados', 'WpAdminStyle' ); ?></h1>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h2><span><?php esc_attr_e( 'Tabla de formularios', 'WpAdminStyle' ); ?></span></h2>

						<div class="inside">
                        <table class="wp-list-table widefat fixed striped"><thead><tr><th>Nombre</th><th>Correo</th>
                                    <th>Teléfono</th><th>Tipo</th><th>Texto</th>
                                    <th>created_at</th></tr></thead>
                                    <tbody id="the-list">
							<?php foreach ( $aremox_formulario as $registro ) {
                                    $nombre = esc_textarea($registro->nombre);
									$correo = esc_textarea($registro->correo);
									$telefono = (int)$registro->telefono;
									$tipo = esc_textarea($registro->tipo);
									$texto = esc_textarea($registro->texto);
									$created_at = esc_textarea($registro->created_at);

                                    echo "<tr>
                                        <td>$nombre</td>
                                        <td>$correo</td><td>$telefono</td><td>$tipo</td>
                                        <td>$texto</td><td>$created_at</td>
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