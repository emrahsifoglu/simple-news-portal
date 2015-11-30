<div class="container">
    <div class="jumbotron">
        <h1>Simple News Portal</h1>
    </div>
    <div style="margin-top: 5px; margin-left: 15px;">
        <table id="grid-news" class="grid">
            <tr>
                <th id="title" colspan="2">NEWS</th>
            </tr>
            <tr id="news-form-controls">
                <th>
                    <a id="sort-title" class="sort" href="#">TITLE</a>
                    <a id="filter-name" href="#">
                        <img class="filter" src="<?=IMAGES.'filter.png'?>">
                    </a>
                </th>
                <th>
                    <a id="sort-description" class="sort" href="#">DESCRIPTION</a>
                    <a id="filter-name" href="#">
                        <img class="filter" src="<?=IMAGES.'filter.png'?>">
                    </a>
                </th>
            </tr>
            <?php
            $news = $data['news'];
            foreach ($news as $n) { ?>
                <tr id="news-tr">
                    <td class="news-td"><a href="<?=WEB.'news/read/'.$n[0]?>" target="_blank"><?=$n[1]?></a></td>
                    <td class="news-td"><?=$n[2]?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>