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
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>