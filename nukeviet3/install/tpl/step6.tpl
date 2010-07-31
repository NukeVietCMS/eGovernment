<!-- BEGIN: step -->
<script type="text/javascript">
      $(document).ready(function(){
        $("#site_config").validate({
			rules:{
        		nv_login: {
					minlength:5
				},
				nv_password: {
					minlength:6
				},				
				re_password:{
					equalTo: "#nv_password"
				}
			}
		});
		
      });
</script>
<form action="" id="site_config"  method="post">
<table cellspacing="0" summary="{LANG.website_info}">
	<caption>{LANG.properties} <span class="highlight_red">*</span>{LANG.is_required}
	</caption>
	<tr>
		<th scope="col" abbr="" class="nobg" style="width: 176px;">&nbsp;</th>
		<th scope="col" abbr="{LANG.enter_form}">{LANG.enter_form}</th>
		<th scope="col" abbr="{LANG.note}">{LANG.note}</th>
	</tr>
	<tr>
		<th scope="row" abbr="" class="spec">{LANG.sitename} <span class="highlight_red">*</span> </th>
		<td><input type="text" name="site_name" value="{DATA.site_name}" class="required" /></td>
		<td>{LANG.sitename_note}</td>
	</tr>
	<tr>
		<th scope="row" abbr="" class="specalt">{LANG.admin_account} 
		<span class="highlight_red">*</span>
		</th>
		<td class="alt"><input type="text" value="{DATA.nv_login}"
			name="nv_login" class="required" /></td>
		<td class="alt">{LANG.admin_account_note}</td>
	</tr>
	<tr>
		<th scope="row" abbr="" class="spec">{LANG.admin_email} <span
			class="highlight_red">*</span></th>
		<td><input type="text" value="{DATA.nv_email}" name="nv_email"
			class="required email" /></td>
		<td>{LANG.admin_email_note}</td>
	</tr>
	<tr>
		<th scope="row" abbr="" class="specalt">{LANG.admin_pass} <span
			class="highlight_red">*</span></th>
		<td class="alt"><input type="password" value="{DATA.nv_password}"
			id="nv_password" name="nv_password" class="required" /></td>
		<td class="alt">{LANG.admin_pass_note}</td>
	</tr>
	<tr>
		<th scope="row" abbr="" class="spec">{LANG.admin_repass} <span
			class="highlight_red">*</span></th>
		<td><input type="password" value="{DATA.re_password}" id="re_password" name="re_password"
			class="required" /></td>
		<td>{LANG.admin_repass_note}</td>
	</tr>
	<tr>
		<th class="spec"></th>
		<td class="spec" colspan="2"><input type="submit"
			value="{LANG.refesh}" /></td>
	</tr>
</table>
    <!-- BEGIN: errordata -->
    	<span class="highlight_red"> {DATA.error} </span>
    <!-- END: errordata -->
</form>

<ul class="control_t fr">
	<li><span class="back_step"><a
		href="{BASE_SITEURL}index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=5">{LANG.previous}</a></span>
	</li>
	<!-- BEGIN: nextstep -->
	<li><span class="next_step"><a
		href="{BASE_SITEURL}index.php?{LANG_VARIABLE}={CURRENTLANG}&amp;step=7">{LANG.next_step}</a></span>
	</li>
	<!-- END: nextstep -->
</ul>
<!-- END: step -->
