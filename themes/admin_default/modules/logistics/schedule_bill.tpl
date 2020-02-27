<!-- BEGIN: main -->
<!-- BEGIN: view -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w100">{LANG.weight}</th>
					<th>{LANG.add_date}</th>
					<th>{LANG.receiver}</th>
					<th>{LANG.employees}</th>
					<th>{LANG.status}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="7">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						{stt}
					</td>
					<td> {VIEW.add_date} </td>
					<td> {VIEW.receiver} </td>
					<td> {VIEW.employees} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<input type="hidden" name="id_bill" value="{ROW.id_bill}" />
	<!-- BEGIN: add_date -->
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.add_date}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<div class="col-sm-8 col-md-8">
				<div class="input-group">
				<input class="form-control" type="text" name="add_date" value="{ROW.add_date}" id="add_date" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="add_date-btn">
						<em class="fa fa-calendar fa-fix"> </em>
					</button> </span>
				</div>
			</div>
			<div class="col-sm-5 col-md-5">
				<select class="form-control" name="add_date_hour">
					<option value=""> -- {LANG.selected_gio} -- </option>
					<!-- BEGIN: gio -->
					<option value="{gio}" {gio.selected}>{gio}</option>
					<!-- END: gio -->
				</select>
			</div>
			<div class="col-sm-5 col-md-5">
				<select class="form-control" name="add_date_min">
					<option value=""> -- {LANG.selected_phut} -- </option>
					<!-- BEGIN: phut -->
					<option value="{phut}" {gio.selected}>{phut}</option>
					<!-- END: phut -->
				</select>
			</div>
			
		</div>
	</div>
	<!-- END: add_date -->
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.status}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select class="form-control" name="status">
				<option value=""> -- {LANG.selected_status} -- </option>
				<!-- BEGIN: select_status -->
				<option value="{OPTION.id}" {selected}>{OPTION.title}</option>
				<!-- END: select_status -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.receiver}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="receiver" value="{ROW.receiver}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.employees}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="employees" value="{ROW.employees}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.note}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="note" value="{ROW.note}" />
		</div>
	</div>
	
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
</div></div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
//<![CDATA[
	$("#add_date").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

	function nv_change_weight(id) {
		var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
		var new_vid = $('#id_weight_' + id).val();
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=schedule_bill&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=schedule_bill';
			return;
		});
		return;
	}


	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=schedule_bill&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
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
<!-- END: main -->