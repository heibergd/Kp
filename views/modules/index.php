<?php
if (!isset($_SESSION["usuario_validado"])) {
	echo "<script> window.location.href = 'login' </script>";
	exit();
}
// phpinfo();
if ($_SESSION["tipo"] != 0) {
	GestorConfigController::verficar_usuario("activities");
}

$subs = GestorSubdivisionesController::subdivisiones();
$workers = GestorWorkersController::workers();
$types = GestorConfigController::types();

$filter_sub = isset($_SESSION["filter_sub"]) ? $_SESSION["filter_sub"] : false;
$filter_lot = isset($_SESSION["filter_lot"]) ? $_SESSION["filter_lot"] : false;
$filter_type = isset($_SESSION["filter_type"]) ? $_SESSION["filter_type"] : false;
$filter_billi = isset($_SESSION["filter_billi"]) ? $_SESSION["filter_billi"] : false;
$filter_worker = isset($_SESSION["filter_worker"]) ? $_SESSION["filter_worker"] : false;
$filter_status = isset($_SESSION["filter_status"]) ? $_SESSION["filter_status"] : false;
$filter_daterange = isset($_SESSION["filter_daterange"]) ? $_SESSION["filter_daterange"] : false;
$show_filters = "hidden";

if ($filter_billi == "") {
	$filter_billi = false;
}
if ($filter_daterange == "" || $filter_daterange == "#") {
	$filter_daterange = false;
}

// echo "range " . $filter_daterange . "<br>";
// echo "date " .$_SESSION["specific_date"];



$total_a = GestorActividadesController::actividades_total();

$fil = GestorActividadesController::filters();

// var_dump($fil);
if ($fil != "") {
	$show_filters = "";
}

// echo $fil;

$calendar_hide = isset($_SESSION["toggle_calendar"]) ? $_SESSION["toggle_calendar"] : false;

$calendar_hide = ($calendar_hide != "" && $calendar_hide !== false) ? $calendar_hide : false;


?>

