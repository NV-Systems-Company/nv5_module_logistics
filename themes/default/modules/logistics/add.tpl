<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/logistics/jquery.number.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-heading"><h3> {LANG.create_bill}</h3></div>
<div class="panel-body">
<form class="form_add_bill" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">

<input type="hidden" name="id" value="{ROW.id}" />

 <div class="row">
<div class="col-xs-24 col-sm-24 col-md-24">
<div class="col-xs-24 col-sm-24 col-md-8">
<div class="panel panel-default">
<div class="panel-body">

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
			<div class="input-group">
			<span class="input-group-addon w100">{LANG.received_date}</span>
			<input class="form-control" type="text" name="received_date" value="{ROW.received_date}" id="received_date" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="received_date-btn">
						<em class="fa fa-calendar fa-fix"> </em>
					</button> </span>
				</div>
		</div>
		
		<div class="form-group">
			<div class="input-group">
			<span class="input-group-addon w100">{LANG.received_time}</span>
				
				<select class="form-control" name="received_time">
					<option value="0">
						-- {LANG.selected_received_time} --
					</option>
					<!-- BEGIN: gio -->			
					<option {selected_khung} value="{idkhung}">
						{khung}
					</option>
					<!-- END: gio -->
				
				</select>
				</div>
		</div>
	
	
	</div>
	</div>
	
</div>

<div class="col-xs-24 col-sm-24 col-md-16">
<div class="panel panel-default">
<div class="panel-heading"><h3>I. {LANG.info_send_name}</h3></div>
<div class="panel-body">
	<div class="form-group">
		<div class="input-group">
					<span class="input-group-addon w100">{LANG.send_name}</span>
					<input type="text" maxlength="255" class="form-control required" value="{ROW.send_name}" name="send_name">
		</div>
	</div>
	
	<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.send_phone}</span>
					<input type="text" maxlength="255" class="form-control required" value="{ROW.send_phone}" name="send_phone">
		</div>
	</div>
	
	<div class="form-group">
	<div class="input-group">
					<span class="input-group-addon w100">{LANG.send_address}</span>
					<input type="text" maxlength="255" class="form-control required" value="{ROW.send_address}" name="send_address">
		</div>
	</div>
	
</div>
</div>			
</div>




</div>	

	
	
<div class="col-xs-24 col-sm-24 col-md-8">
<div class="panel panel-default">
<div class="panel-heading"><h3>II. {LANG.info_receive}</h3></div>
<div class="panel-body">

		<div class="form-group"><div class="input-group">
				<span class="input-group-addon w100">{LANG.receive_phone}</span>
				<input type="text" maxlength="255" class="form-control" value="{ROW.receive_phone}" name="receive_phone" placeholder="{LANG.please_send_phone}">
				<div class="customer_user"></div>
		</div>
		
		</div>
		<div class="form-group"><div class="input-group">
				<span class="input-group-addon w100">{LANG.receive_name}</span>
				<input type="text" maxlength="255" class="form-control required" value="{ROW.receive_name}" name="receive_name" placeholder="{LANG.please_send_name}">
		</div></div>
		
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
				<span class="input-group-addon w100">{LANG.send_address}</span>
				<input type="text" maxlength="255" class="form-control required" value="{ROW.receive_address}" name="receive_address" placeholder="{LANG.please_address}">
		</div></div>
		
		
		<div class="form-group">
			<div class="input-group">
			<span class="input-group-addon w100">{LANG.delivery_date}</span>
			<input class="form-control" type="text" name="delivery_date" value="{ROW.delivery_date}" id="delivery_date" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="delivery_date-btn">
						<em class="fa fa-calendar fa-fix"> </em>
					</button> </span>
				</div>
		</div>
		
		<div class="form-group">
			<div class="input-group">
			<span class="input-group-addon w100">{LANG.delivery_time}</span>
				<select class="form-control" name="delivery_time">
					<!-- BEGIN: gio1 -->			
					<option {selected_khung_giao} value="{idkhung}">
						{khung}
					</option>
					<!-- END: gio1 -->
				
				</select>
				</div>
		</div>
		
		
