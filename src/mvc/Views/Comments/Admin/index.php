<div class="container">
    <div class="">
        <div class="well">
            <h4>What is on your mind?</h4>
            <hr data-brackets-id="12673">
            <ul  data-brackets-id="12674" id="sortable" class="list-unstyled ui-sortable comments-ul">
                <?php
                $comments = $data['comments'];
                foreach ($comments as $comment) { ?>
                    <li id="<?=$comment[0]?>" class="comment-li">
                        <strong class="pull-left primary-font"><?=$comment[1]?></strong>
                        <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span><?=$comment[3]?></small>
                        <br>
                        <p class="ui-state-default"><?=$comment[2]?></p>
                        <div style="text-align: right;">
                            <a class="delete-comment" id="comment-<?=$comment[0]?>" href="#">
                                <img class="crud" src="<?=IMAGES.'delete.png'?>">
                            </a>
                        </div>
                        <br>
                        <div class="hr"></div>
                        <br>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<input type="hidden" id="controller-comments" value="<?=WEB.'comments'?>">
<input type="hidden" id="csrf_token_comment" name="csrf_token_comment" value="<?=$data['csrf_token_comment']?>">