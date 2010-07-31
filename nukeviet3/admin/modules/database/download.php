<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:49
 */

if ( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$tables = $nv_Request->get_array( 'tables', 'post', array() );
$type = filter_text_input( 'type', 'post', '' );
$ext = filter_text_input( 'ext', 'post', '' );

if ( empty( $tables ) )
{
    $tables = array();
}
elseif ( ! is_array( $tables ) )
{
    $tables = array( 
        $tables 
    );
}

$tab_list = array();
$result = $db->sql_query( "SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'" );
while ( $item = $db->sql_fetchrow( $result ) )
{
    $tab_list[] = $item[0];
}
$db->sql_freeresult( $result );

$contents = array();
$contents['tables'] = ( empty( $tables ) ) ? $tab_list : array_values( array_intersect( $tab_list, $tables ) );
$contents['type'] = ( $type != "str" ) ? "all" : "str";
$contents['savetype'] = ( $ext != "sql" ) ? "gz" : "sql";
$contents['filename'] = tempnam( NV_ROOTDIR . "/" . NV_TEMP_DIR, NV_TEMPNAM_PREFIX );

include ( NV_ROOTDIR . "/includes/core/dump.php" );
$result = nv_dump_save( $contents );
if ( ! empty( $result ) )
{
    $content['mime'] = ( $contents['savetype'] == "gz" ) ? 'application/x-gzip' : 'text/x-sql';
    $contents['fname'] = $db->dbname . '.sql';
    if ( $contents['savetype'] == "gz" )
    {
        $contents['fname'] .= '.gz';
    }
    
    header( 'Content-Description: File Transfer' );
    header( "Content-Type: " . $content['mime'] . "; name=\"" . $contents['fname'] . "\"" );
    header( "Content-Disposition: attachment; filename=\"" . basename( $contents['fname'] ) . "\"" );
    header( "Content-Transfer-Encoding: binary" );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Pragma: public' );
    header( "Content-Length: " . $result[1] );
    ob_end_clean();
    flush();
    readfile( $result[0] );
    exit();
}
die();
?>