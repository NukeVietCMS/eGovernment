<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 5-8-2010  1:13
 */

/**
 * Get data from file as a URL
 * 
 * @package NUKEVIET 3.0
 * @author VINADES., JSC
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class UrlGetContents
{

    private $allow_methods = array();

    private $safe_mode;

    private $open_basedir;

    private $url_info = false;

    /**
     * UrlGetContents::__construct()
     * 
     * @return
     */
    function __construct ( )
    {
        if ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false )
        {
            $disable_functions = array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) );
        }
        else
        {
            $disable_functions = array();
        }
        
        if ( extension_loaded( 'curl' ) and function_exists( "curl_init" ) and ! in_array( 'curl_init', $disable_functions ) )
        {
            $this->allow_methods[] = 'curl';
        }
        
        if ( function_exists( "fsockopen" ) and ! in_array( 'fsockopen', $disable_functions ) )
        {
            $this->allow_methods[] = 'fsockopen';
        }
        
        if ( ini_get( 'allow_url_fopen' ) == '1' or strtolower( ini_get( 'allow_url_fopen' ) ) == 'on' )
        {
            if ( function_exists( "fopen" ) and ! in_array( 'fopen', $disable_functions ) )
            {
                $this->allow_methods[] = 'fopen';
            }
            
            if ( function_exists( "file_get_contents" ) and ! in_array( 'file_get_contents', $disable_functions ) )
            {
                $this->allow_methods[] = 'file_get_contents';
            }
        }
        
        if ( ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on' )
        {
            $this->safe_mode = true;
        }
        else
        {
            $this->safe_mode = false;
        }
        
        if ( ini_get( 'open_basedir' ) == '1' || strtolower( ini_get( 'open_basedir' ) ) == 'on' )
        {
            $this->open_basedir = true;
        }
        else
        {
            $this->open_basedir = false;
        }
    }

    /**
     * UrlGetContents::curl_Get()
     * 
     * @param mixed $url
     * @param string $login
     * @param string $password
     * @param string $ref
     * @return
     */
    private function curl_Get ( $url, $login = '', $password = '', $ref = '' )
    {
        $curlHandle = curl_init();
        curl_setopt( $curlHandle, CURLOPT_ENCODING, '' );
        curl_setopt( $curlHandle, CURLOPT_URL, $url );
        curl_setopt( $curlHandle, CURLOPT_HEADER, 0 );
        curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, 1 );
        
        if ( ! empty( $login ) )
        {
            curl_setopt( $curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
            curl_setopt( CURLOPT_USERPWD, '[' . $login . ']:[' . $password . ']' );
        }
        
        curl_setopt( $curlHandle, CURLOPT_USERAGENT, ini_get( "user_agent" ) );
        
        if ( ! empty( $ref ) )
        {
            curl_setopt( $curlHandle, CURLOPT_REFERER, urlencode( $ref ) );
        }
        else
        {
            curl_setopt( $curlHandle, CURLOPT_REFERER, $url );
        }
        
        if ( ! $this->safe_mode and $this->open_basedir )
        {
            curl_setopt( $curlHandle, CURLOPT_FOLLOWLOCATION, 1 );
            curl_setopt( $curlHandle, CURLOPT_MAXREDIRS, 10 );
            die( "Den day" );
        }
        
        curl_setopt( $curlHandle, CURLOPT_TIMEOUT, 30 );
        
        $result = curl_exec( $curlHandle );
        
        if ( curl_errno( $curlHandle ) == 23 || curl_errno( $curlHandle ) == 61 )
        {
            curl_setopt( $curlHandle, CURLOPT_ENCODING, 'none' );
            $result = curl_exec( $curlHandle );
        }
        
        if ( curl_errno( $curlHandle ) )
        {
            curl_close( $curlHandle );
            return false;
        }
        
        $response = curl_getinfo( $curlHandle );
        if ( ( $response['http_code'] < 200 ) || ( 300 <= $response['http_code'] ) )
        {
            curl_close( $curlHandle );
            return false;
        }
        
        curl_close( $curlHandle );
        
        return $result;
    }

    /**
     * UrlGetContents::fsockopen_Get()
     * 
     * @param mixed $url
     * @param string $login
     * @param string $password
     * @param string $ref
     * @return
     */
    private function fsockopen_Get ( $url, $login = '', $password = '', $ref = '' )
    {
        if ( strtolower( $this->url_info['scheme'] ) == 'https' )
        {
            $this->url_info['host'] = "ssl://" . $this->url_info['host'];
            $this->url_info['port'] = 443;
        }
        
        $fp = @fsockopen( $this->url_info['host'], $this->url_info['port'], $errno, $errstr, 30 );
        if ( ! $fp )
        {
            return false;
        }
        
        $request = "GET " . $this->url_info['path'] . $this->url_info["query"];
        $request .= " HTTP/1.0\r\n";
        $request .= "Host: " . $this->url_info['host'];
        
        if ( $this->url_info['port'] != 80 )
        {
            $request .= ":" . $info['port'];
        }
        $request .= "\r\n";
        
        $request .= "Connection: Close\r\n";
        $request .= "User-Agent: " . ini_get( "user_agent" ) . "\r\n\r\n";
        
        if ( function_exists( 'gzinflate' ) )
        {
            $request .= "Accept-Encoding: gzip,deflate\r\n";
        }
        
        $request .= "Accept: */*\r\n";
        
        if ( ! empty( $ref ) )
        {
            $request .= "Referer: " . urlencode( $ref ) . "\r\n";
        }
        else
        {
            $request .= "Referer: " . $url . "\r\n";
        }
        
        if ( ! empty( $login ) )
        {
            $request .= "Authorization: Basic ";
            $request .= base64_encode( $login . ':' . $password );
            $request .= "\r\n";
        }
        
        $request .= "\r\n";
        
        if ( @fwrite( $fp, $request ) === false )
        {
            @fclose( $fp );
            return false;
        }
        
        @stream_set_blocking( $fp, true );
        @stream_set_timeout( $fp, 30 );
        $in_f = @stream_get_meta_data( $fp );
        
        $response = "";
        
        while ( ( ! @feof( $fp ) ) && ( ! $in_f['timed_out'] ) )
        {
            $response .= @fgets( $fp, 4096 );
            $inf = @stream_get_meta_data( $fp );
            if ( $inf['timed_out'] )
            {
                @fclose( $fp );
                return false;
            }
        }
        
        if ( function_exists( 'gzinflate' ) and substr( $response, 0, 8 ) == "\x1f\x8b\x08\x00\x00\x00\x00\x00" )
        {
            $response = substr( $response, 10 );
            $response = gzinflate( $response );
        }
        
        @fclose( $fp );
        
        list( $header, $result ) = preg_split( "/\r?\n\r?\n/", $response, 2 );
        unset( $matches );
        preg_match( "/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches );
        if ( $matches == array() || $matches[1] != 200 )
        {
            return false;
        }
        
        return $result;
    }

    /**
     * UrlGetContents::fopen_Get()
     * 
     * @param mixed $url
     * @return
     */
    private function fopen_Get ( $url )
    {
        if ( ( $fd = @fopen( $url, "rb" ) ) === false )
        {
            return false;
        }
        
        $result = '';
        while ( ( $data = fread( $fd, 4096 ) ) != "" )
        {
            $result .= $data;
        }
        fclose( $fd );
        
        return $result;
    }

    /**
     * UrlGetContents::file_get_contents_Get()
     * 
     * @param mixed $url
     * @return
     */
    private function file_get_contents_Get ( $url )
    {
        return file_get_contents( $url );
    }

    /**
     * UrlGetContents::url_get_info()
     * 
     * @param mixed $url
     * @return
     */
    private function url_get_info ( $url )
    {
        //URL: http://username:password@www.example.com:80/dir/page.php?foo=bar&foo2=bar2#bookmark
        $url_info = @parse_url( $url );
        
        //[host] => www.example.com
        if ( ! isset( $url_info['host'] ) )
        {
            return false;
        }
        
        //[port] => :80
        $url_info['port'] = isset( $url_info['port'] ) ? $url_info['port'] : 80;
        
        //[login] => username:password@
        $url_info['login'] = '';
        if ( isset( $url_info['user'] ) )
        {
            $url_info['login'] = $url_info['user'];
            if ( isset( $url_info['pass'] ) )
            {
                $url_info['login'] .= ':' . $url_info['pass'];
            }
            $url_info['login'] .= '@';
        }
        
        //[path] => /dir/page.php
        if ( isset( $url_info['path'] ) )
        {
            if ( substr( $url_info['path'], 0, 1 ) != '/' )
            {
                $url_info['path'] = '/' . $url_info['path'];
            }
        }
        else
        {
            $url_info['path'] = '/';
        }
        
        //[query] => ?foo=bar&foo2=bar2
        $url_info['query'] = isset( $url_info['query'] ) ? '?' . $url_info['query'] : '';
        
        //[fragment] => bookmark
        $url_info['fragment'] = isset( $url_info['fragment'] ) ? $url_info['fragment'] : '';
        
        //[file] => page.php
        $url_info['file'] = explode( '/', $url_info['path'] );
        $url_info['file'] = array_pop( $url_info['file'] );
        
        //[dir] => /dir
        $url_info['dir'] = substr( $url_info['path'], 0, strrpos( $url_info['path'], '/' ) );
        
        //[base] => http://www.example.com/dir
        $url_info['base'] = $url_info['scheme'] . '://' . $url_info['host'] . $url_info['dir'];
        
        //[uri] => http://username:password@www.example.com:80/dir/page.php?#bookmark
        $url_info['uri'] = $url_info['scheme'] . '://' . $url_info['login'] . $url_info['host'];
        if ( $url_info['port'] != 80 )
        {
            $url_info['uri'] .= ':' . $url_info['port'];
        }
        $url_info['uri'] .= $url_info['path'] . '?' . $url_info['query'];
        if ( $url_info['fragment'] != '' )
        {
            $url_info['uri'] .= '#' . $url_info['fragment'];
        }
        
        return $url_info;
    }

    /**
     * UrlGetContents::get()
     * 
     * @param mixed $url
     * @param string $login
     * @param string $password
     * @param string $ref
     * @return
     */
    public function get ( $url, $login = '', $password = '', $ref = '' )
    {
        $this->url_info = $this->url_get_info( $url );
        
        if ( ! $this->url_info or ! isset( $this->url_info['scheme'] ) )
        {
            return false;
        }
        
        $disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
        $safe_mode = ( ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on' ) ? 1 : 0;
        
        if ( ! $safe_mode and function_exists( 'set_time_limit' ) and ! in_array( 'set_time_limit', $disable_functions ) )
        {
            set_time_limit( 0 );
        }
        if ( function_exists( 'ini_set' ) and ! in_array( 'ini_set', $disable_functions ) )
        {
            ini_set( 'allow_url_fopen', 1 );
            ini_set( 'default_socket_timeout', 120 );
            ini_set( 'memory_limit', '64M' );
        }
        $result = '';
        
        foreach ( $this->allow_methods as $method )
        {
            if ( $method == 'curl' )
            {
                $result = $this->curl_Get( $url, $login, $password, $ref );
                if ( ! empty( $result ) )
                {
                    break;
                }
            }
            elseif ( $method == 'fsockopen' )
            {
                $result = $this->fsockopen_Get( $url, $login, $password, $ref );
                if ( ! empty( $result ) )
                {
                    break;
                }
            }
            elseif ( $method == 'fopen' )
            {
                $result = $this->fopen_Get( $url );
                if ( ! empty( $result ) )
                {
                    break;
                }
            }
            elseif ( $method == 'file_get_contents' )
            {
                $result = $this->file_get_contents_Get( $url );
                if ( ! empty( $result ) )
                {
                    break;
                }
            }
        }
        
        return $result;
    }
}

?>