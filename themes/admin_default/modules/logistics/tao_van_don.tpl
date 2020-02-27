<!-- BEGIN: main -->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="{NV_BASE_SITEURL}assets/js/select2/select2.min.css">
	<script type="text/javascript" src="{NV_BASE_SITEURL}assets/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<!-- BEGIN: themtc -->
<div class="tao_vandon">Đã thêm vận đơn thành công !</div>
<!-- END: themtc -->
<!-- BEGIN: them -->
<div class="tao_vandon">{LANG.create}</div>
<!-- END: them -->
<!-- BEGIN: capnhat -->
<div class="tao_vandon">Cập nhật vận đơn</div>
<!-- END: capnhat -->
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<!-- BEGIN: searchne -->
	<input type="hidden" name="ma_bill_s" value="{ma_bill}" />
	<input type="hidden" name="awbnccs_s" value="{awbnccs}" />
	<input type="hidden" name="tenncc_s" value="{tenncc}" />
	<input type="hidden" name="dia_chi_goi_s" value="{dia_chi_goi}" />
	<input type="hidden" name="ngay_nhan_s" value="{ngay_nhan}" />
	<input type="hidden" name="dia_chi_nhan_s" value="{dia_chi_nhan}" />
	<input type="hidden" name="ngay_gioi_s" value="{ngay_gioi}" />
	<input type="hidden" name="tai_khoan_s" value="{tai_khoan}" />
	<input type="hidden" name="page_s" value="{page}" />
	<!-- END: searchne -->
	<input type="hidden" name="id" value="{ROW.id}" />
	<input type="hidden" name="ma_bill_cu" value="{ROW.ma_bill}" />
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.ma_bill} <sup class="required">(*)</sup></strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="ma_bill" value="{ROW.ma_bill}"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.ngay_gioi} <sup class="required">(*)</sup></strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control w200_n" id="datepicker" type="text" name="ngay_gioi" value="{ROW.ngay_gioi}"/>
			<span class="doi_ngay_nhan w200_ngay"><i class="fa fa-calendar"></i>Chọn ngày</span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.awbncc}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="awbncc" value="{ROW.awbncc}" />
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.st_dtt}</strong> </label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" onkeyup="this.value=FormatNumber(this.value);" type="text" name="st_dtt" value="{ROW.st_dtt}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.tai_khoan}</strong></label>
		<div class="col-sm-19 col-md-20">
		<!--	<input class="form-control w200_n" id="tai_khoan" type="text" name="tai_khoan" value="{ROW.tai_khoan}"/> -->
			
			<select class="form-control tai_khoan" name="tai_khoan">
				<option value="">-- Chọn tài khoản --</option>
				<!-- BEGIN: kh -->
				<option {selected} value="{kh.userid}">{kh.last_name} {kh.first_name}</option>
				<!-- END: kh -->
			</select>
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_nuoc_den}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="id_nuoc_den" value="{ROW.id_nuoc_den}" />
		</div>
	</div>
		<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.nguoi_nhan}</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="nguoi_nhan" value="{ROW.nguoi_nhan}" />
		</div>
	</div>
	
	
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.tenncc}
</strong></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="tenncc" value="{ROW.tenncc}" />
		</div>
	</div>
	
	
	<!-- BEGIN: tong_tien -->
	
	<!-- END: tong_tien -->
	<!--<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.nguoi_goi}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" type="text" name="nguoi_goi" value="{ROW.nguoi_goi}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
		</div>
	</div>-->
	
	<!--<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.cuoc_phi}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<input class="form-control" readonly="readonly" type="text" name="cuoc_phi" value="{ROW.cuoc_phi}" required="required" />
		</div>
	</div>-->
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
</div></div>

<script type="text/javascript">
	
	$('.tai_khoan').change(function(){
		var tai_khoan = $(this).val();
		if(tai_khoan != '')
		{
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tao_van_don&tai_khoanajax=' + tai_khoan, function(res) {
					$('input[name=bgad]').val(res);
					
				});
		}
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

	$( "#datepicker" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.w200_ngay').click(function(){
					$( "#datepicker" ).focus();
				});
		$( "#datepicker1" ).datepicker({
						dateFormat : "dd/mm/yy",
						changeMonth : true,
						changeYear : true,
						showOtherMonths : true,
						showOn: 'focus',
					});
				$('.ngay_nhan').click(function(){
					$( "#datepicker1" ).focus();
				});

//]]>
</script>
<script type="text/javascript">
	
	 $('.tai_khoan').select2({
        
    });

	function FormatNumber(str) {

		var strTemp = GetNumber(str);
		if (strTemp.length <= 3)
			return strTemp;
		strResult = "";
		for (var i = 0; i < strTemp.length; i++)
			strTemp = strTemp.replace(",", "");
		var m = strTemp.lastIndexOf(".");
		if (m == -1) {
			for (var i = strTemp.length; i >= 0; i--) {
				if (strResult.length > 0 && (strTemp.length - i - 1) % 3 == 0)
					strResult = "," + strResult;
				strResult = strTemp.substring(i, i + 1) + strResult;
			}
		} else {
			var strphannguyen = strTemp.substring(0, strTemp.lastIndexOf("."));
			var strphanthapphan = strTemp.substring(strTemp.lastIndexOf("."), strTemp.length);
			var tam = 0;
			for (var i = strphannguyen.length; i >= 0; i--) {

				if (strResult.length > 0 && tam == 4) {
					strResult = "," + strResult;
					tam = 1;
				}

				strResult = strphannguyen.substring(i, i + 1) + strResult;
				tam = tam + 1;
			}
			strResult = strResult + strphanthapphan;
		}
		return strResult;
	}

	function GetNumber(str) {
		var count = 0;
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "," || temp == "." || (temp >= 0 && temp <= 9))) {
				alert("");
				return str.substring(0, i);
			}
			if (temp == " ")
				return str.substring(0, i);
			if (temp == ".") {
				if (count > 0)
					return str.substring(0, ipubl_date);
				count++;
			}
		}
		return str;
	}

	function IsNumberInt(str) {
		for (var i = 0; i < str.length; i++) {
			var temp = str.substring(i, i + 1);
			if (!(temp == "." || (temp >= 0 && temp <= 9))) {
				alert("");
				return str.substring(0, i);
			}
			if (temp == ",") {
				alert("");
				return str.substring(0, i);
			}
		}
		return str;
	}
</script>
<!-- END: main -->