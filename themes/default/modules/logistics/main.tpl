<!-- BEGIN: main -->

<div class="rows"> 

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="panel panel-default">
<div class="panel-heading"><strong>{LANG.hello}: {name}</strong></div>
<div class="panel-body">


<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-bar-chart"></span> <div><a title="{LANG.sum_bill}" href="#">{LANG.sum_bill}: {tong_vandon}</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-user"></span> <div><a title="{LANG.username}" href="#">{LANG.dagiao_thongke}: {dagiao}</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-user"></span> <div><a title="{LANG.trahang}" href="#">{LANG.trahang}: {tong_trahang}</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-user"></span> <div><a title="{LANG.doisoat}" href="#">{LANG.doisoat}: {tong_doisoat}</a></div></div>
</div>


 <div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-university"></span> <div><a title="{LANG.thuho_thongke}" href="#">{LANG.thuho_thongke}: {tong_thu}đ</a></div></div>
</div> 

  <div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_thongke"><span class="fa fa-money"></span> <div><a title="{LANG.cuocphi_thongke}" href="#">{LANG.cuocphi_thongke}: {tong_cuocphi}đ</a></div></div>
</div>


</div>
</div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">

<div class="panel panel-default">
<div class="panel-heading"><strong>{LANG.thongke_thangnay} {thang_nam}</strong></div>
<div class="panel-body">

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_dongtien"><span class="fa fa-bar-chart"></span> <div><a title="{LANG.sum_bill}" href="#">{LANG.sum_bill}:{tong_vandon_thang}</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_dongtien"><span class="fa fa-user"></span> <div><a title="{LANG.thuho_thongke}" href="#">{LANG.thuho_thongke}: {tong_thu_thang}đ</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_dongtien"><span class="fa fa-user"></span> <div><a title="{LANG.chuadoisoat_tk}" href="#">{LANG.chuadoisoat_tk}: {chuadoisoat_tk_thang}đ</a></div></div>
</div>

<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_dongtien"><span class="fa fa-user"></span> <div><a title="{LANG.dadoisoat}" href="#">{LANG.dadoisoat}: {tong_doisoat_thang}đ</a></div></div>
</div>
<div class="col-lg-12 col-md-12  col-xs-24">
<div class="tms_dongtien"><span class="fa fa-money"></span> <div><a title="{LANG.cuocphi_thongke}" href="#">{LANG.cuocphi_thongke}: {tong_cuocphi_thang}đ</a></div></div>
</div>

</div>
</div>

</div>


</div> 
<div class="clearfix"></div>




<div class="rows">  
<div class="panel panel-default">  
<div class="panel-body">
<div class="col-xs-24 col-sm-24 col-md-24">
    <ol class="breadcrumb-arrow">
		<li class="active"><a href="">{LANG.thongke_thangnay1} {thang_nam}: </a></li>
		<!-- BEGIN: status_bill -->
		<li><a href="{link_trangthai}">{schedule.title}: {dem_trinhtrang}</a></li>
		<!-- END: status_bill -->
	</ol>

</div>
<div class="clearfix"></div>

<!-- BEGIN: bill_home -->
<div class="col-xs-24 col-sm-24 col-md-6">
	<div class="panel panel-default">
	<div class="panel-heading"><strong>{schedule.title}</strong></div>
	<div class="panel-body">
	<!-- BEGIN: loop -->
		<div class="panel panel-default">
			<div class="panel-body">
			<strong class="bill_home">{LANG.bill}: {row.bill}</strong><br/>
			<i class="fa fa-money"></i> {LANG.cuoc}: {row.total_money}đ <br/> <i class="fa fa-calendar"></i> {LANG.add_date}:{row.add_date}<br/>
			<i class="fa fa-user"></i> {row.receive_name}, {row.receive_phone} <br/>
			</div>			
		</div>
	<!-- END: loop -->
	</div>
	</div>	


</div>
<!-- END: bill_home -->	


	
</div>
</div>
</div>
<div class="clear"></div>








<!-- END: main -->