<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$id = $nv_Request->get_array ( 'idcheck', 'post' );

foreach ( $id as $value ) {
	$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` WHERE id=" . $value . "";
	$db->sql_query ( $query );
}
Header ( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&op=brokenlink" );
exit();
?>