</div>
</div>			
</div>

<div class="col-xs-24 col-sm-24 col-md-8">
<div class="panel panel-default">
<div class="panel-heading"><h3>III. {LANG.info_document}</h3></div>
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
			
		<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.document_name}</span>
					<input type="text" maxlength="255" class="form-control required" value="{ROW.document_name}" name="document_name">
		</div></div>	
		
		<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.amount}</span>
					<input type="text" maxlength="255" class="form-control" value="{ROW.amount}" name="amount">
		</div></div>	
		<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.value_goods}</span>
					<input onkeyup="this.value=FormatNumber(this.value);" type="text" maxlength="255" class="form-control" value="{ROW.value_goods}" name="value_goods" >
		</div></div>
		<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.weight_document}</span>
					<input type="text" maxlength="255" class="form-control" value="{ROW.weight_document}" placeholder="{LANG.weight_document}(gram)"name="weight_document" >
		</div></div>	
		<h3 style="text-align:center">{LANG.quydoi_trongluong}</h3>
		<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.weight_document}<br/> {LANG.quydoi} <br/>{LANG.kichthuot}</span>
					<input type="text" maxlength="255" class="form-control" value="{ROW.long_document}" name="long_document" placeholder="{LANG.long_document}(cm)">
					<input type="text" maxlength="255" class="form-control" value="{ROW.wide}" name="wide" placeholder="{LANG.wide}(cm)">
					<input type="text" maxlength="255" class="form-control" value="{ROW.height}" name="height" placeholder="{LANG.height}(cm)">
		</div></div>
			

		
</div>
</div>
</div>

<div class="col-xs-24 col-sm-24 col-md-8">
<div class="panel panel-default">
<div class="panel-heading"><h3> IV. {LANG.info_services}</h3></div>
	<div class="panel-body">
	
		<div class="form-group">
		<label> {LANG.info_services}</label>
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
		<label> {LANG.service_surcharge}</label>
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
		
		
		<div class="form-group">
		<label> {LANG.other_requirements}</label>
		<div class="panel panel-default">
		<div class="panel-body">
		<textarea cols="8" name="other_requirements" class="form-control" maxlength="1000" onkeypress="nv_validErrorHidden(this);">{ROW.other_requirements}</textarea>
		</div>	

		</div>
        </div>
		
</div>			
</div>
</div>

 	<div class="clear"></div>

<div class="col-xs-24 col-sm-24 col-md-24">
<div class="panel panel-default">
<div class="panel-heading"><h3>V. {LANG.price_thuho}</h3></div>
<div class="panel-body">

<div class="col-xs-24 col-sm-24 col-md-8">
	
		<div class="form-group">
		<label> {LANG.pay}</label>
		<div class="panel panel-default">
			<div class="panel-body">
				<!-- BEGIN: pay -->
					<div style="margin-bottom: 2px;">
					<input type="radio" {checkbox_pay} value="{pay_id}" name="pay">
					<span> {pay_title} </span>
					</div>
				<!-- END: pay -->
				
			</div>
		</div>
		</div>
	
	

	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w100">{LANG.money_collection}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.money_collection}" readonly name="money_collection">
	</div></div>
	
	

</div>



<div class="col-xs-24 col-sm-24 col-md-8">
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
					<span class="input-group-addon w150">{LANG.total_money} (d+e)</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.total_money}" name="total_money" readonly>
	</div></div>
Giá cước trên đã bao gồm phụ phí xăng dầu. Áp dụng theo từng thời điểm của từng khu vực phát
</div>

