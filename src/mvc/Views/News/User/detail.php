<div class="container">
    <div id="legend">
        <legend id="<?=$data['news'][0]?>" class=""><?=$data['news'][1]?></legend>
        <div style="text-align: right">
            <div style="display: inline-block; text-align: left;">
                <?php echo $data['news'][5]; ?>
            </div>
        </div>
    </div>
    <fieldset>
        <div id="picture"><img style="width: 20%; height: 20%;" src="<?=WEB.'uploads/'.$data['news'][4]?>"></div>
        <h1></h1>
        <p><?=$data['news'][3]?></p>
        <input type="hidden" id="_id" name="_id" value="<?=$data['news'][0]?>">
    </fieldset>
    <h1></h1>
    <div class="">
        <div class="well">
            <h4>What is on your mind?</h4>
            <div id="comment-input" class="input-group" style="display: <?=($data['isUserLoggedIn']) ? 'table' : 'none'?>">
                <input type="text" id="content" class="form-control input-sm chat-input" maxlength="250" placeholder="Write your message here..." />
                <span class="input-group-btn">
                    <a href="#" id="add_comment" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-comment"></span> Add Comment</a>
                </span>
            </div>
            <hr data-brackets-id="12673">
            <ul  data-brackets-id="12674" id="sortable" class="list-unstyled ui-sortable comments-ul">
                <li id="comment-0" class="comment-li" style="display: none">
                    <strong class="pull-left primary-font">[[username]]</strong>
                    <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>[[date]]</small>
                    <br>
                    <p class="ui-state-default">[[content]]</p>
                </li>
                <?php
                $comments = $data['comments'];
                foreach ($comments as $comment) { ?>
                    <li id="<?=$comment[0]?>" class="comment-li">
                        <strong class="pull-left primary-font"><?=$comment[1]?></strong>
                        <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span><?=$comment[3]?></small>
                        <br>
                        <p class="ui-state-default"><?=$comment[2]?></p>
                    </li>
                 <?php } ?>
            </ul>
        </div>
    </div>
</div>
<input type="hidden" id="csrf-token-comment" value="<?=$data['csrf_token_comment']?>">
<input type="hidden" id="isUserLoggedIn" value="<?=$data['isUserLoggedIn']?>">
<input type="hidden" id="controller-comment" value="<?=WEB.'comments'?>">