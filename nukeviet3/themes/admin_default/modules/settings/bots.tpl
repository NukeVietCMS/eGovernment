<!-- BEGIN: main -->
<form action="" method="post">
<table class="tab1" summary="">
	<caption>{LANG.page_title}</caption>
	<thead>
		<tr>
			<td>{LANG.bot_name}</td>
			<td>{LANG.bot_agent}(*)</td>
			<td>{LANG.bot_ips} (**)</td>
			<td>{LANG.bot_allowed}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody class="{DATA.class}">
		<tr>
			<td><input type="text" value="{DATA.name}" name="bot_name[{DATA.id}]" style="width: 200px;" /></td>
			<td><input type="text" value="{DATA.agent}" name="bot_agent[{DATA.id}]" style="width: 200px;" /></td>
			<td><input type="text" value="{DATA.ips}" name="bot_ips[{DATA.id}]" style="width: 200px;" /></td>
			<td><input type="checkbox" checked="{DATA.checked}" value="1" name="bot_allowed[{DATA.id}]" /></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<div style="width: 200px; margin: 10px auto; text-align: center;"><input type="submit" name="submit" value="{LANG.submit}" style="width: 100px;" /></div>
</form>
<!-- END: main -->