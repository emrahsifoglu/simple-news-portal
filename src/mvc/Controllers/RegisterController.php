<?php

use app\core\Helper;
use app\core\Security;
use app\mvc\Controller;

class RegisterController extends Controller {

    public function __construct(){
        parent::__construct('register');
        if (Security::isUserLoggedIn()) Helper::redirectTo(WEB.DEFAULT_ROUTE);
    }

    public function indexAction(){
        $title = "Register";
        $styles = [STYLES.'register.css'];
        $scripts = [SCRIPTS.'register.js'];
        $this->loadView(LAYOUT, 'Register/index', $title, $styles, $scripts, ['csrf_token_register' => Security::generateCSRFToken('csrf_token_register')]);
    }

    /**
     * @return void
     */
    public function createAction() {
        if ($this->isAJAX() && $this->isRequestMethod('POST')){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_register'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_username'}, FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9]{3,15}$/'))) &&
                filter_var($request->{'_password'},  FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9]{6,20}$/')))){
                $status = 400;
                $data = array("error" => 'bad_request');
                $csrf_token_register = htmlspecialchars($request->{'_csrf_token_register'}, ENT_QUOTES);
                if ($csrf_token_register == hash('sha256', Security::getCSRFToken('csrf_token_register'))){
                    $username = htmlspecialchars($request->{'_username'} , ENT_QUOTES);
                    $password = htmlspecialchars($request->{'_password'}, ENT_QUOTES);
                    $user = $this->loadModel('User');
                    $user->Username = $username;
                    $user->Password = $password;
                    $status = 409;
                    $data = array('error' => 'username_is_taken');
                    if (!$user->isUsernameTaken()){
                        $id = $user->Save(array(
                                'username' => $username,
                                'password' => $user->Password ));
                        if ($id > 0) {
                            $role = $this->loadModel('Role');
                            $role->Save(array(
                                    'user_id' => $id,
                                    'role_id' => 1 ));
                            $status = 201;
                            $data = array('id' => $id);
                        }
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        } else {
            Helper::redirectTo(WEB.'register');
        }
    }
} 