<!-- BEGIN: main -->
	<ul>
		<!-- BEGIN: loop -->
			<li>
				<div class ="fill_cus" name="{cus.title}" phone="{cus.phone}" city="{cus.city}" district="{cus.district}" wards="{cus.wards}" address="{cus.address}" >{cus.phone} - {cus.title}</div>
			</li>
		<!-- END: loop -->
	</ul>
	
	<script>
		$('.fill_cus').click(function(){
			var phone = $(this).attr('phone');
			var name = $(this).attr('name');
			var city = $(this).attr('city');
			var district = $(this).attr('district');
			var wards = $(this).attr('wards');
			var address = $(this).attr('address');
			
			$('input[name=receive_phone]').val(phone);
			$('input[name=receive_name]').val(name);
			$('input[name=receive_address]').val(address);
			
			if(city > 0 && district > 0)
			{
					$.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add&city_cus=' + city + '&district_cus=' + district +'&wards_cus=' + wards, function(res) {
						var myObj = JSON.parse(res);
						$('.tinhthanh').html(myObj.city_cus);
						$('.quanhuyen').html(myObj.district_cus);
						$('.xaphuong').html(myObj.wards_cus);
						
					});
					
					tinhtien_phone(city, district, wards)
			}
			
			
			$('.customer_user').hide();
			
		});
		
		
		function tinhtien_phone(send_city, send_district, send_wards)
		{
			// LẤY THÔNG TIN TRỌNG LƯỢNG
			var long_document = $('.form_add_bill input[name=long_document]').val();
			var wide = $('.form_add_bill input[name=wide]').val();
			var height = $('.form_add_bill input[name=height]').val();
			
			var weight_document = 0;
				
			var weight_document_new = (parseFloat(long_document) * parseFloat(wide) * parseFloat(height))/3000;
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
			
			var id_service = $('input[name=id_service]:checked').val();
			var id_document = $('input[name=id_document]:checked').val();
			var surcharge = [];
			$(".phuthu_gia input:checked").each(function() {
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
		
		
		function FormatNumber1(myNumber)
		{
			var tam = Math.ceil(myNumber/1000);
			tam = tam * 1000;
			return FormatNumber(tam);
		}
	</script>
<!-- END: main -->