<!-- BEGIN: main -->
<script type="text/javascript" data-show="after">
$(document).ready(function() {
    window.print();
});
</script>
<style type="text/css">
@page { margin: 0 }
body { margin: 0 }
.sheet {
  margin: 0;
  overflow: hidden;
  position: relative;
  box-sizing: border-box;
  page-break-after: always;
}

/** Paper sizes **/
body.A3           .sheet { width: 297mm; height: 419mm }
body.A3.landscape .sheet { width: 420mm; height: 296mm }
body.A4           .sheet { width: 210mm; height: 296mm }
body.A4.landscape .sheet { width: 297mm; height: 209mm }
body.A5           .sheet { width: 148mm; height: 209mm }
body.A5.landscape .sheet { width: 210mm; height: 147mm }

/** Padding area **/
.sheet.padding-10mm { padding: 4mm }
.sheet.padding-15mm { padding: 15mm }
.sheet.padding-20mm { padding: 20mm }
.sheet.padding-25mm { padding: 25mm }

/** For screen preview **/
@media screen {
  body { background: #e0e0e0 }
  .sheet {
    background: white;
    box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
    margin: 5mm;
  }
}

/** Fix for Chrome issue #273306 **/
@media print {
           body.A3.landscape { width: 420mm }
  body.A3, body.A4.landscape { width: 297mm }
  body.A4, body.A5.landscape { width: 210mm }
  body.A5                    { width: 148mm }
}

img.barcode {
    margin-top: 30px;
}


.tms_panel {
    margin-bottom: 18px;
    background-color: #ffffff;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.05);
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
}
.tms_col{padding:3px!important;text-align:center!important;}
.tms_col_cuoc{padding:3px!important;text-align:center}
.tms_text_10{font-size:10px; text-align:center}
.tms10{float:left; width:10%}
.tms15{float:left; width:15%}
.tms20{float:left; width:20%}
.tms25{float:left; width:25%}
.tms30{float:left; width:30%}
.tms40{float:left; width:40%;  text-align: left!important; }
.tms50{float:left; width:50%;  text-align: left!important; }
.tms_top_50{float:left; width:50%;  text-align: center!important; }
.tms_top_50 h4{font-size:11px; font-weight:600;}
.tms55{float:left; width:10%;  text-align: left!important; }
.tms_h1{ padding:3px;font-weight:600;font-size:22px!important; color:red!important;  }
.tms_heading{ padding:5px;font-weight:600; color:#000!important; background-color: #797a7b !important;    text-align: left!important; }
.tms_boder{float:left; width:100%;border: 1px solid #797a7b; }
.tms_boder_left{float:left; border-left: 1px solid #797a7b; }
.tms_boder_bottom{float:left; border-left: 1px solid #797a7b; }
.tms_boder_top{float:left; border-top: 1px solid #797a7b; width:100%; }
.tms_row{border-bottom: 1px dotted #797a7b!important; line-height:25px;  text-align: left!important; }



</style>


<body class="A5 landscape">
<!-- BEGIN: print -->
  <section class="sheet padding-10mm">
<div class="col-lg-24 col-md-24  col-xs-24">
<div class="tms">
<div class="rows">
<center>
<div class="tms_panel panel-default">
<div class="tms_boder">
<div class="tms25 tms_col">
<img src="{NV_BASE_SITEURL}themes/default/images/logo_print.png" style="margin-top:30px;">
</div>
<div class="tms_top_50  tms_col">
<div> 
<h4>CÔNG TY TNHH GIAO NHẬN VÀ CHUYỂN PHÁT NHANH SÀI GÒN </h4>
</div>
<div> 
Điện thoại:  028.3845.3999  &nbsp;&nbsp;<i class="fa fa-plane"></i> Hotline: 028.3547.5118
</div>
<div> 
Email: info@salogex.vn    &nbsp;&nbsp;<i class="fa fa-plane"></i> Website: www.salogex.vn
</div>

<div class="tms_h1"> 
VẬN ĐƠN : {row.bill}
</div>

</div>


<div class="content_print">

<div class="tms25  tms_col">
{row.barcode}
<br/>{row.bill}
</div> 

<div class=" tms_boder_top"></div>
<div class="tms50">
<div class="tms_heading">I.{LANG.info_send_name}</div>
<div class="panel-body">
<div class="tms_row">{row.send_name}</div>
<div class="tms_row">{row.send_address}</div>		
<div class="tms_row">{LANG.dienthoai}: {row.send_phone}</div>			
</div>			
</div>

<div class="tms50 tms_boder_left"  style="height:150px">
<div class="tms_heading">II.{LANG.info_receive}</div>
<div class="panel-body">
<div class="tms_row">{row.receive_name}</div>
<div class="tms_row">{row.receive_address}</div>		
<div class="tms_row">{LANG.dienthoai}: {row.receive_phone}</div>	
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
			
		
<div style="margin-top: 2px;"><input type="checkbox" value="1" name="hanghoa"> {LANG.document_name}: {row.document_name}</div>			   
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
<div class=" tms_boder_top"></div>

<div class="tms25 " style="height:50px">
<div class="tms_heading">Chữ ký người gửi</div>
</div>
<div class="tms25 tms_boder_left" style="height:50px">
<div class="tms_heading">Nhân viên chấp nhận</div>
</div>
<div class="tms25 tms_boder_left" style="height:50px">
<div class="tms_heading">Nhân viên phát</div>
</div>
<div class="tms25 tms_boder_left" style="height:50px">
<div class="tms_heading">Chữ ký người nhận</div>
</div>


</div>

<div class="clear"></div>
</div>
</div>
</div>

</div>
</center>
</div>
<div class="clear" style="height:28px;"></div>
 </section>
<!-- END: print -->
</body>		



<div class="clear"></div>

<div>
<div class="text-center">
	    <button class="btn btn-primary hidden-print" onclick="window.print();"><em class="fa fa-print">&nbsp;&nbsp;In vận đơn</em></button>
</div>
</div>
		
<!-- END: main -->