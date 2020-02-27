<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
<div class="panel-body">
<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="id_document" value="{id_document}" />
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.price}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20 list_price">
			<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-5">
					Số tiền thu hộ: 0 đ - 2.000.000đ <br/>
					Số tiền thu hộ: 2.000.000 đ - 5.000.000
				</div>
				<div class="col-xs-12 col-sm-7 col-md-5">
						Phí thu hộ ví dụ: 2%<br/>
						Phí thu hộ ví dụ: 1%
				</div>
				
		</div>
		<!-- BEGIN: price_add -->
			<div class="row">
				<div class="col-xs-12 col-sm-7 col-md-5">
					<input class="form-control" type="text" name="quantity[]" value="{price.quantity}" required="required" placeholder="{LANG.value_goods_price}" />
				</div>
				<div class="col-xs-12 col-sm-7 col-md-5">
					<input class="form-control" type="text" name="price[]" value="{price.price}" required="required" placeholder="{LANG.price_value_goods}"/>
				</div>
			</div>
		<!-- END: price_add -->
		<!-- BEGIN: price -->
			<div class="row" style="margin-bottom:10px;">
				<div class="col-xs-12 col-sm-7 col-md-5">
					<input class="form-control" type="text" name="quantity[]" value="{price.quantity}" required="required" placeholder="{LANG.quantity}" />
				</div>
				<div class="col-xs-12 col-sm-7 col-md-5">
					<input class="form-control" type="text" name="price[]" value="{price.price}" required="required" placeholder="{LANG.price}"/>
				</div>
				<span class="delete_price" onclick="delete_price(this);">{LANG.delete}</span>
			</div>
		<!-- END: price -->

			</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.id_document}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-19 col-md-20">
			<select disabled class="form-control w200"  name="id_document"  style="float:left">
				<option value="0"></option>
			<!-- BEGIN: document -->
				<option {document.selected} value="{document.id}">{document.title}</option>
			<!-- END: document -->
			</select>
			<a class="add_price btn btn-primary" style="float:left; margin-left:20px">{LANG.add_price}</a>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-5 col-md-4 control-label"><strong>{LANG.price_min}</strong> <span class="red">(*)</span></label>
		<div class="col-sm-9 col-md-9">
			<input class="form-control" type="text" name="price_min" value="{ROW.price_min}"/>
		</div>
	</div>
	
	<div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
</div></div>

<script type="text/javascript">

	$('.add_price').click(function(){
		var html = '<div class="row"style="margin-bottom:10px;"><div class="col-xs-12 col-sm-7 col-md-5"><input class="form-control" type="text" name="quantity[]" value="" required="required" placeholder="{LANG.value_goods_price}"></div><div class="col-xs-12 col-sm-7 col-md-5"><input class="form-control" type="text" name="price[]" value="" required="required" placeholder="{LANG.price_value_goods}"></div><span class="delete_price" onclick="delete_price(this);">{LANG.delete}</span></div>';
		$('.list_price').append(html);
	});
	
	function delete_price(a)
	{

		$(a).parent().remove();
		
	}


//]]>
</script>
<!-- END: main -->