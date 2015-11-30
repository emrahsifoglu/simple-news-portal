<?php

use app\mvc\Model;
use lib\Crypto;

class UserModel extends Model {

    public $Username;
    private $Password;
    private $Role;
    private $encryptionKey = 'lkirwf897+22#bbtrm8814z5qq=498j5'; // 32 * 8 = 256 bit key
    private $encryptionIV = '741952hheeyy66#cs!9hjv887mxx7@8y'; // 32 * 8 = 256 bit iv


    /**
     * @return \UserModel
     */
    public function __construct(){
        parent::__construct('User', 'users');
        $this->Id = 0;
        $this->Role = '';
    }

    /**
     * @param string $property
     * @param string $value
     * @return void
     */
    public function __set($property, $value) {
        switch($property) {
            case 'Password':
                $this->Password = Crypto::encryptRJ256($this->encryptionKey, $this->encryptionIV, $value);
            break;
        }
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function __get($property) {
        switch($property) {
            case 'Password':
                return $this->Password;
            break;
            case 'Role':
                return $this->Role;
            break;
        }
    }

    /**
     * @desc This method returns user's id if the credential is found. In other cases, return value will be zero
     *
     * @return int
     */
    public function isAuthorized(){
        $where = ['users.username' => $this->Username, 'users.password' => $this->Password];
        $columns = ['users.id','users.username','roles.role'];
        $join = 'JOIN user_role ON users.id = user_role.user_id JOIN roles ON roles.id = user_role.role_id';

        //another approach
        /*$join = array(
                        array('join' => 'user_role', 'on' => 'users.id = user_role.user_id'),
                        array('join' => 'roles', 'on' => 'roles.id = user_role.role_id'));*/

        $result = $this->select('users '.$join, $columns, $where, ' LIMIT 1');
        if (!empty($result)) {
            $this->Id = $result[0][0];
            $this->Role = $result[0][2];
            return $this->Id;
        }
        return $this->Id;
    }

    public function isUsernameTaken(){
        $result = $this->find(array('username'), array('username' => $this->Username));
        return (!empty($result[0])) ? true : false;
    }
} 