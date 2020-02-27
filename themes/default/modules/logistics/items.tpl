<!-- BEGIN: main -->

 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
<div class="rows">

<div class="panel panel-default">
<div class="panel-body">

<form action="{NV_BASE_SITEURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
<div class="col-xs-24 col-sm-24 col-md-8">
<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.bill}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{bill}" name="bill"  placeholder="{LANG.bill}">
	</div></div>
	
		<div class="form-group"><div class="input-group">
			<span class="input-group-addon w100">{LANG.trangthai}</span>
			<select class="form-control" name="status">
				<option value="0"> -- {LANG.selected_status} -- </option>
				<!-- BEGIN: select_status -->
				<option value="{OPTION.id}" {selected}>{OPTION.title}</option>
				<!-- END: select_status -->
			</select>
		</div></div>	
		
</div>

<div class="col-xs-24 col-sm-24 col-md-8">

	<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.phone}</span>
					<input type="text" maxlength="255" class="form-control disabled "value="{phone}" name="phone"  placeholder="{LANG.phone}">
	</div></div>
	
<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.store}</span>
					<select class="form-control" name="store">
					<option value="0"> -- {LANG.select_store}-- </option>
					
					<!-- BEGIN: store -->
					<option value="{store.id}" {selected_store}>{store.title}</option>
					<!-- END: store -->
				

				
			</select>
	</div></div>	
	
	

</div>

<div class="col-xs-24 col-sm-24 col-md-8">

	<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.ngay_tu}</span>
					<input id="ngaytu" type="text" maxlength="255" class="form-control disabled" value="{ngay_tu}" name="ngay_tu"  placeholder="{LANG.ngay_tu}">
	</div>
	</div>
	<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.ngay_den}</span>
					<input id="ngayden" type="text" maxlength="255" class="form-control disabled" value="{ngay_den}" name="ngay_den"  placeholder="{LANG.ngay_den}">
	</div>
	</div>

</div>

<div class="form-group">	<div class="input-group">
<center><input name="print_all" value="1" type="checkbox"><span style="position: relative;top: 3px;"> {LANG.print_bill}</span></center>	
</div></div>

<div class="form-group">	<div class="input-group">
<center><input class=" btn btn-success" type="submit" value="Tìm kiếm vận đơn"></center>	
</div></div>
</form>	

</div>
</div>

</div>
<div class="clear"></div>


<div class="clear"></div>
<div class="col-xs-24 col-sm-24 col-md-24">
    <ol class="breadcrumb-arrow">
		<li class="active"><a href="{add}">{LANG.add_bill}</a></li>
		
		<!-- BEGIN: status_bill -->
		<li><a href="{link_trangthai}">{OPTION.title} ({dem_trinhtrang})</a></li>
		<!-- END: status_bill -->
	</ol>

</div>


<div class="clear"></div>

<!-- BEGIN: view -->

<div class="rows">
<div class="panel panel-default">
<div class="panel-body">

<!-- BEGIN: loop -->
<div class="col-md-8 col-sm-12 col-xs-24">
<div class="panel panel-default">
	<div class="panel-body">
	<strong><span class="ma_bill_items">{LANG.bill}</span>: {VIEW.bill}</strong> <strong style="float:right">{VIEW.trangthai_moi}</strong><br/>
	<i class="fa fa-user"></i> <strong>{VIEW.receive_name}</strong>, {VIEW.receive_phone} <br/>
	<i class="fa fa-tasks"></i> <strong>{LANG.loaihang}:</strong> {VIEW.id_document}<br/>
	<i class="fa fa-money"></i> <strong>{VIEW.id_service}:</strong> {VIEW.total_money}đ<br/>
	<i class="fa fa-money"></i> <strong>{LANG.tiencanthu}:</strong> {VIEW.total_receivable}đ<br/>
	<i class="fa fa-calendar"></i> <strong>{LANG.ngaytaovandon}:</strong> {VIEW.add_date}<br/>
	<br/>
	<center>
	<!-- BEGIN: huy_bill -->
	<span onclick="nv_change_status({VIEW.id});" class="btn btn-warning">{LANG.huy_bill}</span>
	<!-- END: huy_bill -->
	<a class="btn btn-primary" href="{VIEW.link}">{LANG.detail}</a>
	<a target="_blank" href="{VIEW.url_print}" class="btn btn-success">{LANG.print_bill}</a>
	</center>
	</div>			
</div>
</div>
<!-- END: loop -->

</div>
</div>
</div>

			<!-- BEGIN: generate_page -->
			<div class="text-center">{NV_GENERATE_PAGE}</div>
			<!-- END: generate_page -->
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

<script type="text/javascript">
function nv_change_status(id) {
		if (confirm('{LANG.xacnhan_huy_bill}')) {
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=items&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert('{LANG.huy_bill_thatbai}');
				}
				location.reload();
			});
		}
		return;
	}
//]]>
</script>

<script type="text/javascript">
//<![CDATA[
	$("#ngaytu,#ngayden").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

</script>

<script type="text/javascript">
//<![CDATA[
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main';
			return;
		});
		return;
	}


	$( "#ngaygoi" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.image_ngaygoi').click(function(){
					$( "#ngaygoi" ).focus();
				});
		$( "#time" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.image_tinme').click(function(){
					$( "#time" ).focus();
				});
				
		$( "#image_ngay_moi_nhat" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.image_ngay_moi_nhat').click(function(){
					$( "#image_ngay_moi_nhat" ).focus();
				});

//]]>
</script>
<!-- END: main -->