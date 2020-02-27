<!-- BEGIN: main  -->

<link rel="StyleSheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/logistics.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/logistics.js"></script>
<div class="content_search_bill">
	<div class="col-xs-24 col-sm-24 col-md-8">
		<div class="panel panel-default tms_col_tracuoc">
		<div class="panel-heading"><h3>I. Thông tin </h3></div>
		<div class="panel-body">
					
					
				<div class="form-group"><div class="input-group">
				<span class="input-group-addon w100">{LANG.send_city}</span>
				<select name="send_city" class="form-control tinhthanh">
											<option value="0">-- {LANG.selected_city} --</option>
											<!-- BEGIN: tinh -->
											<option {l.selected} value="{l.provinceid}">-- {l.type} {l.title} --</option>
											<!-- END: tinh -->
			</select>
		</div></div>
		<div class="form-group"><div class="input-group">
				<span class="input-group-addon w100">{LANG.send_district}</span>
				<select name="send_district" class="form-control quanhuyen">
											<option value="0">-- {LANG.select_district} --</option>
											<!-- BEGIN: quan -->
											<option {l.selected} value="{l.districtid}">-- {l.type} {l.title} --</option>
											<!-- END: quan -->
										</select>
		</div></div>
		<div class="form-group"><div class="input-group">
				<span class="input-group-addon w100">{LANG.send_wards}</span>
				<select name="send_wards" class="form-control xaphuong">
											<option value="0">-- {LANG.select_ward} --</option>
											<!-- BEGIN: xa -->
											<option {l.selected} value="{l.wardid}">-- {l.type} {l.title} --</option>
											<!-- END: xa -->
										</select>
		</div></div>
		
			<div class="form-group"><div class="input-group">
					<span class="input-group-addon w100">{LANG.value_goods}</span>
					<input onkeyup="this.value=FormatNumber(this.value);" type="text" maxlength="255" class="form-control" value="{ROW.value_goods}" name="value_goods" >
			</div></div>
					
				<div class="form-group"><div class="input-group">
							<span class="input-group-addon w100">{LANG.weight_document}</span>
							<input type="text" maxlength="255" class="form-control required" value="{ROW.weight_document}" placeholder="{LANG.weight_document}(gram)"name="weight_document" >
				</div></div>	
				<h3 style="text-align:center">{LANG.quydoi_trongluong}</h3>
				<div class="form-group"><div class="input-group">
							<span class="input-group-addon w100">{LANG.weight_document}<br/> {LANG.quydoi} <br/>{LANG.kichthuot}</span>
							<input type="text" maxlength="255" class="form-control" value="{ROW.long_document}" name="long_document" placeholder="{LANG.long_document}(cm)">
							<input type="text" maxlength="255" class="form-control" value="{ROW.wide}" name="wide" placeholder="{LANG.wide}(cm)">
							<input type="text" maxlength="255" class="form-control" value="{ROW.height}" name="height" placeholder="{LANG.height}(cm)">
				</div></div>
					

				
		</div>
		</div>
		</div>
	<div class="col-xs-24 col-sm-24 col-md-8">
		<div class="panel panel-default tms_col_tracuoc">
		<div class="panel-heading"><h3> II. Thông tin cước</h3></div>
			<div class="panel-body">
			<div class="form-group">
					<div class="panel panel-default">
					<div class="panel-body">
					
					
					<!-- BEGIN: document -->
						<div class="col-xs-24 col-sm-24 col-md-12">
						<div style="margin-bottom: 2px;">
							<input {document.checked} type="radio" value="{document.id}" name="id_document"><span> {document.title} </span>
						</div>
						</div>
					<!-- END: document -->
					
					</div>	
					</div>
					</div>
					
					
				<div class="form-group">
				<div> {LANG.info_services}</div>
				<div class="panel panel-default">
				<div class="panel-body">
				<!-- BEGIN: service -->
				<div style="margin-bottom: 2px;">
				<input {service.checked} type="radio" value="{service.id}" name="id_service">
				<span> {service.title} </span>
				</div>
				<!-- END: service -->
				</div>	

				
				</div>
				</div>
			
			<div class="form-group phuthu_gia">
				<div> {LANG.service_surcharge}</div>
				<div class="panel panel-default">
				<div class="panel-body">
				<!-- BEGIN: surcharge -->
				<div style="margin-bottom: 2px;">
				<input {checked_surcharge} type="checkbox" value="{surcharge.id}" name="surcharge[]">
				<span> {surcharge.title} </span>
				</div>
				<!-- END: surcharge -->
				</div>	

				</div>
				</div>
				
				
				
				
		</div>			
		</div>
	</div>
	
	<div class="col-xs-24 col-sm-24 col-md-8 ">
	<div class="panel panel-default tms_col_tracuoc">
		<div class="panel-heading"><h3> III. {LANG.cuocphi}</h3></div>
			<div class="panel-body">
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">a.{LANG.service_charge}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.service_charge}" name="service_charge" readonly>
	</div></div>
	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">b.{LANG.pays}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.pays}" name="pays" readonly>
	</div></div>	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">c.{LANG.charge_for_collection}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.charge_for_collection}" name="charge_for_collection" readonly>
	</div></div>
	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">d.{LANG.total_charge} (a+b+c)</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.total_charge}" name="total_charge" readonly>
	</div></div>
	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">e.{LANG.vat} (d x 10%)</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.vat}" name="vat" readonly>
	</div></div>
	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.tong_tracuoc} (d+e)</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.total_money}" name="total_money" readonly>
	</div></div>
