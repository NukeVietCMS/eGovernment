<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

function nv_banner_client_checkdata ( $cookie )
{
    global $db;
    
    $client = unserialize( $cookie );
    
    $strlen = ( NV_CRYPT_SHA1 == 1 ) ? 40 : 32;
    
    $banner_client_info = array();
    
    if ( isset( $client['login'] ) and preg_match( "/^[a-zA-Z0-9_]{" . NV_UNICKMIN . "," . NV_UNICKMAX . "}$/", $client['login'] ) )
    {
        if ( isset( $client['checknum'] ) and preg_match( "/^[a-z0-9]{" . $strlen . "}$/", $client['checknum'] ) )
        {
            $login = $client['login'];
            $query = "SELECT * FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `login` = " . $db->dbescape( $login ) . " AND `act`=1";
            $result = $db->sql_query( $query );
            
            $numrows = $db->sql_numrows( $result );
            if ( $numrows != 1 ) return array();
            
            $row = $db->sql_fetchrow( $result );
            $db->sql_freeresult( $result );
            
            if ( strcasecmp( $client['checknum'], $row['check_num'] ) == 0 and //checknum
! empty( $client['current_agent'] ) and strcasecmp( $client['current_agent'], $row['last_agent'] ) == 0 and //user_agent
! empty( $client['current_ip'] ) and strcasecmp( $client['current_ip'], $row['last_ip'] ) == 0 and //IP
! empty( $client['current_login'] ) and strcasecmp( $client['current_login'], intval( $row['last_login'] ) ) == 0 ) //current login
            

            {
                $banner_client_info['id'] = intval( $row['id'] );
                $banner_client_info['login'] = $row['login'];
                $banner_client_info['email'] = $row['email'];
                $banner_client_info['full_name'] = $row['full_name'];
                $banner_client_info['reg_time'] = intval( $row['reg_time'] );
                $banner_client_info['website'] = $row['website'];
                $banner_client_info['location'] = $row['location'];
                $banner_client_info['yim'] = $row['yim'];
                $banner_client_info['phone'] = $row['phone'];
                $banner_client_info['fax'] = $row['fax'];
                $banner_client_info['mobile'] = $row['mobile'];
                $banner_client_info['current_login'] = intval( $row['last_login'] );
                $banner_client_info['last_login'] = intval( $client['last_login'] );
                $banner_client_info['current_agent'] = $row['last_agent'];
                $banner_client_info['last_agent'] = $client['last_agent'];
                $banner_client_info['current_ip'] = $row['last_ip'];
                $banner_client_info['last_ip'] = $client['last_ip'];
            }
        }
    }
    return $banner_client_info;
}

$bncl = $nv_Request->get_string( 'bncl', 'cookie' );
if ( ! empty( $bncl ) )
{
    $banner_client_info = nv_banner_client_checkdata( $bncl );
    if ( empty( $banner_client_info ) )
    {
        $nv_Request->unset_request( 'bncl', 'cookie' );
        header( "Location: " . $client_info['selfurl'] );
        die();
    }
    define( 'NV_IS_BANNER_CLIENT', true );
}
unset( $bncl );

define( 'NV_IS_MOD_BANNERS', true );

?>