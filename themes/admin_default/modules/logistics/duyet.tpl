

<!-- BEGIN: main -->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<!-- BEGIN: view -->
<div class="well form_search">
<form action="{NV_BASE_ADMINURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
<table class="tab1">
<tbody><tr><td align="left" style="font-weight: bold">
TRACKING &nbsp; <input name="ma_bill" type="text" value="{ma_bill}" style="width:120px" maxlength="20">&nbsp;
Nội dung hàng &nbsp; <input name="awbncc" type="text" value="{awbncc}" style="width:100px" maxlength="20">&nbsp;
Ghi chú &nbsp; <input name="tenncc" type="text" value="{tenncc}" style="width:100px" maxlength="20">
&nbsp;
{LANG.tai_khoan} &nbsp; <input name="tai_khoan" type="text" value="{tai_khoan}" style="width:100px" maxlength="20">
</br></br>
{LANG.ngay_nhan} &nbsp;
<input id="time" name="ngay_nhan" style="width: 100px;margin-right:0px" value="{ngay_nhan}" type="text">
<img class="image_tinme" src="{NV_BASE_SITEURL}themes/admin_default/images/calendar.jpg" style="cursor: pointer; vertical-align: middle;margin-right:14px" alt="" height="17">
{LANG.ngay_gioi} &nbsp;
<input id="ngaygoi" name="ngay_gioi" style="width: 100px;margin-right:0px" value="{ngay_goi}" type="text">
<img class="image_ngaygoi" src="{NV_BASE_SITEURL}themes/admin_default/images/calendar.jpg" style="cursor: pointer; vertical-align: middle;margin-right:40px" alt="" height="17">

Ngày mới nhất &nbsp;
<input id="image_ngay_moi_nhat" name="ngay_moi_nhat" style="width: 100px;margin-right:0px" value="{ngay_moinhat_tam}" type="text">
<img class="image_ngay_moi_nhat" src="{NV_BASE_SITEURL}themes/admin_default/images/calendar.jpg" style="cursor: pointer; vertical-align: middle;margin-right:14px" alt="" height="17">

<span class="nguoinhan_search">
	<select style="height:24px;margin-right:10px" name="nguoi_nhan">
		<option value="0">Tình trạng nhận</option>
		<option {select_danhan} value="1">Đã nhận</option>
		<option {select_chuanhan} value="2">Chưa nhận</option>
	</select>
</span>
<input class="btn btn-primary" type="submit" value="Tìm kiếm">
</td></tr> 
</tbody></table>
</form>
<!-- BEGIN: export_vd -->
<div class="xuat_file_excel text-right"><a href="{LINK_EXCEL}" class="btn btn-primary">Xuất vận đơn ra file excel</a></div>
<!-- END: export_vd -->
</div>
<!-- BEGIN: suatc -->
<div class="tao_vandon">Đã cập nhật vận đơn thành công !</div>
<!-- END: suatc -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive list_vandon">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="w50">STT</th>
					<th>{LANG.ma_bill}</th>
					<th>Nội dung hàng</th>
					<th>Ghi chú</th>
					<th>{LANG.ngay_nhan}</th>
					
					<th>{LANG.nguoi_nhan}</th>
					<!--<th>{LANG.gio_goi}</th>
					<th>{LANG.ngay_gioi}</th>
					<th>{LANG.ghi_chu}</th>-->
					<th>{LANG.tai_khoan}</th>					
					<th>{LANG.st_dtt}</th>
					<th>Ngày mới nhất</th>
					<th>Trạng thái mới nhất </th>
					<th class="w100 text-center">{LANG.status}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
		
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td>
						{STT}
				</td>
					<td> <a href="{VIEW.link}">{VIEW.ma_bill} </a></td>
					<td> {VIEW.awbncc} </td>
					<td> {VIEW.tenncc} </td>
					<td> {VIEW.ngay_nhan} </td>
					<td> {VIEW.nguoi_nhan} </td>
					<!--<td> {VIEW.gio_phat} </td>
					<td> {VIEW.ngay_gioi} </td>
					<td> {VIEW.ghi_chu} </td>-->
					<td> {VIEW.tai_khoan} </td>
					<td> {VIEW.st_dtt} </td>
					<td> {VIEW.ngay}</td>
					<td> {VIEW.trangthaimoinhat}</td>
						<td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /></td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>

			<!-- BEGIN: generate_page -->
			<div class="text-center">{NV_GENERATE_PAGE}</div>
			<!-- END: generate_page -->
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->

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


	function nv_change_status(id) {
		var new_status = $('#change_status_' + id).is(':checked') ? true : false;
		if (confirm(nv_is_change_act_confirm[0])) {
			var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'change_status=1&id='+id, function(res) {
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