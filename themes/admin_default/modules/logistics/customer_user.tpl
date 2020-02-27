<!-- BEGIN: main -->
<div class="row">
<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />

<div class="col-xs-24 col-sm-24 col-md-8">
<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.username}</span>
					<select class="form-control" name="userid">
						<option value="0"> -- {LANG.selected_user} -- </option>
						<!-- BEGIN: user -->
						<option value="{user.userid}" {selected}>{user.username}</option>
						<!-- END: user -->
					</select>
	</div></div>
</div>

<div class="col-xs-24 col-sm-24 col-md-8 text-left">


<div class="form-group">	
<div class="input-group">
Chức năng:
<input type="radio" value="1" name="export_word"> {LANG.export_excel}
<input class=" btn btn-success" type="submit" value="Tìm kiếm">
</div></div>
</div>
</form>
</div>
<div class="clear"></div>
<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100">{LANG.weight}</th>
					<th>{LANG.title}</th>
					<th>{LANG.phone}</th>
					<th>{LANG.address}</th>
					<th>{LANG.wards}</th>
					<th>{LANG.district}</th>
					<th>{LANG.city}</th>
				
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="8">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						{stt}
				</td>
					<td> {VIEW.userid} - {VIEW.title} </td>
					<td> {VIEW.phone} </td>
					<td> {VIEW.address} </td>
					<td> {VIEW.wards} </td>
					<td> {VIEW.district} </td>
					<td> {VIEW.city} </td>
					
					
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<script type="text/javascript">
//<![CDATA[
	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=store&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=store';
			return;
		});
		return;
	}


	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=store&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
				var r_split = res.split('_');
				if (r_split[0] != 'OK') {
					alert(nv_is_change_act_confirm[2]);
				}
			});
		}
		else{
			$('#change_status_' + id).prop('checked', new_status ? false : true );
		}
		return;
	}


//]]>
</script>

<script type="text/javascript">
	$(document).ready(function() {
	  $("select").select2();
	});
	$('.tinhthanh').change(function(){
		var tinhthanh = $(this).val();
		if(tinhthanh > 0)
		{
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=store&id_tinhthanh=' + tinhthanh, function(res) {
					$('.quanhuyen').html(res);
					
				});
		}
	});
	
	$('select.quanhuyen').change(function(){
		var id_quanhuyen = $(this).val();
		var chon = $(this).attr('chon');
		if(id_quanhuyen > 0)
		{
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=store&id_quanhuyen=' + id_quanhuyen, function(res) {
					$('.xaphuong').html(res);
					
				});
		}
	
	});

</script>
<!-- END: main -->