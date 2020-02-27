<!-- BEGIN: main -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="thongtin_bill_lt">
<span class="ma_bill">{LANG.ma_bill}: <span class="mau_bill">{ma_bill}</span><span class="mar10">| </span>   </span>
<span class="ma_bill">Nội dung hàng: <span class="mau_bill">{awbncc}</span><span class="mar10">| </span>   </span>
<span class="ma_bill">Khách hàng: <span class="mau_bill">{tai_khoan}</span>  <span class="mar10" > |  </span>  </span>
<span class="ma_bill">Số tiền thu hộ: <span class="mau_bill">{st_dtt}</span></span>
</div>
<!-- BEGIN: view -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<div class="table-responsive">
		<table class="lichtrinh_table table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>STT</th>
					<th>{LANG.ngay}</th>
					<th>{LANG.gio}</th>
					<th>Người nhận</th>
					<th>Nhân viên phụ trách</th>
					<th>{LANG.chi_tiet}</th>
					<th>{LANG.status}</th>
					<th class="w150">&nbsp;</th>
				</tr>
			</thead>
			<!-- BEGIN: generate_page -->
			<tfoot>
				<tr>
					<td class="text-center" colspan="6">{NV_GENERATE_PAGE}</td>
				</tr>
			</tfoot>
			<!-- END: generate_page -->
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">
						{stt}
					</td>
					<td> {VIEW.ngay_1} </td>
					<td> {VIEW.gio_1} </td>
					<td> {VIEW.nguoi_nhan} </td>
					<td> {VIEW.nv_phu_trach} </td>
					<td> {VIEW.ghi_chu} </td>
					<td> {VIEW.status} </td>
					<td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i> <a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: view -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: link -->
<div class="text-center" style="margin-bottom:20px;"><a href="{LINK}" class="btn btn-primary">Quay về kết quả tìm kiếm</a></div>
<!-- END: link -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id" value="{ROW.id}" />
	<input class="form-control" type="hidden" name="id_van_don" value="{id_van_don}"/>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.ngay}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control w200_n" id="datepicker"  type="text" name="ngay" value="{ROW.ngay}"  oninput="setCustomValidity('')" required="required" />
			<span class="doi_ngay_nhan w200_ngay"><i class="fa fa-calendar"></i>Chọn ngày</span>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.nguoi_nhan_lt}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="nguoi_nhan" value="{ROW.nguoi_nhan}"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.nguoi_phu_trach_lt}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="nv_phu_trach" value="{ROW.nv_phu_trach}"/>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.status}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select class="form-control" name="status">
				<option value="">-- Chọn trạng thái --</option>
				<!-- BEGIN: trangthai -->
				<option {trangthai.selected} value="{trangthai.title}">{trangthai.title}</option>
				<!-- END: trangthai -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.chi_tiet}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="chi_tiet" value="{ROW.ghi_chu}"/>
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
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lich_trinh&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
			var r_split = res.split('_');
			if (r_split[0] != 'OK') {
				alert(nv_is_change_act_confirm[2]);
			}
			window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=lich_trinh';
			return;
		});
		return;
	}

			$( "#datepicker" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.doi_ngay_nhan').click(function(){
					$( "#datepicker" ).focus();
				});
//]]>
</script>
<!-- END: main -->