<?php

namespace app\core;

use lib\Session;

class Security {

    /**
     * @access public
     * @return bool
     */
    public static function isUserLoggedIn(){
        Session::Start();
        return (Session::Get('user')[0] == 0) ? false : true;
    }
    /**
     * @access public
     * @return int
     */
    public static function getUserId(){
        Session::Start();
        return Session::Get('user')[0];
    }

    public static function getUserRole(){
        Session::Start();
        return Session::Get('user')[1];
    }

    /**
     * @access public
     * @param integer $id
     * @param string $role
     * @return bool
     */
    public static function loggedIn($id, $role){
        Session::Start();
        Session::Set('user', array($id, $role));
    }

    /**
     * @access public
     * @return bool
     */
    public static function loggedOut(){
        Session::Start();
        Session::Set('user', []);
        Session::Stop();
    }

    /**
     * @access public
     * @param string $name
     * @return mixed
     */
    public static function getCSRFToken($name){
        Session::Start();
        return Session::Get($name);
    }

    /**
     * @access public
     * @param string $name
     * @return void
     */
    public static function destroyCSRFToken($name){
        Session::Start();
        Session::Destroy($name);
    }

    /**
     * @access public
     * @param string $name
     * @param int $length
     * @return string
     */
    public static function generateCSRFToken($name, $length = 100){
        $token = Helper::random($length);
        Session::Start();
        Session::Set($name, $token);
        return hash('sha256', $token);
    }
}