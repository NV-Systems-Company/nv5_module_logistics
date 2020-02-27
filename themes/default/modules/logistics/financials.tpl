<!-- BEGIN: main -->
<!-- BEGIN: view -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
<form action="{NV_BASE_SITEURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
<div class="row">

<div class="col-xs-24 col-sm-12 col-md-6">
<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.bill}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{bill}" name="bill"  placeholder="{LANG.bill}">
	</div></div>		
</div>

<div class="col-xs-24 col-sm-12 col-md-6">
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

<div class="col-xs-24 col-sm-12 col-md-6">

	<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.ngay_tu}</span>
					<input id="ngaytu" type="text" maxlength="255" class="form-control disabled" value="{ngay_tu}" name="ngay_tu"  placeholder="{LANG.ngay_tu}">
	</div>
	</div>
	
</div>

<div class="col-xs-24 col-sm-12 col-md-6">
<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.ngay_den}</span>
					<input id="ngayden" type="text" maxlength="255" class="form-control disabled" value="{ngay_den}" name="ngay_den"  placeholder="{LANG.ngay_den}">
	</div>
	</div>

</div>



<div class="clear"></div>
<div class="form-group text-right col-xs-24">	<div class="input-group">
<span style="margin-right:10px;"><input type="checkbox" value="1" name="export_excel"> <span style="position:relative;top:2px;">{LANG.export_excel} </span></span><input class=" btn btn-success" type="submit" value="Tìm kiếm vận đơn">
</div></div>
</div>

</form>	
<div class="clear"></div>
<script type="text/javascript">


//<![CDATA[
	$("#ngaytu,#ngayden").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

</script>

<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100" rowspan="2">{LANG.weight}</th>
					
			
					
					<th rowspan="2">{LANG.bill}</th>
					<th rowspan="2">{LANG.add_date_doisoat}</th>
					<th rowspan="2">{LANG.id_service}</th>
					<th rowspan="2">{LANG.value_goods}</th>
					<th colspan="6" class="text-center">
					{LANG.total_service_charge}
					<tr>
					
					<th>{LANG.money_collection}
					<th>{LANG.service_charge}</th>
					<th>{LANG.pays}</th>
					<th>{LANG.total_charge}</th>
					<th>{LANG.vat}</th>
					<th>{LANG.total_money}</th>
				</tr>
					
			     	</th>
					
					
					
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="11">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						{stt}
				</td>
					
			
					<td class="bill_vandon">{VIEW.userid_add} - {VIEW.bill} </td>
					<td> {VIEW.add_date}</td>
					<td> {VIEW.id_service}</td>
					
			
					<td> {VIEW.money_collection}</td>
					<td> {VIEW.charge_for_collection}</td>	
					<td> {VIEW.service_charge}</td>
					<td> {VIEW.pays}</td>
					<td> {VIEW.total_charge}</td>
					<td> {VIEW.vat}</td>
					<td> {VIEW.total_money}</td>
					
				</tr>
				<!-- END: loop -->
				
				<tr style="color: red;font-weight: 700;">
					<td class="text-center" colspan="4">
						{LANG.thongke_cuoc}
					</td>		
					<td> {phithuho}</td>
					<td> {tienthuho}</td>	
					<td> {cuocdichvu}</td>
					<td> {dichvugiatang}</td>
					<td> {cuoctamtinh}</td>
					<td> {vat}</td>
					<td> {tongcuocthu}</td>
					
				</tr>
									
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">

	
	
//<![CDATA[
	$("#delivery_date,#received_date").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add';
			return;
		});
		return;
	}


	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert(nv_is_change_act_confirm[2]);
				}
				location.reload();
			});
		}
		else{
			$('#change_status_' + id).prop('checked', new_status ? false : true );
		}
		return;
	}


//]]>
</script>
<!-- END: main -->