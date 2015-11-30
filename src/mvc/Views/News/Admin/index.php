<div class="container">
    <div id="legend">
        <legend class="">News</legend>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table id="grid-news" class="grid">
                <tr>
                    <th id="title" colspan="2">NEWS</th>
                </tr>
                <tr id="news-form-controls">
                    <th>
                        <a id="sort-name" class="sort" href="#">TITLES</a>
                        <a id="filter-name" href="#">
                            <img class="filter" src="<?=IMAGES.'filter.png'?>">
                        </a>
                    </th>
                    <th>CONTROLS</th>
                </tr>
                <tr id="news-0">
                    <td>Add</td>
                    <td colspan="2">
                        <div style="text-align: left;">
                            <a id="add-news" href="#">
                                <img class="crud" src="<?=IMAGES.'add.png'?>">
                            </a>
                        </div>
                    </td>
                </tr>
                <tr id="news-tr">
                    <td class="news-td">[[title]]</td>
                    <td>
                        <div style="text-align: center;">
                            <a class="read-news" id="news-[[id]]" href="[[id]]">
                                <img class="crud" src="<?=IMAGES.'update.png'?>">
                            </a>
                            <a class="delete-news" id="news-[[id]]" href="[[id]]">
                                <img class="crud" src="<?=IMAGES.'delete.png'?>">
                            </a>
                        </div>
                    </td>
                </tr>
                <tr id="paginate-tr" style="display: none;">
                    <td colspan="2">
                        <ul class="paginate" id="news"></ul>
                    </td>
                </tr>
            </table>
            <h1></h1>
        </div>
        <div class="col-md-6">
            <div>
                <form id="form-news" class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?=WEB.'news/save'?>">
                    <fieldset>
                        <div class="control-group">
                            <!-- Browse -->
                            <div id="browse">Select</div>
                            <div style="height: 0px;width: 0px; overflow:hidden;">
                                <input id="image_file" name="image_file" type="file" data-required="true" data-error-valid="File is not valid"/>
                            </div>
                        </div>

                        <div class="control-group">
                            <!-- Username -->
                            <label class="control-label" for="_title">Title</label>
                            <div class="controls">
                                <input type="text" id="_title" name="_title" placeholder="Title" class="form-control form-news-control" maxlength="50" required autofocus>
                                <p class="help-block">Title can contain any letters or numbers, without spaces between 3 and 50</p>
                            </div>
                        </div>

                        <div class="control-group">
                            <!-- Description -->
                            <label class="control-label" for="_description">Description</label>
                            <div class="controls">
                                <textarea rows="2" id="_description" name="_description" placeholder="Description" class="form-control form-news-control" required></textarea>
                                <p class="help-block">Description can contain any letters or numbers, without spaces between 3 and 250</p>
                            </div>
                        </div>

                        <div class="control-group">
                            <!-- Content -->
                            <label class="control-label" for="_description">Content</label>
                            <div class="controls">
                                <textarea rows="5" id="_content" name="_content" placeholder="Content" class="form-control form-news-control" required></textarea>
                                <p class="help-block">Content can contain any letters or numbers, without spaces between 3 and 1000</p>
                            </div>
                        </div>

                        <div class="control-group">
                            <!-- Button -->
                            <div class="controls">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                        <input type="hidden" id="_id" name="_id" value="0">
                        <input type="hidden" id="_csrf_token_news" name="_csrf_token_news" value="<?=$data['csrf_token_news']?>">
                    </fieldset>
                </form>
            </div>
            <h1></h1>
        </div>
        <div class="col-md-2">
            <table id="categories" class="grid">
                <tr id="customer-form-controls">
                    <th colspan="2">
                        <a id="sort-title" class="sort" href="#">CATEGORIES</a>
                        <a id="filter-title" href="#">
                            <img class="filter" src="<?=IMAGES.'filter.png'?>">
                        </a>
                    </th>
                </tr>
                <tr id="category-all">
                    <td colspan="2" id="all" class="category-td"><a href="all">All</a></td>
                </tr>
                <?php
                foreach ($data['categories'] as $c) {
                    echo '<tr id="category-'.$c[0].'">';
                    echo '<td id="'.$c[0].'" class="category-td"><a href="'.$c[0].'">'.$c[1].'</a></td>'.PHP_EOL;
                    echo '<td id="'.$c[0].'" class="category-td-cb"><input id="category-cb-'.$c[0].'" class="category-cb" type="checkbox" name="'.$c[0].'"></td>'.PHP_EOL;
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>
<input type="hidden" id="pages-count" value="<?=$data['pages_count']?>">
<input type="hidden" id="news-count" value="<?=$data['news_count']?>">
<input type="hidden" id="item-per-page" value="<?=$data['item_per_page']?>">
<input type="hidden" id="category" value="<?=$data['category']?>">
<input type="hidden" id="controller-news" value="<?=WEB.'news'?>">