<div class="col-xs-24 col-sm-24 col-md-8">	
	<div class="form-group"><div class="input-group">
					<span class="input-group-addon w150">{LANG.total_receivable}</span>
					<input type="text" maxlength="255" class="form-control disabled" value="{ROW.total_receivable}" readonly name="total_receivable">
	</div></div>

	
</div>

</div>

  </div>
 </div> 
</div>

<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>

</form>

</div>
<div class="clear"></div>

<script type="text/javascript">

	$('.form_add_bill input[name=receive_phone]').keypress(function(){
		var receive_phone = $('.form_add_bill input[name=receive_phone]').val();
		//alert(receive_phone.length);
		if(receive_phone != '')
		{
			$.ajax({
				type : 'POST',
				url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add',
				data : { customer_phone : receive_phone},
				success : function(res){
					$('.customer_user').show();
					$( ".customer_user" ).html(res);
				},
				error: err
			
			});
		}
	});
	
	/*
	$('.form_add_bill input[name=value_goods]').change(function(){
		
		var tien_hang = $('.form_add_bill input[name=value_goods]').val();
		if(tien_hang == '')
			tien_hang = 0;			
		$('.form_add_bill input[name=money_collection]').val(tien_hang);
		//var tien_hang = parseFloat(tien_hang.replaceAll('.', ''));

		//alert(tien_hang);	
		var phi_thu_ho = parseFloat((tien_hang * 2/100)); 
		if(phi_thu_ho < 15000 && phi_thu_ho > 0)
			phi_thu_ho = 15000;			
		$('.form_add_bill input[name=charge_for_collection]').val(phi_thu_ho);
		
	});
*/

	$('.form_add_bill input[name=pay],.form_add_bill input[name=id_service],.form_add_bill select[name=send_city],.form_add_bill select[name=send_district],.form_add_bill select[name=send_wards],.form_add_bill input[name=id_document],.form_add_bill input[name=value_goods],.form_add_bill .phuthu_gia input,.form_add_bill input[name=weight_document],.form_add_bill input[name=long_document],.form_add_bill input[name=wide],.form_add_bill input[name=height]').change(function(){
		//alert('ok');
		
		tinhtien();
		
	});
	
	
	function tinhtien()
	{
		// LẤY THÔNG TIN TRỌNG LƯỢNG
		var long_document = $('.form_add_bill input[name=long_document]').val();
		var wide = $('.form_add_bill input[name=wide]').val();
		var height = $('.form_add_bill input[name=height]').val();
		
		var weight_document = 0;
			
		var weight_document_new = Math.round((parseFloat(long_document) * parseFloat(wide) * parseFloat(height))/6);
		if(weight_document_new > 0)
		{
			$('.form_add_bill input[name=weight_document]').val(weight_document_new);
			weight_document = weight_document_new;
		}
		else
		{
			weight_document = $('.form_add_bill input[name=weight_document]').val();
		}
			 
		// KẾT THÚC LẤY THÔNG TIN TRỌNG LƯỢNG
		
		var id_service = $('.form_add_bill input[name=id_service]:checked').val();
		var id_document = $('.form_add_bill input[name=id_document]:checked').val();
		var send_city = $('.form_add_bill select[name=send_city] option:selected').val();
		var send_district = $('.form_add_bill select[name=send_district] option:selected').val();
		var send_wards = $('.form_add_bill select[name=send_wards] option:selected').val();
		var surcharge = [];
		$(".form_add_bill .phuthu_gia input:checked").each(function() {
            surcharge.push($(this).val());
        });
		
		
		//alert(weight_document);		
		if(id_service > 0 && id_document > 0 && send_city > 0 && send_district > 0 && weight_document > 0)
		{	
			var tien_hang = $('.form_add_bill input[name=value_goods]').val();
						tien_hang = tien_hang.replace(/,/g , "");
						if(tien_hang == '')
							tien_hang = 0;
			$.ajax({
					type : 'POST',
					url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add',
					data : { id_service_a : id_service, id_document_a : id_document, send_city_a : send_city, send_district_a : send_district, send_wards_a : send_wards , weight_document_a : weight_document, surcharge_a : surcharge, tien_hang : tien_hang },
					dataType: 'json',
					success : function(res){
					//var myObj = JSON.parse(res);
						console.log(res);
						//alert(res);
						$('.form_add_bill input[name=service_charge]').val(FormatNumber1(res.gia_tam_thu));
						$('.form_add_bill input[name=pays]').val(FormatNumber1(res.phu_thu));
			
						$('.form_add_bill input[name=money_collection]').val(FormatNumber1(tien_hang));
						
						var phi_thu_ho = parseFloat(res.cuoc_phi_hang);
						
						$('.form_add_bill input[name=charge_for_collection]').val(FormatNumber1(phi_thu_ho));
						
						var tong_cuoc = parseFloat(res.gia_tam_thu) + parseFloat(res.phu_thu) + parseFloat(phi_thu_ho);
						$('.form_add_bill input[name=total_charge]').val(FormatNumber1(tong_cuoc));
						
						var vat = parseFloat((tong_cuoc * 10/100));
						$('.form_add_bill input[name=vat]').val(FormatNumber1(vat));
						
						var tong_thu = parseFloat(tong_cuoc) + parseFloat(vat);
						$('.form_add_bill input[name=total_money]').val(FormatNumber1(tong_thu));
						
						// CHỌN HÌNH THỨC THANH TOÁN
						var thanhtoan = $('.form_add_bill input[name=pay]:checked').val();
						
						if(thanhtoan == 1)
						{
							$('.form_add_bill input[name=seller_payments]').val(FormatNumber1(tien_hang));
							$('.form_add_bill input[name=total_receivable]').val(FormatNumber1(tien_hang));
							
						}
						if(thanhtoan == 2)
						{
							$('.form_add_bill input[name=seller_payments]').val(FormatNumber1(tien_hang));
							$('.form_add_bill input[name=total_receivable]').val(FormatNumber1(tien_hang));
							
						}
						if(thanhtoan == 3)
						{
							$('.form_add_bill input[name=seller_payments]').val(FormatNumber1(tien_hang));
							$('.form_add_bill input[name=total_receivable]').val(FormatNumber1(parseFloat(tien_hang) + parseFloat(tong_thu)));
							
						}
						
					},
					error: err
				
				});
		}
		
	}
	

	$('.form_add_bill select[name=id_store]').change(function(){
		var id_store = $(this).val();
		$.ajax({
				type : 'POST',
				url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add',
				data : { id_store_ajax : id_store},
				dataType: 'json',
				success : function(res){
				//var myObj = JSON.parse(res);
					console.log(res);
					$('input[name=send_name]').val(res.name);
					$('input[name=send_phone]').val(res.phone);
					$('input[name=send_address]').val(res.dia_chi_day_du);
				},
				error: err
			
			});
	});
	
	function err(xhr, reason, ex)
      {
        $("body").text(reason);
      }
	 
	function FormatNumber1(myNumber)
	{
		var tam = Math.ceil(myNumber/1000);
		tam = tam * 1000;
		return FormatNumber(tam);
	}
	$(document).ready(function() {
	  $("select").select2();
	});
	$('.form_add_bill .tinhthanh').change(function(){
		var tinhthanh = $(this).val();
		if(tinhthanh > 0)
		{
				$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&id_tinhthanh=' + tinhthanh, function(res) {
					$('.quanhuyen').html(res);
					
				});
		}
	});
	
	$('.form_add_bill select.quanhuyen').change(function(){
		var id_quanhuyen = $(this).val();
		var chon = $(this).attr('chon');
		if(id_quanhuyen > 0)
		{
			$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&id_quanhuyen=' + id_quanhuyen, function(res) {
					$('.xaphuong').html(res);
					
				});
		}
	
	});

</script>
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
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
//]]>
</script>
 
<!-- END: main -->