<!-- contenido -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">Dashboard</h3>
			<span class="kt-subheader__separator kt-subheader__separator--v"></span>
			<span class="kt-subheader__desc"></span>
		</div>
		<div class="kt-subheader__toolbar">
			<div class="kt-subheader__wrapper"></div>
		</div>
	</div>

	<!-- end:: Content Head -->

	<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid px-3" id="kt_content">
		
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="row">
				<div class="col-md-5 calendar_content <?php echo ($calendar_hide) ? 'hidden' : '' ?>">
					<div class="kt-portlet p-3" id="kt_portlet">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<span class="kt-portlet__head-icon">
									<i class="flaticon-calendar-2"></i>
								</span>
								<h3 class="kt-portlet__head-title">
									Calendar
								</h3>
							</div>
							<div class="kt-portlet__head-toolbar">
								<!-- <a href="#" class="btn btn-brand btn-elevate">
								<i class="la la-plus"></i>
								Add Event
							</a> -->
							</div>
						</div>
						<div class="kt-portlet__body">
							<div id="kt_calendar"></div>
						</div>
					</div>
				</div>
				<div class="activity_content <?php echo ($calendar_hide) ? 'col-md-12' : 'col-md-7' ?>">
					<div class="kt-portlet p-3 mb-0" id="kt_portlet">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<span class="kt-portlet__head-icon hide_calendar icon_toggle_calendar <?php echo ($calendar_hide) ? 'hidden' : '' ?>" data-toggle="kt-tooltip" title="Hide Calendar" data-val="true">
									<i class="flaticon-delete-2"></i>
								</span>

								<span class="kt-portlet__head-icon icon_toggle_calendar show_calendar <?php echo (!$calendar_hide) ? 'hidden' : '' ?>" data-toggle="kt-tooltip" title="Show Calendar" data-val="">
									<i class="flaticon-calendar-2"></i>
								</span>

								<h3 class="kt-portlet__head-title">
									Activities (<span id="s_total_"><?php echo $total_a ?></span>)
								</h3>
							</div>
							<div class="kt-portlet__head-toolbar">
								<a href="#" class="btn btn-brand btn-sm addActivity">
									<i class="la la-plus"></i>
								</a>

								<div class="flex_ ml-3">
									<span class="kt-switch kt-switch--icon">
										<label class="m-0">
											<input type="checkbox" name="" class="add_filter" <?php echo ($show_filters == "") ? "checked" : "" ?>>
											<span></span>
										</label>
									</span>
									<label class="col-form-label">Filters</label>
								</div>

							</div>
						</div>
						<div class="filters <?php echo $show_filters ?>">
							<form id="form_filters_a">
								<div class="row mt-2">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Subdivisions</label>
											<select class="form-control" name="filter_sub">
												<option value="#">All</option>
												<?php foreach ($subs as $item) { ?>
													<option value="<?php echo $item['id'] ?>" <?php echo ($filter_sub == $item["id"]) ? "selected" : "" ?>>
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Lot</label>

											<!-- <input type="text" class="form-control" placeholder="Lot" name="filter_lot" value="<?php echo $filter_lot ?>"> -->

											<input list="lotes_list" class="custom-select searchLot" name="filter_lot" value="<?php echo $filter_lot ?>" id="lotFilter_">
											<datalist id="lotes_list"></datalist>
										</div>

									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Type</label>
											<select class="form-control" name="filter_type">
												<option value="#">All</option>
												<?php foreach ($types as $item) { ?>
													<option value="<?php echo $item['id'] ?>" <?php echo ($filter_type == $item["id"]) ? "selected" : "" ?>>
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="">Installers</label>
											<select class="form-control" name="filter_worker">
												<option value="#">All</option>
												<?php foreach ($workers as $item) { ?>
													<option value="<?php echo $item['id'] ?>" <?php echo ($filter_worker == $item["id"]) ? "selected" : "" ?>>
														<?php echo $item["nombre"] ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="">
												Date range
												<?php if ($filter_daterange) { ?>
													<span class="badge badge-danger">Filter Active</span>

													<button class="btn btn-secondary btn-sm cancel_range_outside">Cancel</button>
												<?php } ?>

											</label>
											<input type='text' class="form-control" id="kt_daterangepicker_1" placeholder="Select dates" type="text" value="<?php echo $filter_daterange ?>" name="filter_daterange" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col">
												<div class="form-group">
													<label for="">Status</label>
													<select class="form-control" name="filter_status">
														<option value="#">All</option>
														<option value="1" <?php echo ($filter_status == "1") ? "selected" : "" ?>>Ordered</option>

														<option value="-1" <?php echo ($filter_status == "-1") ? "selected" : "" ?>>Not Ordered</option>

														<option value="2" <?php echo ($filter_status == "2") ? "selected" : "" ?>>Marked</option>

														<option value="-2" <?php echo ($filter_status == "-2") ? "selected" : "" ?>>Not Marked</option>
													</select>
												</div>
											</div>
											<div class="col">
												<label class="col-form-label">Billiable</label>
												<div class="form-group">

													<span class="kt-switch kt-switch--icon">
														<label class="m-0">
															<input type="checkbox" name="filter_billi" <?php echo ($filter_billi) ? "checked" : "" ?> id="fil_billiable">
															<span></span>
														</label>
													</span>

												</div>
											</div>
										</div>

									</div>

								</div>
							</form>


							<div>
								<button class="btn btn-primary  btn-sm applyAllFilters">Apply Filters</button>
								<button class="btn btn-danger  btn-sm removeAllFilters">Remove Filters</button>
								<a href="#" class="btn btn-label-brand btn-bold btn-sm dropdown-toggle" data-toggle="dropdown">
									Export
								</a>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right">
									<ul class="kt-nav">
										<li class="kt-nav__section kt-nav__section--first">
											<span class="kt-nav__section-text">Choose an action:</span>
										</li>
										<li class="kt-nav__item">
											<a href="exportPdf" download class="kt-nav__link" id="ex_pdf">
												<i class="kt-nav__link-icon flaticon2-graph-1"></i>
												<span class="kt-nav__link-text">PDF Workers</span>
											</a>
										</li>
										<li class="kt-nav__item">
											<a href="exportPdfAdmin" download class="kt-nav__link" id="ex_pdf">
												<i class="kt-nav__link-icon flaticon2-graph-1"></i>
												<span class="kt-nav__link-text">PDF Administration</span>
											</a>
										</li>
										<li class="kt-nav__item">
											<a href="exportExcel" download class="kt-nav__link">
												<i class="kt-nav__link-icon flaticon2-layers-1"></i>
												<span class="kt-nav__link-text">EXCEL</span>
											</a>
										</li>
									</ul>
								</div>

							</div>
						</div>
						<h3 class="specific_date my-3">
							<?php
							if (isset($_SESSION["specific_date"])) {
								if ($_SESSION["specific_date"] != "" && $_SESSION["specific_date"] != "null" && $_SESSION["specific_date"] != "undefined" && $_SESSION["specific_date"] != false) {
									$s_date = $_SESSION["specific_date"];
									$f_ = date("m", strtotime($s_date)) . "-" . date("d", strtotime($s_date)) . "-" . date("Y", strtotime($s_date));

									echo "Specific date: " . $f_;
									echo " <span class='removeSpecificDate'> <i class='flaticon2-trash'></i> </span>";
									echo " <input type='hidden' id='specific_d_border' value='" . $_SESSION["specific_date"] . "' />";
								}
							}

							?>
						</h3>

						<div class="kt-portlet__body fc fc-unthemed fc-ltr p-0 content_act_list" style="overflow: auto;">
							<div class="p-5 flex_ spinner_e hidden">
								<div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--dark"></div>
							</div>
							<div class="text-center my-2 loadMoreTop hidden">
								<span class="loadMore">Load More</span>
							</div>
							<div class="fc-view-container container_list_act" style="">
								<div class="fc-view fc-listWeek-view fc-list-view fc-widget-content" style="">

									<table class="fc-list-table ">
										<tbody id="list_activities">


										</tbody>
									</table>

								</div>
							</div>
							<div class="text-center my-2 loadMoreBottom hidden">
								<span class="loadMore">Load More</span>
							</div>
							<div class="p-5 flex_ spinner_i hidden">
								<div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--dark"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

	</div>