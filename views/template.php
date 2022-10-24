<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$color = GestorConfigController::get_config("sidebar_color");

$color = isset($color["config_value"]) ? $color["config_value"] : "light";
$subs = GestorSubdivisionesController::subdivisiones();
$workers = GestorWorkersController::workers();
$workers_actives = GestorWorkersController::workers_actives();
$types = GestorConfigController::types();
?>
<!DOCTYPE html>
<html lang="es">

<!-- begin::Head -->

<head>
	<meta charset="utf-8" />
	<title>KP Plumbing</title>
	<meta name="description" content="Administrador">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<!--begin:: Global Mandatory Vendors -->
	<link href="views/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

	<!--end:: Global Mandatory Vendors -->

	<!--begin:: Global Optional Vendors -->

	<link href="views/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />



	<link href="views/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />

	<link href="views/assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/vendors/custom/vendors/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css" />

	<!--end:: Global Optional Vendors -->

	<!--begin::Global Theme Styles(used by all pages) -->
	<link href="views/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />

	<!--end::Global Theme Styles -->
	<link href="views/assets/app/custom/login/login-v4.default.css" rel="stylesheet" type="text/css" />

	<!--begin::Layout Skins(used by all pages) -->
	<link href="views/assets/demo/default/skins/header/base/light.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/demo/default/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/demo/default/skins/brand/dark.css" rel="stylesheet" type="text/css" />
	<link href="views/assets/demo/default/skins/aside/<?php echo $color ?>.css" rel="stylesheet" type="text/css" />


	<link rel="stylesheet" href="views/lib/css/monokai.css">

	<!--end::Layout Skins -->
	<link rel="stylesheet" href="views/css/dataTables.bootstrap4.css">
	<link rel="stylesheet" href="views/assets/app/custom/pricing/pricing-v1.default.css">
	<link rel="shortcut icon" href="views/assets/media/logos/favicon.ico" />
	<link rel="stylesheet" href="views/css/leaflet.css">
	<link rel="stylesheet" href="views/css/leaflet-routing-machine.css" />
	<link rel="stylesheet" href="views/css/leaflet.extra-markers.min.css">
	<link rel="stylesheet" type="text/css" href="views/css/estilos.css?e=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="views/css/custom.css">
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading _body_">

	<!-- begin:: Page -->

	<?php if (isset($_GET["action"]) && $_GET["action"] == "login") : ?>

		<?php
		$module = new Enlaces();
		$module->enlacesController();
		?>

	<?php else : ?>
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="index">
					<h4 class="text-white"><b>KP Plumbing</b></h4>
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<!-- <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button> -->
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->

		<!-- INCLUDE MODULE -->

		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- SIDE BAR -->

				<?php include("views/modules/sidebar.php") ?>

				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- HEADER -->
					<?php include("views/modules/header.php") ?>


					<!-- CONTENIDO -->
					<?php
					$module = new Enlaces();
					$module->enlacesController();
					?>

					<!-- FOOTER -->
					<?php include("views/modules/footer.php") ?>

				</div>
			</div>
		</div>

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>
		<div class="cubierta"></div>


	<?php endif; ?>

	<!-- end::Scrolltop -->


	<!-- Modals -->
	<!-- Modal changes -->
	<div class="modal fade" id="new_changes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_7" aria-hidden="true" style="z-index: 1350;">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_7">
						Updating Sheets Modules...
					</h5>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340" style="text-align: center;">

						<img width="336" height="215" style="margin-top: 60px;" src="views/images/update.gif" alt="">



					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new builder -->
	<div class="modal fade" id="new_builder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_7" aria-hidden="true" style="z-index: 1350;">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_7">
						New Builder
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formNewBuilder">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control" name="nombre">
										<span class="form-text text-muted">Enter name</span>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="createBuilder">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal edit builder -->
	<div class="modal fade" id="edit_builder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_8" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_8">
						Edit Builder
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formEditBuilder">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control nombre" name="nombre">
										<input type="hidden" class="id" name="id">
										<span class="form-text text-muted">Enter name</span>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="updateBuilder">UPDATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new fixture -->
	<div class="modal fade" id="new_fixture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_9" aria-hidden="true" style="z-index: 1350;">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_9">
						New Fixture
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formNewFixture">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control" name="nombre">
									</div>
									<div class="form-group">
										<label>Type:</label>
										<select name="tipo" class="form-control">
											<option value="0">Integer</option>
											<option value="1">Yes/No</option>
										</select>
									</div>
									<div class="form-group">
										<label>Is Extra:</label>
										<select name="extra" class="form-control">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
									</div>
									<div class="form-group">
										<label>Price charge:</label>
										<input type="text" class="form-control" name="precio_cobrar">
									</div>
									<div class="form-group">
										<label>Price to pay:</label>
										<input type="text" class="form-control" name="precio_pagar">
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="createFixture">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal edit builder -->
	<div class="modal fade" id="edit_fixture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_10" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_10">
						Edit Fixture
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formEditFixture">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control nombre" name="nombre">
										<input type="hidden" class="id" name="id">
									</div>
									<div class="form-group">
										<label>Type:</label>
										<select name="tipo" class="form-control tipo">
											<option value="0">Integer</option>
											<option value="1">Yes/No</option>
										</select>
									</div>
									<div class="form-group">
										<label>Is Extra:</label>
										<select name="extra" class="form-control extra">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
									</div>
									<div class="form-group">
										<label>Price charge:</label>
										<input type="text" class="form-control precio_cobrar" name="precio_cobrar">
									</div>
									<div class="form-group">
										<label>Price to pay:</label>
										<input type="text" class="form-control precio_pagar" name="precio_pagar">
									</div>
									<div class="form-group">
										<label>Items select:</label>
										<span class="text-muted">separados por comas (,)</span>
										<input type="text" class="form-control items" name="items">
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="updateFixture">UPDATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new worker -->
	<div class="modal fade" id="new_worker" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_0" aria-hidden="true" style="z-index: 1350;">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_0">
						New Installer
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formadd_Worker">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Full name:</label>
										<input type="text" class="form-control" name="nombre">
										<span class="form-text text-muted">Enter full name</span>
									</div>
									<div class="form-group">
										<label>Email:</label>
										<input type="email" class="form-control" name="correo">
										<span class="form-text text-muted">Enter email</span>
									</div>
									<div class="form-group">
										<label>Phone</label>
										<input type="text" class="form-control" name="telefono">
										<span class="form-text text-muted">(Opional)</span>
									</div>
									<div class="form-group">
										<label>Image:</label>
										<div class="custom-file">
											<input type="file" name="image_worker" class="custom-file-input">
											<label for="" class="custom-file-label">Selec an image</label>
										</div>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="add_worker">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal edit worker -->
	<div class="modal fade" id="edit_worker" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						Edit Installer
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formEditWorker">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">
									<div class="form-group">
										<label>Full name:</label>
										<input type="text" class="form-control nombre" name="nombre">
										<input type="hidden" class="id" name="id">
										<span class="form-text text-muted">Enter full name</span>
									</div>
									<div class="form-group">
										<label>Email:</label>
										<input type="email" class="form-control correo" name="correo">
										<span class="form-text text-muted">Enter email</span>
									</div>
									<div class="form-group">
										<label>Phone</label>
										<input type="text" class="form-control telefono" name="telefono">
										<span class="form-text text-muted">(Opional)</span>
									</div>
									<div class="form-group">
										<label>Image:</label>
										<div class="custom-file">
											<input type="file" name="image_worker" class="custom-file-input">
											<label for="" class="custom-file-label">Selec an image</label>
										</div>
									</div>
									<div class="form-group">
										<label>Speciality</label>
										<select name="especialidad" class="form-control especialidad">
											<option value="0">Seleccionar</option>
											<?php
											foreach ($types as $tipo) {
												echo "<option value='" . $tipo['id'] . "'>" . $tipo['nombre'] . "</option>";
											}

											?>
										</select>
										<span class="form-text text-muted">(Opional)</span>
									</div>
									<div class="form-group">
										<label>Active</label>
										<select name="active" class="form-control estado">
											<option value="1">Yes</option>
											<option value="0">No</option>
										</select>
									</div>
								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="updateWorker">UPDATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new subdivision -->
	<div class="modal fade" id="m_new_subdivision" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_2" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_2">
						New Subdivision
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="360">

						<form class="kt-form" id="formNewSubdivision">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">


									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control" name="nombre">
										<span class="form-text text-muted">Enter subdivision</span>
									</div>
									<div class="form-group w-100">
										<label>Builders</label>
										<select name="textos[]" class="select_2_b" multiple="multiple" id="builders_select_add">
										</select>
									</div>
									<div class="form-group">

										<label>Forsyth county:</label>
										<select name="forsyth_county" class="form-control">
											<option value="No">No</option>
											<option value="Yes">Yes</option>
										</select>
									</div>

									<div class="form-group">

										<label>Sewer System:</label>
										<select name="sewer_system" class="form-control">
											<option value="0">Sewer </option>
											<option value="1">Septic System</option>
										</select>
									</div>



									<div class="form-group">
										<label>
											Address:
										</label>
										<textarea name="direccion" id="direccion_edit" max="250" class="form-control"></textarea>
										<span class="form-text text-muted">(Optional)</span>
									</div>
									<div class="form-group">

										<label>Lots:</label>
										<input type="text" class="form-control" name="lotes">
										<span class="form-text text-muted">(Optional)</span>
									</div>
									<div class="form-group">
										<label>
											Location:
											<span class="form-text text-muted">
												Tap on map to save location
											</span>
										</label>
										<div id="mapa_add_sub" class="mapa_sub"></div>
										<input type="hidden" name="latlng" id="latlng">
									</div>

								</div>
							</div>
							<div class="kt-portlet__foot">
								<div class="kt-form__actions">

								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="crearSubdivision">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal edit subdivision -->
	<div class="modal fade" id="m_edit_subdivision" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_3" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_3">
						Edit Subdivision
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="340">

						<form class="kt-form" id="formEditSubdivision">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">


									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control nombre" name="nombre">
										<input type="hidden" class="id" name="id">
									</div>
									<div class="form-group w-100">
										<label>Builders:</label>
										<select name="textos[]" class="select_2_b" multiple="multiple" id="builders_select_edit"></select>
									</div>

									<div class="form-group">

										<label>Forsyth county:</label>
										<select name="forsyth_county" class="form-control" id="forsyth">
											<option>Select</option>
											<option value="No">No</option>
											<option value="Yes">Yes</option>
										</select>
									</div>

									<div class="form-group">

										<label>Sewer System:</label>
										<select name="sewer_system" class="form-control" id="sewer">
											<option selected="selected">Select</option>
											<option value="0">Sewer </option>
											<option value="1">Septic System</option>
										</select>
									</div>


									<div class="form-group">
										<label>
											Address:
										</label>
										<textarea name="direccion" id="direccion" max="250" class="form-control direccion"></textarea>
										<span class="form-text text-muted">(Optional)</span>
									</div>
									<div class="form-group">

										<label>Lots:</label>
										<input type="text" class="form-control lotes" name="lotes">
										<span class="form-text text-muted">(Optional)</span>
									</div>
									<div class="form-group">
										<label>
											Location:
											<span class="form-text text-muted">
												Tap on map to save location
											</span>
										</label>
										<div id="mapa_edit_sub" class="mapa_sub"></div>
										<input type="hidden" name="latlng" id="latlng_edit" class="latlng">
									</div>

								</div>
							</div>
							<div class="kt-portlet__foot">
								<div class="kt-form__actions">

								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="updateSubdivision">UPDATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new type -->
	<div class="modal fade" id="m_new_type" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_4" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_4">
						New Type
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="280">


						<div class="kt-portlet__body">
							<div class="kt-section kt-section--first">

								<form id="form_type">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control nombre" name="name" id="typeName">
									</div>
									<div class="form-group">
										<label>Default color:</label>
										<input type="text" class="btn jscolor" name="defaul_color_" placeHolder="Default color" value="ffcd20">
									</div>
									<div class="form-row">
										<div class="col">
											<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
												<input type="checkbox" id="ordered" name="ordered" checked> Ordered
												<span></span>
											</label>
										</div>
										<div class="col">
											<input type="text" name="color_ordered" class="btn jscolor" value='2AC3BF'>
										</div>

									</div>
									<div class="form-row mt-1">
										<div class="col">
											<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
												<input type="checkbox" id="marked" name="marked" checked> Marked
												<span></span>
											</label>
										</div>
										<div class="col">
											<input type="text" name="color_marked" class="btn jscolor" value="ABB3B6">
										</div>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" id="attachment" name="attachment" checked> Attachment
											<span></span>
										</label>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" id="billiable" name="billiable" checked> Billiable
											<span></span>
										</label>

										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand ml-2">
											<input type="checkbox" id="default_billiable" name="default_billiable" checked> Default
											<span></span>
										</label>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" id="archive" name="archive" checked> Archive
											<span></span>
										</label>
									</div>
									<div class="form-group">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" id="warning" name="warning" checked> Warning
											<span></span>
										</label>
									</div>


									<div class="form-group w-100">
										<select name="textos[]" class="select_2_m" multiple="multiple"></select>
									</div>
								</form>


							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="createType">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal edit type -->
	<div class="modal fade" id="m_edit_type" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_14" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_14">
						Edit Type
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body">
					<div class="kt-scroll" data-scroll="true" data-height="280">


						<div class="kt-portlet__body">
							<div class="kt-section kt-section--first">

								<form id="form_edit_type">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control nombre" name="name" id="name_type_edit">
										<span class="form-text text-muted">Enter type name</span>

										<input type="hidden" name="id" id="id_type_edit">
									</div>
									<div class="form-group">
										<label>Default color:</label>
										<input type="text" id="defaul_color_edit" name="defaul_color_" class="btn jscolor">
									</div>
									<div class="form-row">
										<div class="col">
											<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
												<input type="checkbox" name="ordered" id="check_ordered_edit" checked> Ordered
												<span></span>
											</label>
										</div>
										<div class="col">
											<input type="text" name="color_ordered" id="color_ordered_edit" class="btn jscolor">
										</div>

									</div>
									<div class="form-row mt-1">
										<div class="col">
											<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
												<input type="checkbox" name="marked" id="check_marked_edit" checked> Marked
												<span></span>
											</label>
										</div>
										<div class="col">
											<input type="text" name="color_marked" id="color_marked_edit" class="btn jscolor">
										</div>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" name="attachment" id="check_attach_edit" checked> Attachment
											<span></span>
										</label>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" name="billiable" id="check_billiable_edit" checked> Billiable
											<span></span>
										</label>

										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand ml-2">
											<input type="checkbox" id="default_billiable_edit" name="default_billiable" checked> Default
											<span></span>
										</label>
									</div>
									<div class="form-group mb-2">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" name="archive" id="check_archive_edit" checked> Archive
											<span></span>
										</label>
									</div>
									<div class="form-group">
										<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
											<input type="checkbox" name="warning" id="check_warning_edit" checked> Warning
											<span></span>
										</label>
									</div>


									<div class="form-group w-100">
										<select name="textos[]" class="select_2_m" multiple="multiple" id="textos_edit_type"></select>
									</div>
								</form>


							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="seveType">UPDATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal new activity -->
	<div class="modal fade" id="m_new_activity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_5" aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_5">
						New Activity
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body cont_scroll">
					<div class="kt-scroll scroll_type" data-scroll="true" data-height="360" id="scroll_type">

						<form class="kt-form" id="formNewActivity">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">

									<div class="form-group">
										<label>Type:</label>
										<select name="tipo" class="form-control" id="type_new_activity">
											<option value="#">select one</option>
											<?php foreach ($types as $item) { ?>
												<option value="<?php echo $item['id'] ?>" data-name="<?php echo $item['nombre'] ?>">
													<?php echo $item["nombre"] ?>
												</option>
											<?php } ?>
										</select>
									</div>

									<div class="form-row list_texts"></div>

									<div class="form-row">
										<div class="col flex_ content_ordened_new hidden form-group">
											<label class="pr-3" for="ordened_new">Ordered:</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="ordened" id="ordened_new">
													<span></span>
												</label>
											</span>
										</div>
										<div class="col flex_ content_marked_new hidden form-group">
											<label class="pr-3" for="marked_new">Marked:</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="marked" id="marked_new">
													<span></span>
												</label>
											</span>
										</div>
										<div class="col flex_ content_billiable_new hidden form-group">
											<label class="pr-3" for="billiable_new">Billiable:</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="billiable" id="billiable_new" checked>
													<span></span>
												</label>
											</span>
										</div>

										<div class="col flex_ attachment_new hidden form-group">
											<label class="mr-2">
												Attachment:
											</label>
											<div class="custom-file">
												<input type="file" name="attachment_f[]" class="custom-file-input" multiple="" id="attachment_new">
												<label for="" class="custom-file-label">add file</label>
											</div>
										</div>


									</div>

									<div class="form-row">

										<!-- <div class="col flex_ content_archive_new hidden form-group">
												<label class="pr-3" for="billiable_new">Archive:</label>
												<span class="kt-switch kt-switch--icon">
													<label class="m-0">
														<input type="checkbox" name="archive" id="archive_new">
														<span></span>
													</label>
												</span>
											</div> -->
									</div>

									<div class="form-row">
										<div class="col form-group">
											<label>Subdivision:</label>
											<select name="subdivision" class="form-control lot_sub" data-content="list_lot1" id="sub_new_act">
												<option value="#">select one</option>
												<?php foreach ($subs as $item) { ?>
													<option value="<?php echo $item['id'] ?>">
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="col form-group">
											<label>Lot:</label>
											<input type="text" class="form-control lot_" data-content="list_lot1" name="lote" id="lote_new_act">
											<span class="form-text text-muted">Number of lot</span>
										</div>
									</div>

									<div class="form-row">
										<div class="col form-group">
											<label>Date:</label>
											<input type="text" class="form-control" id="kt_datepicker_1" readonly placeholder="Select a date" name="fecha" />
										</div>
										<div class="col form-group">
											<label>Installer:
												<a href="#" class="n_worker ml-2" data-reload="false">New Installer</a>
											</label>
											<select name="worker" class="form-control" id="select_new_worker">
												<option value="#">select one</option>
												<?php foreach ($workers_actives as $item) { ?>
													<option value="<?php echo $item['id'] ?>">
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
											<span class="form-text text-muted">(Opcional)</span>
										</div>

									</div>

									<div class="address_new hidden form-row">
										<div class="col form-group">
											<label>Time:</label>
											<input type="text" class="form-control" id="time" placeholder="time" name="time" />
										</div>
										<div class="col form-group">
											<label>Address:</label>
											<input type="text" class="form-control" id="address" placeholder="address" name="address" />
											<span class="form-text text-muted">(Opcional)</span>
										</div>

									</div>

									<div class="message_lot1 flex_"></div>
									<div class="mb-3">
										<label>Activities in this lot:</label>
										<ul class="list_before hidden" id="list_lot1"></ul>
									</div>

									<div class="mb-3 attachment_list_1 hidden">
										<label>Attachment list:</label>
										<ul class="list_before text-shadow" id="attach_list_1"></ul>
									</div>

									<div class="mb-3 attachment_video_1 hidden">
										<label>Attachment list video:</label>
										<ul class="list_before text-shadow" id="attach_video_1"></ul>
									</div>

									<div class="form-group content_history_desc hidden">
										<h4>Description History</h4>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th style="max-width:70px"><b>Type</b></th>
													<th><b>Description</b></th>
												</tr>
											</thead>
											<tbody id="bodyHistory"></tbody>

										</table>
									</div>

									<div class="form-group">
										<label>
											Description:
										</label>
										<a href="#" id="history">History (show)</a>
										<textarea name="descripcion" id="descripcion_new_activity" class="form-control" style="height: 150px;"></textarea>
										<span class="form-text text-muted">(Opcional)</span>
									</div>

									<div class="form-group">
										<label>
											Administration:
										</label>
										<textarea name="administracion" id="administracion" class="form-control"></textarea>
										<span class="form-text text-muted">(Opcional)</span>
									</div>


								</div>
							</div>
							<div class="kt-portlet__foot">
								<div class="kt-form__actions">

								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="crearActivity">CREATE</button>
					<button type="button" class="btn btn-brand" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal edit activity -->
	<div class="modal fade" id="m_edit_activity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_6" aria-hidden="true">

		<div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel_6">
						Edit Activity
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"></span>
					</button>
				</div>
				<div class="modal-body scroll_t_2">
					<div class="kt-scroll" data-scroll="true" data-height="360" id="scroll_2">

						<form class="kt-form" id="formEditActivity">
							<div class="kt-portlet__body">
								<div class="kt-section kt-section--first">

									<div class="form-group">
										<label>Type:</label>
										<select name="tipo" class="form-control tipo" id="type_edit_activity">
											<option value="#">select one</option>
											<?php foreach ($types as $item) { ?>
												<option value="<?php echo $item['id'] ?>">
													<?php echo $item["nombre"] ?>
												</option>
											<?php } ?>
										</select>
									</div>
									<div class="form-row list_texts_edit"></div>
									<div class="form-row">
										<div class="col flex_ content_ordened_edit hidden form-group">
											<label class="pr-3" for="ordened_edit">Ordered:</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="ordened" id="ordened_edit">
													<span></span>
												</label>
											</span>
										</div>
										<div class="col flex_ content_marked_edit hidden form-group">
											<label class="pr-3" for="marked_edit">Marked:</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="marked" id="marked_edit">
													<span></span>
												</label>
											</span>
										</div>
										<div class="col flex_ content_billiable_edit hidden form-group">
											<label class="pr-3" for="billiable_edit">
												Billiable:
											</label>
											<span class="kt-switch kt-switch--icon">
												<label class="m-0">
													<input type="checkbox" name="billiable" id="billiable_edit">
													<span></span>
												</label>
											</span>
										</div>
										<div class="col flex_ attachment_edit hidden form-group">
											<label class="mr-2">
												Attachment:
											</label>
											<div class="custom-file">
												<input type="file" name="attachment_f[]" class="custom-file-input" multiple="" id="attachment_edit">
												<label for="" class="custom-file-label">add file</label>
											</div>
										</div>
									</div>
									<div class="form-row">

										<!-- <div class="col flex_ content_archive_edit hidden form-group">
												<label class="pr-3" for="archive_edit">
													Archive:
												</label>
												<span class="kt-switch kt-switch--icon">
													<label class="m-0">
														<input type="checkbox" name="archive" id="archive_edit">
														<span></span>
													</label>
												</span>
											</div> -->
									</div>

									<div class="form-row">
										<div class="col form-group">
											<label>Subdivision:</label>
											<select name="subdivision" class="form-control subdivision lot_sub" data-content="list_lot2" id="sub_edit_act">
												<option value="#">select one</option>
												<?php foreach ($subs as $item) { ?>
													<option value="<?php echo $item['id'] ?>">
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
										</div>
										<div class="col form-group">
											<label>Lot:</label>
											<input type="text" class="form-control lote lot_" name="lote" data-content="list_lot2" id="lote_edit_act">
											<span class="form-text text-muted">Number of lot</span>
										</div>

									</div>
									<div class="form-row">
										<div class="col form-group">
											<label>Date:</label>
											<input type="text" class="form-control fecha" id="kt_datepicker_2" readonly placeholder="Select a date" name="fecha" />
											<input type="hidden" name="id" class="id" id="act_id_Edit">
										</div>
										<div class="col form-group">
											<label>Installer:</label>
											<select name="worker" class="form-control worker">
												<option value="#">select one</option>
												<?php foreach ($workers as $item) { ?>
													<option value="<?php echo $item['id'] ?>">
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
											<span class="form-text text-muted">(Opcional)</span>
										</div>

									</div>

									<div class="address_new_edit hidden form-row">
										<div class="col form-group">
											<label>Time:</label>
											<input type="text" class="form-control" id="time_edit" placeholder="time" name="time" />
										</div>
										<div class="col form-group">
											<label>Address:</label>
											<input type="text" class="form-control" id="address_edit" placeholder="address" name="address" />
											<span class="form-text text-muted">(Opcional)</span>
										</div>

									</div>

									<div class="message_lot2 flex_"></div>
									<div class="mb-3">
										<label>Activities in this lot:</label>
										<ul class="list_before hidden" id="list_lot2"></ul>
									</div>

									<div class="mb-3 attachment_list_2 hidden">
										<label>Attachment list:</label>
										<ul class="list_before text-shadow" id="attach_list_2"></ul>
									</div>
									<div class="mb-3 attachment_video_2 hidden">
										<label>Attachment list Video:</label>
										<ul class="list_before text-shadow" id="attach_video_2"></ul>
									</div>

									<div class="form-group">
										<label>
											Description:
										</label>
										<textarea name="descripcion" id="descripcion" style="height: 150px;" class="form-control descripcion"></textarea>
										<span class="form-text text-muted">(Opcional)</span>
									</div>

									<div class="form-group">
										<label>
											Administration:
										</label>
										<textarea name="administracion" id="administracion_edit" class="form-control"></textarea>
										<span class="form-text text-muted">(Opcional)</span>
									</div>

								</div>
							</div>
						</form>

					</div>
				</div>
				<div class="modal-footer">
					<div class="w-100 progress_edit">
						<!-- <div class="progress progress-sm">
								<div class="progress-bar kt-bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div> -->
					</div>
					<div class="w-100" style="display: flex;justify-content: space-between;">
						<div>
							<button class="btn btn-warning" id="b_actividad">
								Delete
							</button>
						</div>
						<div>
							<a class="btn btn-primary" id="link_rough_sheet" style="color:white;" target="_blank">Rough Sheet</a>

							<!-- <form action="views/modules/downPdfSheetBill.php" method="post" target="_blank" style="display: inline-block;" id="formSummaryPayPrint"> -->
							<form action="views/modules/downPdfSheetPay.php" method="post" target="_blank" style="display: inline-block;" id="formSummaryPayPrint">
								<input type="hidden" name="id">
								<input type="submit" value="Print" class="btn btn-primary" />
							</form>
							<!-- <form action="views/modules/downPdfSheetBill.php" method="post" target="_blank" style="display: inline-block;" id="formSummaryBillPrint">
									<input type="hidden" name="id" >
									<input type="submit" value="Print S. Bill" class="btn btn-primary" />
								</form> -->
							<button class="btn btn-primary" id="editActivity">UPDATE</button>
							<button class="btn btn-primary" id="cloneActivity">
								Prepare Duplicate
							</button>
							<button type="button" class="btn btn-brand" data-dismiss="modal">
								Close
							</button>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- SCRIPTS TEMPLATE -->

	<script>
		var KTAppOptions = {
			"colors": {
				"state": {
					"brand": "#5d78ff",
					"dark": "#282a3c",
					"light": "#ffffff",
					"primary": "#5867dd",
					"success": "#34bfa3",
					"info": "#36a3f7",
					"warning": "#ffb822",
					"danger": "#fd3995"
				},
				"base": {
					"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
					"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
				}
			}
		};
	</script>

	<script src="views/assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>

	<script src="views/assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>


	<!--end:: Global Mandatory Vendors -->

	<!--begin:: Global Optional Vendors -->

	<script src="views/assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

	<script src="views/assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>

	<script src="views/assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
	<script src="views/assets/vendors/custom/components/vendors/bootstrap-timepicker/init.js" type="text/javascript"></script>

	<script src="views/assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript"></script>


	<script src="views/assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/select2/dist/js/select2.full.js" type="text/javascript"></script>


	<script src="views/assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
	<script src="views/assets/vendors/custom/components/vendors/bootstrap-markdown/init.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>

	<script src="views/assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
	<script src="views/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
	<script src="views/assets/vendors/custom/components/vendors/sweetalert2/init.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
	<script src="views/assets/app/custom/general/components/extended/bootstrap-notify.js" type="text/javascript"></script>
	<script src="views/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>



	<!--begin::Global Theme Bundle(used by all pages) -->
	<script src="views/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>


	<!--begin::Page Scripts(used by this page) -->
	<script src="views/assets/app/custom/general/components/calendar/google.js" type="text/javascript"></script>
	<!-- <script src="views/assets/app/custom/general/dashboard.js" type="text/javascript"></script> -->

	<!--end::Page Scripts -->

	<!--begin::Global App Bundle(used by all pages) -->
	<script src="views/assets/app/bundle/app.bundle.js" type="text/javascript"></script>


	<?php if (isset($_GET["action"]) && $_GET["action"] == "admins") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/list-datatable.js" type="text/javascript"></script>
	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "builders") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-constructoras.js" type="text/javascript"></script>
		<script src="views/js/constructoras.js"></script>

	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "fixtures") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-fixtures.js" type="text/javascript"></script>
		<script src="views/js/fixtures.js"></script>

	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "roughForms") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-rough-forms.js" type="text/javascript"></script>

	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "workers") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-workers.js" type="text/javascript"></script>
	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "subdivisions") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-subdivisions.js" type="text/javascript"></script>
	<?php } ?>
	<?php if (isset($_GET["action"]) && $_GET["action"] == "activity") { ?>
		<script src="views/assets/js/plugin.bundle.js" type="text/javascript"></script>
		<script src="views/assets/js/scripts.bundle.js" type="text/javascript"></script>
		<script src="views/js/list-activities.js" type="text/javascript"></script>
	<?php } ?>


	<script src="views/assets/app/custom/general/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
	<script src="views/assets/app/custom/general/crud/forms/widgets/select2.js" type="text/javascript"></script>
	<!-- CUSTOM!! -->


	<script src="views/js/alertify.js"></script>
	<script src="views/js/jquery.dataTables.js"></script>
	<script src="views/js/ajax.js"></script>
	<script src="views/js/utilidades.js"></script>


	<!-- <script src="views/js/leaflet.js"></script> -->
	<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>
	<script src="views/js/leaflet.extra-markers.js"></script>
	<script src="views/js/leaflet-routing-machine.js"></script>
	<script src="views/js/Control.Geocoder.js"></script>

	<script src="views/js/jscolor.js"></script>
	<script src="views/js/auth.js"></script>
	<script src="views/js/gestorConfig.js"></script>
	<script src="views/js/usuarios.js"></script>
	<script src="views/js/subdivision.js"></script>
	<script src="views/js/workers.js"></script>
	<script src="views/js/actividades.js"></script>
	<script src="views/js/rough_forms.js"></script>

	<!-- Script para actualizar la base de datos -->
	<script src="views/js/updatedatabase.js"></script>

	<script>
		// $("#m-table").KTDatatable()
		var upgrade = localStorage.getItem("upgrade");
		console.log(JSON.parse(upgrade) === true);
		if (!upgrade) {
			localStorage.setItem("upgrade", true);
			$('#new_changes').modal('show');
		} else {
			console.log("no");
		}



		$("#table__r").DataTable({
			responsive: true
		})

		$(".select_2").select2()

		$(".select_2_b").select2()

		$(".select_2_m").select2({
			"text": "label attribute",
			tags: true
		})

		$('#kt_daterangepicker_1').daterangepicker({
			buttonClasses: ' btn',
			applyClass: 'btn-primary filter_range',
			cancelClass: 'btn-secondary cancel_range',
			locale: {
				format: 'MM/DD/YYYY'
			}
		});

		$(".filter_range").on("click", function() {

			setTimeout(() => {

				var val = $('#kt_daterangepicker_1').val()


				var data = {
					set_filter_list: true,
					key: "filter_daterange",
					val: val
				}
				console.log(data);

				ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
					.then((res) => {

						console.log(res);
						// window.location.reload()
					}, (fail) => {

						console.log(fail);
					})

			}, 500);




		})

		$(".cancel_range").on("click", function() {
			cancel_range_date()
		})

		$(".cancel_range_outside").on("click", function(e) {
			e.preventDefault()
			cancel_range_date()
		})

		$("#formSummaryPayPrint").on("submit", function(e) {

			var id_print = $('#formSummaryPayPrint input[name="id"]').val()
			if (id_print == undefined || id_print == "") {
				e.preventDefault()
			}
		})
		$("#formSummaryBillPrint").on("submit", function(e) {

			var id_print = $('#formSummaryBillPrint input[name="id"]').val()
			if (id_print == undefined || id_print == "") {
				e.preventDefault()
			}
		})

		function cancel_range_date() {

			setTimeout(() => {

				var val = $('#kt_daterangepicker_1').val()


				var data = {
					set_filter_list: true,
					key: "filter_daterange",
					val: ""
				}

				ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
					.then((res) => {

						console.log(res);
						window.location.reload()
					}, (fail) => {

						console.log(fail);
					})

			}, 500);
		}
	</script>


</body>

</html>