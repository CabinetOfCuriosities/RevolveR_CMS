<?php

/** 
  * 
  * RevolveR MySQL Data Base X class with 
  * array structure based queries syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class DBX {

    public static $host = 'localhost';
    public static $user = 'root';
    public static $pass = 'root';
    public static $name = 'root';
    public static $port = 3306;

    public static $result = [];
    public static $sql_query = '';

    function __construct( $db ) {
        // set connection data inside dbx
        self::$host = ( isset($db[0]) ) ? self::escape($db[0]) : self::escape(self::$host);
        self::$user = ( isset($db[1]) ) ? self::escape($db[1]) : self::escape(self::$user);
        self::$pass = ( isset($db[2]) ) ? self::escape($db[2]) : self::escape(self::$pass);
        self::$port = ( isset($db[4]) ) ? self::escape($db[4]) : self::escape(self::$port);
        self::$name = ( isset($db[3]) ) ? self::escape($db[3]) : self::escape(self::$name);
    }

    public static function query($dbx_q, $dbx_t, $dbx_f) {

        $dbx_q = self::escape($dbx_q);
        $dbx_t = self::escape($dbx_t);

        $dbx_q = explode('|', $dbx_q);
        $SQL = '';

        // switch query variant 
        switch ($dbx_q[0]) {
            case 'n': //

                $SQL .= 'CREATE DATABASE `'. self::$name .'`;';

                print $SQL;
                exit(0);
                
                break;

            case 'c': // create query
                    
                $SQL .= 'CREATE TABLE '. $dbx_t .'(';
                
                $SQL .= self::compileListValues($dbx_f, $dbx_q[0]);

                $SQL .= ');';

                break;
            case 't': // truncate query
                    
                $SQL .= 'TRUNCATE TABLE '. $dbx_t .';';

                break;
            case 'd': // drop query
                    
                $SQL .= 'DROP TABLE '. $dbx_t .';';

                break;
            case 's': // select query
                    
                $SQL .= 'SELECT ';
                $SQL_0 = '';

                $c = 0;

                foreach ($dbx_f as $k => $v) {

                    if( $c > 0) {
                        $SQL .= ', ';
                    }

                    $SQL .= '`'. $k .'`';

                    $c++;
                }

                if( isset( $dbx_q[1]) ) {

                    $SQL_0 .= ' ORDER BY `'. $dbx_q[1] .'`';
                
                    if( isset($dbx_q[2]) ) {

                        switch ($dbx_q[2]) {
                            case 'asc':
                                $SQL_0 .= ' ASC';
                                break;
                            
                            case 'desc':
                                $SQL_0 .= ' DESC';
                                break;
                        }

                    }

                    if( isset($dbx_q[3]) ) {
                        $SQL_0 .= ' LIMIT ' . (int)$dbx_q[3];
                    }

                    if( isset($dbx_q[4]) ) {
                        $SQL_0 .= ' OFFSET ' . (int)$dbx_q[4];
                    }

                }

                $SQL .= ' FROM '. $dbx_t . $SQL_0 .';';

                break;
            case 'i': // inser query
                    
                $SQL .= 'INSERT INTO '. $dbx_t .'(';
                
                $SQL .= self::compileListValues($dbx_f, $dbx_q[0]);
                
                $SQL .= ') VALUES (';

                $c = 0;
                foreach ($dbx_f as $f => $v) {

                    if( isset($v['value']) ) {

                        if( $c > 0 ) {
                            $SQL .= ', ';
                        }

                        $SQL .= '\''. self::escape($v['value']) .'\'';

                        $c++; 
                    }

                 } 

                $SQL .= ');';

                break;
            case 'u': // update query
                    
                $SQL .= 'UPDATE '. $dbx_t .' SET ';

                $SQL_0 = '';
                $SQL_1 = '';
                $SQL_2 = '';
                $SQL_3 = '';

                foreach ($dbx_f as $k => $v) {
                    
                    foreach( $v as $c => $val) {

                        if( isset($v['new_value']) ) {

                            if( $c === 'criterion_field' ) {
                                $SQL_0 .= '`'. $k .'`';
                                $SQL_2 .= '`'. $val .'`';
                            }

                            if( $c === 'criterion_value' ) {
                                $SQL_3 .= '=\''. self::escape($val) . '\'';
                            }

                            if( $c === 'new_value' ) {
                                $SQL_1 .= '=\''. self::escape($val) . '\'';
                            }

                        }

                    }

                }

                $SQL .= $SQL_0 . $SQL_1 .' WHERE ' . $SQL_2 . $SQL_3 . ';';

                break;
            case 'x': // delete query
                    
                $SQL .= 'DELETE FROM '. $dbx_t .' WHERE ';

                foreach ($dbx_f as $k => $v) {
                    
                    foreach( $v as $c => $val) {

                        if( $c === 'criterion_field' ) {
                            $SQL_0 = '`'. self::escape($val) .'`';
                        }

                        if( $c === 'criterion_value' ) {
                            $SQL_1 .= '=\''. self::escape($val) . '\'';
                        }

                    }

                }

                $SQL .= $SQL_0 . $SQL_1 . ';';

                break;
        }
        
        self::$sql_query = $SQL;
        self::executionSQL($SQL);

        //print $SQL;
    }

    // connect database
    public static function connect() {

        $dbx_link = mysqli_connect(
            DBX::$host, 
            DBX::$user, 
            DBX::$pass, 
            DBX::$name, 
            DBX::$port
        );

        if (!$dbx_link) {
            $status = [
                'CONNECTION' => FALSE,
                'DEBUG' => mysqli_connect_error() .' [#'. mysqli_connect_errno() .']' 
            ];
        } 
        else {
            
            $status = [
                'CONNECTION' => TRUE,
                'DEBUG'  => $dbx_link->error .' [#'. $dbx_link->errno .'] [%'. $dbx_link->warning_count .']',
                'SERVER' => $dbx_link->server_info,
                'STATUS' => $dbx_link->stat
            ];

        }

        // inject status
        return [$dbx_link, $status];

    }

    // perform SQL execution
    public static function executionSQL( $SQL ) {
        
        $DBX = self::connect();

        // check if connection success
        if( $DBX[1]['CONNECTION'] ) {
           
            $result = $DBX[0]->query($SQL);

            if( $result->num_rows > 0 ) {

                $counter = 0;

                while($row = mysqli_fetch_assoc($result)) { 
                    DBX::$result['result'][$counter++] = $row;
                }

            }

            self::$result['status'] = $DBX[1];

            if( isset($result->num_rows) ) {
               self::$result['status']['affected_rows'] = $result->num_rows;
            }

            if( isset($result->field_count) ) {
               self::$result['status']['affected_fields'] = $result->field_count;
            }

            self::$result['status']['sql_query_debug'] = DBX::$sql_query;

            self::disconnect($DBX);

        }

    }

    // combine some list values for create query and insert query
    public static function compileListValues($list, $query) {
        
        $SQL = '';
        $c = 0;

        foreach ($list as $field => $value) {


            if( $query === 'c' ) {
                
                if( $c > 0 ) {
                    $SQL .= ', ';
                }

                $SQL .= '`'. $field .'` '. self::parseFieldsSyntax($query, $value);

                $c++;
            }

            if( $query === 'i' ) {
                
                if( $c > 0 ) {
                    $SQL .= ', ';
                }

                $SQL .= '`'. $field . '`';

                $c++;
            }

        }

        return $SQL;

    }

    // combine some SQL fragments with fields
    public static function parseFieldsSyntax($m, $dbx_f_v) {

        $auto_counter = 0;
        $field_count  = count($dbx_f_v);

        $sql_fragment_0 = '';
        $sql_fragment_1 = '';
        $sql_fragment_2 = '';
        $sql_fragment_3 = '';
        $sql_fragment_4 = '';

        foreach ($dbx_f_v as $k => $v) {

            if( $m === 'c' ) {
                if( $k === 'type' ) {

                    switch ($v) {
                        case 'text':
                            
                            $sql_fragment_0 = 'VARCHAR'; 

                            break;
                        case 'num':
                            
                            $sql_fragment_0 = 'INT'; 

                            break; 
                        case 'time':
                            
                            $sql_fragment_0 = 'TIMESTAMP'; 

                            break;
                    }

                }
        

                if( $k === 'length' ) {
                    $sql_fragment_1 = '('. (int)$v .')';
                }


                if( $k === 'auto' && $auto_counter <= 0 ) {
                    $sql_fragment_2 = 'AUTO_INCREMENT PRIMARY KEY';
                    $auto_counter++;
                }

                if( $k === 'fill' && $k !== 'auto' ) {
                    $sql_fragment_3 = 'NOT NULL';
                }
            
                $sql_fragment = $sql_fragment_0 . $sql_fragment_1 .' '. $sql_fragment_2 .' '. $sql_fragment_3 . ', ';
                $sql_fragment = rtrim($sql_fragment, ", ");

            }

        }      

        return $sql_fragment;
    }

    // clenup
    public static function escape( $string ) {
        return htmlspecialchars(addslashes(preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xEF\xBF\xBD", $string)));
    }

    // disconnect database
    public static function disconnect($db) {        
        mysqli_close($db[0]);

    }

}

?>