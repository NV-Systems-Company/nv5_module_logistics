<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.selected_user}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select class="form-control" name="userid">
					<option value="0">
						-- {LANG.selected_user} --
					</option>
					<!-- BEGIN: user -->			
					<option {selected} value="{user.userid}">
						{user.username}
					</option>
					<!-- END: user -->
				
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.title}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.name}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="name" value="{ROW.name}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.phone}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="phone" value="{ROW.phone}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.city}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select name="city" class="form-control tinhthanh">
											<option value="0">-- Chọn tỉnh thành --</option>
											<!-- BEGIN: tinh -->
											<option {l.selected} value="{l.provinceid}">-- {l.type} {l.title} --</option>
											<!-- END: tinh -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.district}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select name="district" class="form-control quanhuyen">
											<option value="0">-- Chọn quận huyện --</option>
											<!-- BEGIN: quan -->
											<option {l.selected} value="{l.districtid}">-- {l.type} {l.title} --</option>
											<!-- END: quan -->
										</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.wards}</strong></label>
		<div class="col-sm-19 col-md-20">
			<select name="wards" class="form-control xaphuong">
											<option value="0">-- Chọn quận huyện --</option>
											<!-- BEGIN: xa -->
											<option {l.selected} value="{l.wardid}">-- {l.type} {l.title} --</option>
											<!-- END: xa -->
										</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.address}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="address" value="{ROW.address}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.note}</strong></label>
		<div class="col-sm-19 col-md-20">
			<textarea class="form-control" style="height:100px;" cols="75" rows="5" name="note">{ROW.note}</textarea>
		</div>
	</div>
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
</div></div>

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