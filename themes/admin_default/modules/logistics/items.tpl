<!-- BEGIN: main -->
<!-- BEGIN: view -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
<form action="{NV_BASE_ADMINURL}index.php" method="get">
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
					<span class="input-group-addon w100">{LANG.username}</span>
					<select class="form-control userid_s" name="userid">
						<option value="0" {selected}>{LANG.selected_user}</option>
						<!-- BEGIN: user -->
						<option value="{user.userid}" {selected}>{user.username}</option>
						<!-- END: user -->
					</select>
	</div></div>
	
	<div class="form-group">
<div class="input-group">
					<span class="input-group-addon w100">{LANG.selected_store}</span>
					<select class="form-control" name="id_store">
					<option value="0">
						-- {LANG.selected_store} --
					</option>
					<!-- BEGIN: store -->			
					<option {selected_store} value="{store.id}">
						{store.title}
					</option>
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

<div class="form-group">	
<div class="input-group ">
<center class="xuatthongtin_excel ">

Chức năng:
<input type="radio" value="1" name="export_word"> {LANG.export_excel}
<input type="radio" value="2" name="export_word"> {LANG.print_all}

</center>	
</div></div>

<div class="form-group">	<div class="input-group">
<center><input class=" btn btn-success" type="submit" value="Tìm kiếm vận đơn"></center>	
</div></div>
</form>	

<script type="text/javascript">


//<![CDATA[
	$("#ngaytu,#ngayden").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});

</script>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.weight}</th>
					
				<!--	<th>{LANG.send_phone}</th>
					<th>{LANG.receive_name}</th>
					<th>{LANG.receive_phone}</th>
					<th>{LANG.document_name}</th> -->
					
					<th style=" width: 230px;">{LANG.bill}</th>
					<th>{LANG.info_send_name}</th>
					<th>{LANG.info_receive_name}</th>
					<th>{LANG.info_money}</th>
					
					<th>{LANG.add_date} - {LANG.note}</th>
					<th>{LANG.trang_thai}</th>
					<th class="w150 text-center">{LANG.active}</th>
				
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
					
				<!--	<td> {VIEW.send_phone} </td>
					<td> {VIEW.receive_name} </td>
					<td> {VIEW.receive_phone} </td>
					<td> {VIEW.document_name} </td> -->
					<td class="bill_vandon">
					<a href="{VIEW.link_schedule_bill}"style="color:#000;">Vận đơn: {VIEW.bill}</a><br/>
					<a style="color:white; padding: 2px;" href="{VIEW.link_schedule_bill}" class="btn btn-warning">Trạng thái</a>
					
					<a target="_blank" style="color:white; padding: 2px;" class="btn btn-primary"href="{VIEW.link}">Chi tiết</a>
					<a target="_blank" style="color:white; padding: 2px;" class="btn btn-success"href="{VIEW.url_print}">In đơn</a>
					</div>
					
					</td>
					<td> {VIEW.send_name} <br/> {VIEW.send_phone} 
					
					<br/> 
					
					
					
					</td>
					<td> {VIEW.receive_name} <br/> {VIEW.receive_phone} <br/> {VIEW.address_sent}  </td>
					<td> 
					<b>{VIEW.id_service}</b> <br/>
					Trọng lượng:{VIEW.weight_document}g - 
					Thu hộ: {VIEW.value_goods}đ
					- Cước: {VIEW.total_money}đ
					</td>
				
					<td> {VIEW.add_date} <br/> {VIEW.other_requirements} </td>
					<td> 
						<p style="font-weight:700; color:red">{VIEW.trangthai_moi}</p>
						<div>({VIEW.ngay_trangthai_moi})</div>
					</td>
					<td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /> Kích hoạt
					<br/>
						<!-- BEGIN: edit2 -->
					<i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
					<!-- END: edit2 -->
					<span {edit_bill}>
					<i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
					</span>
					
					</td>
					
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">

	$('form select[name=userid]').change(function(){
		var userid = $(this).val();
		var id_bill = $('form input[name=id]').val();
		
		if(userid > 0)
		{
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&userid_ajax=' + userid, function(res) {
					$('form select[name=id_store]').html(res);
				});
			
		}
	
	});

	

	$('.userid_s').select2();
	
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