<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'del', 'delconfirm', 'setactive', 'user_add', 'edit', 'user_waiting', 'delawaitinglist', 'question', 'siteterms', 'config' );
define( 'NV_IS_FILE_ADMIN', true );
$submenu['user_add'] = $lang_module['user_add'];
$submenu['user_waiting'] = $lang_module['member_wating'];
$submenu['question'] = $lang_module['question'];
$submenu['siteterms'] = $lang_module['siteterms'];
$submenu['config'] = $lang_module['config'];

function nv_fix_question()
{
    global $db;
    
    $query = "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight++;
        $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $weight . " WHERE `qid`=" . $row['qid'];
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

?>