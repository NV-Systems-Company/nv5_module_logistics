<!-- BEGIN: main  -->

<form action="{NV_BASE_SITEURL}index.php" method="get">
	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
	<input type="hidden" name="{NV_OP_VARIABLE}"  value="search" />
	
<div class="tms_dangky_dichvu">
<span class="fa fa-search search_bill"></span>
<input type="text" value="{Q}" name="q" maxlength="255" placeholder="{LANG.nhapma_bill}"  >

</div>
	
	
		
	
</form>


<script>
	$('.search_bill').click(function(){
		var q = $('form input[name=q]').val();
		var link = '{link}';
		if(q != '')
		{
			location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '={MODULE_NAME}&' + nv_fc_variable + '=search&q=' + q;
		}
		else
		{
			alert('{LANG.chuanhap_ma_bill}');
		}
	});
</script>
<!-- END: main -->