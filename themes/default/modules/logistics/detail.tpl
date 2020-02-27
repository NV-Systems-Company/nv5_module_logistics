<!-- BEGIN: main -->

<div class="rows">
<div class="col-sm-16 col-md-16">
<div class="tms_panel panel-default">
<div class="tms_boder">
<div class="tms25 tms_col tms_margin_top_50">
<img src="{NV_BASE_SITEURL}themes/default/images/logistics/logo_print.png">
</div>
<div class="tms_top_50  tms_col">
<div> 
<h4>CÔNG TY TNHH GIAO NHẬN VÀ CHUYỂN PHÁT SÀI GÒN</h4>
</div>
<div> 
Điện thoại: 028.3547.5118  &nbsp;&nbsp;<i class="fa fa-plane"></i> Hotline: 028.3845.3999 
</div>
<div> 
Email: cskh@salogex.vn      &nbsp;&nbsp;<i class="fa fa-plane"></i> Website: www.salogex.vn
</div>


<div class="tms_h1 "> 
VẬN ĐƠN : {row.bill}
</div>

</div>

<div class="tms25 tms_col tms_margin_top_50">
{row.barcode}<br/>
{row.bill}
</div> 

<div class=" tms_boder_top"></div>
<div class="tms50">
<div class="tms_heading">I.{LANG.info_send_name}</div>
<div class="panel-body">
<div class="tms_row">{row.send_name}</div>
<!-- BEGIN: login_ok1 -->
<div class="tms_row">{row.send_address}</div>		
<div class="tms_row">{LANG.dienthoai}: {row.send_phone}</div>	
<!-- END: login_ok1 -->	

<!-- BEGIN: login1 -->
<div class="tms_row"><a href="{login}">{LANG.login}</a></div>
<!-- END: login1 -->
	
</div>			
</div>

<div class="tms50 tms_boder_left"style="min-height:150px">
<div class="tms_heading">II.{LANG.info_receive}</div>
<div class="panel-body">
<div class="tms_row">{row.receive_name}</div>

<!-- BEGIN: login_ok2 -->
<div class="tms_row">{row.receive_address}</div>		
<div class="tms_row">{LANG.dienthoai}: {row.receive_phone}</div>	
<!-- END: login_ok2 -->

<!-- BEGIN: login2 -->
<div class="tms_row"><a href="{login}">{LANG.login}</a></div>
<!-- END: login2 -->

</div>
</div>

<div class=" tms_boder_top"></div>
<div class="tms50">
<div class="panel-body">
			
			<!-- BEGIN: document -->
			<div class="tms50">
				<div style="margin-bottom: 2px;">
				<input {document.checked} type="checkbox"><span> {document.title} </span>
				</div>
			</div>
			<!-- END: document -->
			
<div class="clear"></div>	 	
<div style="margin-top: 5px;">{LANG.document_name}: <span style="font-weight:700">{row.document_name}</span></div>			   
</div>
</div>		
			
<div class="tms50  tms_boder_left" style="height:80px;">
<div class="tms30 tms_col_cuoc" style="height:30px;"> {LANG.amount}</div>
<div class="tms30 tms_boder_left tms_col_cuoc"style="height:30px;"> {LANG.weight_document}</div>
<div class="tms40 tms_boder_left tms_col_cuoc "style="height:30px;"> {LANG.trongluong_quydoi}</div>

<div class=" tms_boder_top"></div>
<div class="tms30 tms_col_cuoc" style="height:50px;"> {row.amount} </div>
<div class="tms30 tms_boder_left tms_col_cuoc"style="height:50px;"> {row.weight_document}g</div>
<div class="tms40 tms_boder_left tms_col_cuoc" style="height:50px;"> 
<!-- BEGIN: quydoi -->
{row.long_document}x{row.wide}x{row.height}
<!-- END: quydoi -->
</div>

</div>


<div class=" tms_boder_top"></div>
<div class="tms50">
<div class="tms50">
<div class="tms_heading">{LANG.id_service}</div>
<div class="panel-body">
		<!-- BEGIN: service -->
			<div style="margin-bottom: 2px;">
				<input type="checkbox" {service.checked}>
				<span> {service.title} </span>
			</div>
		<!-- END: service -->
</div>
		
</div>			


<div class="tms50 tms_boder_left">
<div class="tms_heading">{LANG.pays}</div>
<div class="panel-body">

		<!-- BEGIN: surcharge -->
			<div style="margin-bottom: 2px;">
				<input type="checkbox" {surcharge.checked}>
				<span> {surcharge.title} </span>
			</div>
		<!-- END: surcharge -->
		
</div>
</div>
</div>

<div class="tms50  tms_boder_left"style="height:125px">
<div class="tms_heading">{LANG.thongtinchung}</div>
<div class="panel-body">
<div class="tms_row"> {LANG.ngaygui}: {row.add_date}</div>
<div class="tms_row"> {LANG.thuho}: {row.value_goods}đ</div>
<div class="tms_row"> {LANG.note}: {row.other_requirements}</div>
</div>
</div>


<div class="clear"></div>

<div>

</div>

	
			
</div>
</div>



	<div class="clear" style="margin-bottom:50px;"></div>

</div>


<div class="col-sm-8 col-md-8">
	<div class="clear"></div>
	<div class="tms_panel panel-default" >
<div class="tms_boder">
		<div class="tms_heading"><h3>{LANG.thongtinhanhtrinh}</h3></div>
		<div class="panel-body">
			<div class="timeline-list-item">
				<!-- BEGIN: lichtrinh -->
                        <ul>
								<!-- BEGIN: loop -->
                                    <li>
                                        <span class="label-time">
                                           {lichtrinh.add_date}
                                        </span>
                                        <span class="block-span">
											{lichtrinh.title}
				<!-- BEGIN: receiver -->- Người nhận: {lichtrinh.receiver} <!-- END: receiver -->
				<!-- BEGIN: employees -->- Nhân viên: {lichtrinh.employees} <!-- END: employees -->
				<!-- BEGIN: note -->- Ghi chú:{lichtrinh.note} <!-- END: note -->
                                        </span>
                                    </li>
                                <!-- END: loop -->
                        </ul>
				<!-- END: lichtrinh -->
                    </div>
					
					
		</div>			
		</div>
</div>
<div class="clear" style="margin-bottom:20px"></div>
[TRAVANDON]

[RIGHT]

	</div>

<!-- END: main -->