<?php

use app\core\Helper;
use app\core\Security;
use app\mvc\Controller;

class CategoryController extends Controller {

    public function __construct(){
        parent::__construct('category');
    }

    public function indexAction(){
        $categories = $this->fetch();
        $css = [STYLES.'grid.css', STYLES.'categories.css'];
        $js = [SCRIPTS.'categories.js'];
        $this->loadView(LAYOUT,'Category/User/index', 'Categories', $css, $js, ['categories' => $categories]);
    }

    public function crudAction(){
        if (Security::getUserRole() === 'ROLE_ADMIN'){
            $css = [STYLES.'grid.css'];
            $js = [SCRIPTS.'category.js', SCRIPTS.'category-crud.js', SCRIPTS.'categories.js'];
            $this->loadView(LAYOUT,'Category/Admin/index', 'Categories', $css, $js, ['csrf_token_category' => Security::generateCSRFToken('csrf_token_category')]);
        } else {
            Helper::redirectTo(WEB.'categories');
        }
    }

    /**
     * @return void
     */
    public function createAction() {
        if ($this->isAJAX() && $this->isRequestMethod('POST') && Security::getUserRole() === 'ROLE_ADMIN'){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_category'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_title'}, FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9_.öçşiğüÖÇŞİĞÜ-]{3,50}$/')))){
                $status = 400;
                $data = array("error" => 'bad_request');
                $csrf_token_category = htmlspecialchars($request->{'_csrf_token_category'}, ENT_QUOTES);
                if ($csrf_token_category == hash('sha256', Security::getCSRFToken('csrf_token_category'))){
                    $title = htmlspecialchars($request->{'_title'} , ENT_QUOTES);
                    $category = $this->loadModel('Category');
                    if(sizeof($category->find(['title'], ['title'=>$title])) == 0){
                        $id = $category->save(array('title' => $title));
                        if ($id > 0){
                            $status = 201;
                            $data = array('id' => $id);
                        }
                    } else {
                        $status = 409;
                        $data = array("error" => 'is_taken');
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }

    public function fetchAction(){
        if ($this->isAJAX() && $this->isRequestMethod('GET')){
            $categories = $this->fetch();
            echo json_encode($categories);
        }
    }

    public function deleteAction(){
        if ($this->isAJAX() && $this->isRequestMethod('DELETE') && Security::getUserRole() === 'ROLE_ADMIN'){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_category'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_id'}, FILTER_VALIDATE_INT)){
                $csrf_token_category = htmlspecialchars($request->{'_csrf_token_category'}, ENT_QUOTES);
                if ($csrf_token_category == hash('sha256', Security::getCSRFToken('csrf_token_category'))){
                    $id = htmlspecialchars($request->{'_id'} , ENT_QUOTES);
                    $category = $this->loadModel('Category');
                    $category->Id = $id;
                    if ($category->delete() == 1) $status = 204;
                } else {
                    $data = array("error" => 'invalid_token');
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }

    public function updateAction(){
        if ($this->isAJAX() && $this->isRequestMethod('PUT') && Security::getUserRole() === 'ROLE_ADMIN'){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_category'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_id'}, FILTER_VALIDATE_INT) &&
                filter_var($request->{'_title'}, FILTER_VALIDATE_REGEXP,
                    array("options"=>array("regexp"=>'/^[a-zA-Z0-9_.öçşiğüÖÇŞİĞÜ-]{3,50}$/')))){
                $status = 400;
                $data = array("error" => 'bad_request');
                $csrf_token_category = htmlspecialchars($request->{'_csrf_token_category'}, ENT_QUOTES);
                if ($csrf_token_category == hash('sha256', Security::getCSRFToken('csrf_token_category'))){
                    $id = htmlspecialchars($request->{'_id'} , ENT_QUOTES);
                    $title = htmlspecialchars($request->{'_title'} , ENT_QUOTES);
                    $category = $this->loadModel('Category');
                    $category->Id = $id;
                    $status = 200;
                    $data = array('id' => $category->save(array('title' => $title)));
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }

    private function fetch(){
        $category = $this->loadModel('Category');
        return $category->findAll();
    }
} 