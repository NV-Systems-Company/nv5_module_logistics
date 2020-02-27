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
										<span class="input-group-addon w100">{LANG.store}</span>
										<select class="form-control" name="store">
										<option value="0"> -- {LANG.select_store}-- </option>
										
										<!-- BEGIN: store -->
										<option value="{store.id}" {selected_store}>{store.title}</option>
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

[HUONGDANECEL]
<br/>
<strong><a href="{FILE_EXCEL}" style="color:red; text-align:center"><i class="fa fa-file-excel-o" aria-hidden="true"></i> TẢI FILE EXCEL MẪU</a></strong>

</div>
</div>
</div>
</div>


</div> 
<div class="clearfix"></div>



	


<!-- END: main -->