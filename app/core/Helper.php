<?php

namespace app\core;

use ReflectionClass;

class Helper {

    /**
     * @access public
     * @param string $route
     * @return void
     */
    public static function redirectTo($route){
        header("Location: ".$route);
    }

    /**
     * @access public
     * @param string $class
     * @return object
     */
    public static function createInstance($class) {
        $reflection_class = new ReflectionClass($class);
        return $reflection_class->newInstanceArgs();
    }

    /**
     * @desc This function generates a random string using the linux random file for more entropy
     * @param int $length
     * @return string
     */
    public static function random($length) {
        $return = '';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $byteLen = intval(($length / 2) + 1);
            $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $length);
        } elseif (@is_readable('/dev/urandom')) {
            $f=fopen('/dev/urandom', 'r');
            $urandom=fread($f, $length);
            fclose($f);
            $return = '';
        }

        if (empty($return)) {
            for ($i=0; $i < $length; ++$i) {
                if (!isset($urandom)) {
                    if ($i%2==0) {
                        mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                    }
                    $rand=48+mt_rand()%64;
                } else {
                    $rand=48+ord($urandom[$i])%64;
                }

                if ($rand>57)
                    $rand+=7;
                if ($rand>90)
                    $rand+=6;

                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return.=chr($rand);
            }
        }
        return $return;
    }

} 