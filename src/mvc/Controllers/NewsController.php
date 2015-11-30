<?php

use app\core\Helper;
use app\core\Security;
use app\mvc\Controller;

class NewsController extends Controller {

    private $data;
    private $status;

    public function __construct(){
        parent::__construct('news');
    }

    public function indexAction(){
        Helper::redirectTo(WEB.'news/category/all');
    }

    /**
     * @access public
     * @param int|string $category
     * @param int $position
     * @param int $item_per_page
     */
    public function categoryAction($category='all', $position=0, $item_per_page=10){
       if($this->isAJAX() && $this->isRequestMethod('GET')){
            echo json_encode($this->loadModel('News')->findByCategoryWithLimit($category, $position, $item_per_page));
        } else if (!$this->isAJAX()){
            $css = [STYLES.'grid.css', STYLES.'news.css'];
            $js = [SCRIPTS.'news-grid.js', SCRIPTS.'news-public.js'];
            $news_count = $this->getNewsCount($category);
            $pages_count = ceil($news_count / $item_per_page);
            $this->loadView(LAYOUT,'News/User/index', 'News', $css, $js, ['news_count' => $news_count, 'pages_count' => $pages_count, 'item_per_page' => $item_per_page, 'category' => $category]);
        }
    }

    /**
     * @access public
     * @param int $news_id
     * @param int $category_id
     * @param int $add_remove
     */
    public function categoryUpdateAction($news_id, $category_id, $add_remove){
        $status = 400;
        $data = array("error" => 'bad_request');
        if ($this->isAJAX() && ($this->isRequestMethod('POST'))) {
            if (is_numeric($news_id) && is_numeric($category_id) && is_numeric($add_remove)){
                $loc = $this->loadModel('LOC');
                if ($add_remove == 1){
                    $loc->Id = 0;
                    $id = $loc->save(['category_id' => $category_id, 'news_id' => $news_id]);
                    if ($id > 0) {
                        $status = 201;
                        $data = array('id' => $id);
                    }
                } else if ($add_remove == 0) {
                    if ($loc->delete(['category_id' => $category_id, 'news_id' => $news_id]) == 1) {
                        $status = 204;
                    } else {
                        $data = array("error" => 'remove_is_failed');
                    }
                }
            }
        }
        http_response_code($status);
        echo json_encode($data);
    }

    public function crudAction(){ // multi select!
        if (!$this->isAJAX()){
            if (Security::getUserRole() === 'ROLE_ADMIN'){
                $categories = $this->loadModel('Category')->findAll();
                $item_per_page = 10;
                $news_count = $this->getNewsCount('all');
                $pages_count = ceil($news_count / $item_per_page);
                $css = [STYLES.'grid.css',STYLES.'file-browser-btn.css', STYLES.'news.css'];
                $js = [BOWER.'jquery-form/jquery.form.js', SCRIPTS.'file-validator.js', SCRIPTS.'file-browser-btn.js', SCRIPTS.'news-grid.js', SCRIPTS.'news-crud.js'];
                $this->loadView(LAYOUT,'News/Admin/index', 'News', $css, $js, ['csrf_token_news'=>Security::generateCSRFToken('csrf_token_news'), 'news_count' => $news_count, 'pages_count' => $pages_count, 'item_per_page' => $item_per_page, 'category' => 'all', 'categories' => $categories]);
            } else {
                Helper::redirectTo(WEB.'categories');
            }
        }

    }

    /**
     * @access public
     * @param int
     */
    public function readAction($id){
        if (filter_var($id, FILTER_VALIDATE_INT)){
            if($this->isAJAX()){
                if ($this->isRequestMethod('GET')){
                    $news = $this->read($id);
                    if (sizeof($this->read($id)) == 1){
                        $categories = $this->loadModel('LOC')->findByNewsId($id);
                        http_response_code(200);
                        echo json_encode(array('news' => $news, 'categories' => $categories));
                    } else {
                        http_response_code(204);
                    }
                }
            } else {
                $news = $this->read($id);
                if (sizeof($this->read($id)) == 1){
                    $comments = $this->loadModel('Comment')->findByNewsId($id);
                    $css = ['news.css'];
                    $js = [SCRIPTS.'comment.js', SCRIPTS.'comments.js'];
                    $this->loadView(LAYOUT,'News/User/detail', 'News', $css, $js, ['news' => $news[0], 'comments' => $comments, 'isUserLoggedIn' => Security::isUserLoggedIn(), 'csrf_token_comment' => Security::generateCSRFToken('csrf_token_comment')]);
                } else {
                    Helper::redirectTo(WEB.'news/category/all');
                }
            }
        }
    }

    public function saveAction(){
        if ($this->isAJAX() && ($this->isRequestMethod('POST'))) {
            $this->status = 400;
            $this->data = array("error" => 'bad_request');
            if (filter_has_var(INPUT_POST, '_csrf_token_news')){
                $csrf_token_news = htmlspecialchars($_POST['_csrf_token_news'], ENT_QUOTES);
                if ($csrf_token_news == hash('sha256', Security::getCSRFToken('csrf_token_news'))){
                    if (filter_has_var(INPUT_POST, '_id')){
                        if (is_numeric($_POST['_id'])){
                            $id = $_POST['_id'];
                            if ($id == 0){
                                $this->create();
                            } else {
                                $this->update($id);
                            }
                        }
                    }
                }
            }
            http_response_code($this->status);
            echo json_encode($this->data);
        } else if (!$this->isAJAX()){
            Helper::redirectTo(WEB.'news/category');
        }
    }

