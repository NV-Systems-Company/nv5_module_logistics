<!-- BEGIN: main -->
<!-- BEGIN: ok -->
	<div style="color:red;margin-bottom: 10px;">Bạn đã cập nhật số lượng sản phẩm thành công!</div>
<!-- END: ok -->

<!-- BEGIN: error -->
	<div style="color:red;margin-bottom: 10px;">{error}</div>
<!-- END: error -->


<div class="rows"> 

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="panel panel-default">
<div class="panel-heading">{LANG.read_excel}</div>
<div class="panel-body">

 
 
	
	<div class="docfile">
		<form method="post" enctype="multipart/form-data" name="readexcel" id="readexcel">

					<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<em class="fa fa-folder-open fa-lg fa-horizon">
					</em>
				</span>
				<select class="form-control" name="userid">
					<option value="0">
						-- {LANG.selected_user} --
					</option>
					<!-- BEGIN: user -->			
					<option {selected_user} value="{user.userid}">
						{user.username}
					</option>
					<!-- END: user -->
				
				</select>
			</div>
	</div>
	
	<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<em class="fa fa-folder-open fa-lg fa-horizon">
					</em>
				</span>
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
			</div>
	</div>
	
					<div class="form-group">
							<label for="fname">File Excel</label>
							  <input type="file" name="excel" id="excel" required />
					</div>
					
					<div class="text-center form-group">
						<input type="submit" value="Up vận đơn" id="btsend" name="import" class="btn btn-primary" />
					</div>
		</form>
	

</div>

</div>
</div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="panel panel-default">
<div class="panel-heading">Hướng dẫn</div>

<div class="panel-body">

<span style="font-size:16px;"><span style="font-family:Times New Roman,Times,serif;"><strong>I. Thông tin hàng hóa:</strong></span></span><br><span style="font-size:14px;"><span style="font-family:Times New Roman,Times,serif;">1.Tài liệu, 2.Hàng hóa, 3.Hàng mẫu, 4.Loại khác</span></span><br><span style="font-size:16px;"><span style="font-family:Times New Roman,Times,serif;"><strong>II. Dịch vụ chuyển phát:</strong></span></span><br><span style="font-size:14px;"><span style="font-family:Times New Roman,Times,serif;">1.Chuyển phát nhanh, 2.Hỏa tốc - Hẹn giờ, 3.Giao hàng thu tiền, 4.Giao hàng hóa tiết kiệm</span></span><br><span style="font-size:16px;"><span style="font-family:Times New Roman,Times,serif;"><strong>III. Dịch vụ gia tăng:</strong></span></span><br><span style="font-size:14px;"><span style="font-family:Times New Roman,Times,serif;">1.Báo phát, 2.Phát tận tay, 3.Dịch vụ hóa đơn, 4.Dịch vụ thư ký</span></span>

<br>
<strong><a href="{FILE_EXCEL}" style="color:red; text-align:center"><i class="fa fa-file-excel-o" aria-hidden="true"></i> TẢI FILE EXCEL MẪU</a></strong>


</div>

</div>
</div>
</div>


</div> 
<div class="clearfix"></div>


<script>
	
	$('select').select2();
	
	$('select[name=userid]').change(function(){
		var userid = $(this).val();
		
		if(userid > 0)
		{
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&userid_ajax=' + userid, function(res) {
					$('select[name=id_store]').html(res);
				});
			
		}
	
	});
	
</script>
	


<!-- END: main -->