Giá cước trên đã bao gồm phụ phí xăng dầu. Áp dụng theo từng thời điểm của từng khu vực phát
	</div>
	</div>
	</div>

</div>

</div>

<script>
		  
	$(document).ready(function() {
	  $("select").select2();
	});
	
	$('.content_search_bill .tinhthanh').change(function(){
		var tinhthanh = $(this).val();
		if(tinhthanh > 0)
		{
				$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=add&id_tinhthanh=' + tinhthanh, function(res) {
					$('.quanhuyen').html(res);
					
				});
		}
	});
	
	$('.content_search_bill select.quanhuyen').change(function(){
		var id_quanhuyen = $(this).val();
		var chon = $(this).attr('chon');
		if(id_quanhuyen > 0)
		{
			$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=add&id_quanhuyen=' + id_quanhuyen, function(res) {
					$('.xaphuong').html(res);
					
				});
		}
	
	});

	$('.content_search_bill input[name=id_service],.content_search_bill input[name=value_goods],.content_search_bill select[name=send_city],.content_search_bill select[name=send_district],.content_search_bill select[name=send_wards],.content_search_bill input[name=id_document],.content_search_bill .phuthu_gia input,.content_search_bill input[name=weight_document],.content_search_bill input[name=long_document],.content_search_bill input[name=wide],.content_search_bill input[name=height]').change(function(){
		//alert('ok');
		
		tinhtien_block();
		
	});
	
	
	function tinhtien_block()
	{
		// LẤY THÔNG TIN TRỌNG LƯỢNG
		var long_document = $('.content_search_bill input[name=long_document]').val();
		var wide = $('.content_search_bill input[name=wide]').val();
		var height = $('.content_search_bill input[name=height]').val();
		
		var weight_document = 0;
			
		var weight_document_new = Math.round((parseFloat(long_document) * parseFloat(wide) * parseFloat(height))/6);
		if(weight_document_new > 0)
		{
			$('.content_search_bill input[name=weight_document]').val(weight_document_new);
			weight_document = weight_document_new;
		}
		else
		{
			weight_document = $('.content_search_bill input[name=weight_document]').val();
		}
		
		
		//alert(weight_document);			
		// KẾT THÚC LẤY THÔNG TIN TRỌNG LƯỢNG
		
		var id_service = $('.content_search_bill input[name=id_service]:checked').val();
		var id_document = $('.content_search_bill input[name=id_document]:checked').val();
		var send_city = $('.content_search_bill select[name=send_city] option:selected').val();
		var send_district = $('.content_search_bill select[name=send_district] option:selected').val();
		var send_wards = $('.content_search_bill select[name=send_wards] option:selected').val();
		var surcharge = [];
		$(".content_search_bill .phuthu_gia input:checked").each(function() {
            surcharge.push($(this).val());
        });
		
		
			
		if(id_service > 0 && id_document > 0 && send_city > 0 && send_district > 0 && weight_document > 0)
		{
			var tien_hang = $('.content_search_bill input[name=value_goods]').val();
			
						tien_hang = tien_hang.replace(/,/g , "");
						if(tien_hang == '')
							tien_hang = 0;
							
			$.ajax({
					type : 'POST',
					url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=add',
					data : { id_service_a : id_service, id_document_a : id_document, send_city_a : send_city, send_district_a : send_district, send_wards_a : send_wards , weight_document_a : weight_document, surcharge_a : surcharge , tien_hang : tien_hang },
					dataType: 'json',
					success : function(res){
					//var myObj = JSON.parse(res);
						console.log(res);
						//alert(res);
						$('.content_search_bill input[name=service_charge]').val(FormatNumber(res.gia_tam_thu));
						$('.content_search_bill input[name=pays]').val(FormatNumber(res.phu_thu));
						
						
						$('.content_search_bill input[name=money_collection]').val(FormatNumber(tien_hang));
						
						var phi_thu_ho = parseFloat(res.cuoc_phi_hang);
						
						$('.content_search_bill input[name=charge_for_collection]').val(FormatNumber(phi_thu_ho));
						
						var tong_cuoc = parseFloat(res.gia_tam_thu) + parseFloat(res.phu_thu) + parseFloat(phi_thu_ho);
						$('.content_search_bill input[name=total_charge]').val(FormatNumber(tong_cuoc));
						
						var vat = parseFloat((tong_cuoc * 10/100));
						$('.content_search_bill input[name=vat]').val(FormatNumber(vat));
						
						var tong_thu = parseFloat(tong_cuoc) + parseFloat(vat);
						$('.content_search_bill input[name=total_money]').val(FormatNumber(tong_thu));
						
						$('.content_search_bill input[name=total_receivable]').val(parseFloat(tien_hang));
					}
				
				});
		}
		
	}

</script>
<!-- END: main -->