    /**
     * @access public
     * @param int
     */
    public function deleteAction($id){
        if ($this->isAJAX() && ($this->isRequestMethod('DELETE'))) {
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_news'}, FILTER_SANITIZE_STRING)){
                $csrf_token_news = htmlspecialchars($request->{'_csrf_token_news'}, ENT_QUOTES);
                if ($csrf_token_news == hash('sha256', Security::getCSRFToken('csrf_token_news'))){
                    if ((is_numeric($id))) $status = ($this->delete($id) == 1) ? 204 : 400;
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }

    public function getNewsPageCountAction($category, $item_per_page){
        if ($this->isAJAX() && ($this->isRequestMethod('GET'))) {
            echo json_encode($this->getNewsPagesCount($category, $item_per_page));
        }
    }

    public function getNewsCountAction($category){
        if ($this->isAJAX() && ($this->isRequestMethod('GET'))) {
            echo json_encode($this->getNewsCount($category));
        }
    }

    private function getNewsPagesCount($category, $item_per_page){
        return ceil($this->getNewsCount($category) / $item_per_page);
    }

    /**
     * @access private
     * @param int|string $category
     * @return int
     */
    private function getNewsCount($category){
        return $this->loadModel('News')->getCountByCategory($category);
    }

    /**
     * @access private
     * @param int $id
     */
    private function read($id){
        $news_model = $this->loadModel('News');
        $news_model->Id = $id;
        return $news_model->read();
    }

    private function create(){
        if (!empty($_FILES) && filter_has_var(INPUT_POST, '_title') && filter_has_var(INPUT_POST, '_description') && filter_has_var(INPUT_POST, '_content')){
            $isFileValid = $this->isFileValid('image_file', WEB_PATH.'uploads/');
            if ($isFileValid === true){
                $picture = $this->fileUpload('image_file', WEB_PATH.'uploads/');
                if ($picture !== false){
                    $title = htmlspecialchars($_POST['_title']);
                    $description = htmlspecialchars($_POST['_description']);
                    $content = htmlspecialchars($_POST['_content']);
                    $id = $this->loadModel('News')->save(['title' => $title, 'description' => $description, 'content' => $content, 'picture' => $picture]);
                    if ($id > 0) {
                        $this->status = 201;
                        $this->data = array('id' => $id);
                    }
                }
            } else {
                $this->data = array("error" => $isFileValid);
            }
        } else {
            $this->data = array("error" => 'Missing field(s).');
        }
    }

    /**
     * @access private
     * @param int $id
     */
    private function update($id){
        $field_values = [];
        $new_model = $this->loadModel('News');
        $new_model->Id = $id;

        if (filter_has_var(INPUT_POST, '_title')) $field_values['title'] = $_POST['_title'];
        if (filter_has_var(INPUT_POST, '_description')) $field_values['description'] = $_POST['_description'];
        if (filter_has_var(INPUT_POST, '_content')) $field_values['content'] = $_POST['_content'];

        if (!empty($_FILES)){
            $isFileValid = $this->isFileValid('image_file', WEB_PATH.'uploads/');
            if ($isFileValid === true){
                $picture = $this->fileUpload('image_file', WEB_PATH.'uploads/');
                if ($picture !== false){
                    $field_values['picture'] = $picture;
                    $this->status = 200;
                    $current_picture = $new_model->read(['picture'])[0][0];
                    if (!empty($current_picture)) unlink( WEB_PATH.'uploads/'.$current_picture);
                    $this->data = array('status' => 'update', 'id' => $new_model->save($field_values));
                }
            } else{
                $this->data = array("error" => $isFileValid);
            }
        } else{
            $this->status = 200;
            $this->data = array('status' => 'update', 'id' => $new_model->save($field_values));
        }
    }

    /**
     * @access private
     * @param int $id
     */
    private function delete($id){
        $new_model = $this->loadModel('News');
        $new_model->Id = $id;
        $current_picture = $new_model->read(['picture'])[0][0];
        if (!empty($current_picture)) unlink( WEB_PATH.'uploads/'.$current_picture);
        return $new_model->delete();
    }

    /**
     * @access private
     * @param string $file
     * @param string $target_dir;
     * @return string $return
     */
    private function fileUpload($file, $target_dir){
        $target_file = $target_dir . basename($_FILES[$file]["name"]);
        return (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) ? basename($_FILES[$file]["name"]) : false;
    }

    /**
     * @access private
     * @param string $file
     * @param string $target_dir
     * @return string|bool $return
     */
    private function isFileValid($file, $target_dir){
        $target_file = $target_dir . basename($_FILES[$file]["name"]);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $check = getimagesize($_FILES[$file]["tmp_name"]);
        $return = true;
        if($check !== false) {
            //'File is an image - ' . $check['mime'] . '.';
            if (file_exists($target_file)) {
                $this->status = 409;
                $return = 'Sorry, file already exists.';
            } else {
                if ($_FILES[$file]["size"] > 1024*1024) {
                    $return = 'Sorry, your file is too large.';
                } else {
                    if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif' ) {
                        $return = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    }
                }
            }
        } else {
            $return = 'File is not an image.';
        }
        return $return;
    }
} 