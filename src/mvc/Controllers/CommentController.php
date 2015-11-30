<?php

use app\core\Helper;
use app\core\Security;
use app\mvc\Controller;

class CommentController extends Controller {

    public function __construct(){
        parent::__construct('comment');
    }

    public function indexAction(){
        $comments = $this->loadModel('Comment')->loadAll();
        $css = [STYLES.'comments.css'];
        $js = [SCRIPTS.'comments-public.js'];
        $this->loadView(LAYOUT,'Comments/User/index', 'Comments', $css, $js, ['comments' => $comments]);
    }

    public function crudAction(){
        if (Security::getUserRole() === 'ROLE_ADMIN'){
            $comments = $this->loadModel('Comment')->loadAll();
            $css = [STYLES.'comments.css'];
            $js = [SCRIPTS.'comment.js', SCRIPTS.'comment-crud.js'];
            $this->loadView(LAYOUT,'Comments/Admin/index', 'Comments', $css, $js, ['comments' => $comments, 'csrf_token_comment' => Security::generateCSRFToken('csrf_token_comment')]);
        } else {
            Helper::redirectTo(WEB.'comments');
        }
    }

    /**
     * @return void
     */
    public function saveAction() {
        if ($this->isAJAX() && $this->isRequestMethod('POST') && (Security::isUserLoggedIn())){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_comment'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_news_id'}, FILTER_VALIDATE_INT) &&
                filter_var($request->{'_content'}, FILTER_SANITIZE_STRING)){
                $status = 400;
                $data = array("error" => 'bad_request');
                $csrf_token_comment = htmlspecialchars($request->{'_csrf_token_comment'}, ENT_QUOTES);
                if ($csrf_token_comment == hash('sha256', Security::getCSRFToken('csrf_token_comment'))){
                    $content = htmlspecialchars($request->{'_content'} , ENT_QUOTES);
                    $news_id = htmlspecialchars($request->{'_news_id'} , ENT_QUOTES);
                    $user_id = Security::getUserId();
                    $comment = $this->loadModel('Comment');
                    $id = $comment->save(['news_id' => $news_id, 'user_id' => $user_id, 'content' => $content]);
                    if ($id > 0){
                        $status = 201;
                        $comment->Id = $id;
                        $data = $comment->getUsernameAndDate();
                    }
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }

    public function deleteAction(){
        if ($this->isAJAX() && $this->isRequestMethod('DELETE') && Security::getUserRole() === 'ROLE_ADMIN'){
            $status = 400;
            $data = array("error" => 'bad_request');
            $request = json_decode(file_get_contents('php://input'));
            if( filter_var($request->{'_csrf_token_comment'}, FILTER_SANITIZE_STRING) &&
                filter_var($request->{'_id'}, FILTER_VALIDATE_INT)){
                $csrf_token_comment = htmlspecialchars($request->{'_csrf_token_comment'}, ENT_QUOTES);
                if ($csrf_token_comment == hash('sha256', Security::getCSRFToken('csrf_token_comment'))){
                    $id = htmlspecialchars($request->{'_id'} , ENT_QUOTES);
                    $comment = $this->loadModel('Comment');
                    $comment->Id = $id;
                    if ($comment->delete() == 1) $status = 204;
                }
            }
            http_response_code($status);
            echo json_encode($data);
        }
    }
} 