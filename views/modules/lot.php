<?php 
    if(!isset($_SESSION["usuario_validado"])){
        echo "<script> window.location.href = 'login' </script>";
        exit();
	}

	$sub_lot = isset($_SESSION["sub_lot"]) ? $_SESSION["sub_lot"] : false;
	$lot_ = isset($_SESSION["lot_"]) ? $_SESSION["lot_"] : false;

	if (!$sub_lot || !$lot_) {
		echo "<script> window.location.href = 'activity' </script>";
        exit();
	}

	
?>

<input type="hidden" id="sub_lot" value="<?php echo $sub_lot ?>">
<input type="hidden" id="lot_" value="<?php echo $lot_ ?>">

<!-- contenido -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
	<div class="kt-subheader__main">
		<h3 class="kt-subheader__title">Lot <?php echo $lot_; ?></h3>
		<span class="kt-subheader__separator kt-subheader__separator--v"></span>
		<span class="kt-subheader__desc"></span>
	</div>
	<div class="kt-subheader__toolbar">
		<div class="kt-subheader__wrapper">
			<a href="#" class="btn btn-brand btn-sm addActivity" data-lot="<?php echo $lot_ ?>" data-sub="<?php echo $sub_lot ?>">
				<i class="la la-plus"></i>
			</a>
		</div>
	</div>
</div>

<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid px-3" id="kt_content">


	<div class="kt-portlet__body kt-portlet__body--fit">
		<div class="kt-portlet p-3" id="kt_portlet">
		
			<div class="kt-portlet__body fc fc-unthemed fc-ltr p-0 content_act_list_lot">
				<div class="fc-view-container" style="">
					<div class="fc-view fc-listWeek-view fc-list-view fc-widget-content" style="">
						
						<table class="fc-list-table ">
							<tbody id="list_activities_lot">
								
							
							</tbody>
						</table>
					
					</div>
				</div>	
				<div class="p-5 flex_ spinner_i hidden">
					<div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--dark"></div>
				</div>					
			</div>
		</div>
		
	</div>

</div>


	
