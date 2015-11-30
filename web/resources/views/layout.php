<?php
use app\core\Security;

    $isUserLoggedIn = Security::isUserLoggedIn();

    function isGuest($isUserLoggedIn){
        return ($isUserLoggedIn) ? 'none' : 'block';
    }

    function isAuthorized($isUserLoggedIn){
        return ($isUserLoggedIn) ? 'block' : 'none';
    }

    function isAdmin($isUserLoggedIn){
         return ($isUserLoggedIn && Security::getUserRole() === 'ROLE_ADMIN') ? 'block' : 'none';
    }

    function isTokenGenerated($isUserLoggedIn){
        return ($isUserLoggedIn) ? 0 : Security::generateCSRFToken('csrf_token_login');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?=BOWER?>bootstrap3-dialog-master/dist/css/bootstrap-dialog.min.css">
        <?php
                $css = [];
                $css[] = STYLES.'layout.css';
                if (!empty($styles)){
                    $css = array_merge($css, $styles);
                    if (isset($css) && !empty($css)){
                        foreach($css as $style) {
                            echo '<link rel="stylesheet" href="'.$style.'">'.PHP_EOL;
                        }
                    }
                }
        ?>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?=BOWER?>jquery/dist/jquery.min.js"></script>
        <script src="<?=BOWER?>bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?=BOWER?>bootstrap3-dialog-master/dist/js/bootstrap-dialog.min.js"></script>
        <?php
                $js = [];
                $js[] = SCRIPTS.'helper.js';
                $js[] = SCRIPTS.'user.js';
                $js[] = SCRIPTS.'app.js';
                if (!empty($styles)){
                    $js = array_merge($js, $scripts);
                    if (isset($js) && !empty($js)){
                        foreach($js as $script) {
                            echo '<script type="text/javascript" src="'.$script.'"></script>'.PHP_EOL;
                        }
                    }
                }
        ?>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header" style="display: ">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=WEB.'home'?>">Simple Web Portal</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="navbar-collapse collapse" id="navbar-main">
                    <ul class="nav navbar-nav navbar-left">
                        <li id="li-categories"><a href="<?=WEB.'categories'?>">Categories</a></li>
                        <li id="li-news"><a href="<?=WEB.'news/category/all'?>">News</a></li>
                        <li id="li-comments"><a href="<?=WEB.'comments'?>">Comments</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li id="li-home"><a href="<?=WEB.'home'?>">Home</a></li>
                        <li class="guest" id="li-register" style="display: <?=isGuest($isUserLoggedIn)?>"><a href="<?=WEB.'register'?>">Register</a></li>
                        <li class="dropdown admin" id="li-admin" style="display: <?=isAdmin($isUserLoggedIn)?>">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Admin <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=WEB.'categories'?>">Categories</a></li>
                                <li><a href="<?=WEB.'news/category/all'?>">News</a></li>
                                <li><a href="<?=WEB.'comments'?>">Comments</a></li>
                                <li class="divider"></li>
                                <li><a href="<?=WEB.'categories/crud'?>">Categories(CRUD)</a></li>
                                <li><a href="<?=WEB.'news/crud'?>">News(CRUD)</a></li>
                                <li><a href="<?=WEB.'comments/crud'?>">Comments(CRUD)</a></li>
                            </ul>
                        </li>
                        <li class="authorized" id="li-logout" style="display: <?=isAuthorized($isUserLoggedIn)?>"><a href="<?=WEB.'logout'?>">Logout</a></li>
                        <li class="guest" id="li-login" style="display: <?=isGuest($isUserLoggedIn)?>"><a href="#">Login</a></li>
                        <li class="guest" id="li-form" style="display: none">
                            <form id="form-login" class="navbar-form form-signin" role="form" method="post" action="<?=WEB.'login'?>">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="_username" name="_username" placeholder="Username" maxlength="15" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="_password" name="_password" placeholder="Password" maxlength="20" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <i class="icon-user icon-white"></i>Go!
                                    </button>
                                </div>
                                <input type="hidden" id="_csrf_token_login" name="_csrf_token_login" value="<?=isTokenGenerated($isUserLoggedIn)?>">
                             </form>
                        </li>
                    </ul>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <?php echo $content; ?>
        <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
            <div class="container" style="text-align: center">
                ...
            </div>
        </nav>
        <input type="hidden" id="route-logout" value="<?=WEB.'logout'?>">
        <input type="hidden" id="route-home" value="<?=WEB.'home'?>">
    </body>
</html>