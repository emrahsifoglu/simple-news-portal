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
        <tr id="news-0" style="display: none;"></tr>
        <tr id="news-tr" style="display: none;">
            <td class="news-td"><a href="[[read]]" target="_blank">[[title]]</a></td>
            <td class="news-td">[[description]]</td>
        </tr>
        <tr id="paginate-tr" style="display: none;">
            <td colspan="2">
                <ul class="paginate" id="news"></ul>
            </td>
        </tr>
    </table>
</div>
<input type="hidden" id="pages-count" value="<?=$data['pages_count']?>">
<input type="hidden" id="news-count" value="<?=$data['news_count']?>">
<input type="hidden" id="item-per-page" value="<?=$data['item_per_page']?>">
<input type="hidden" id="category" value="<?=$data['category']?>">
<input type="hidden" id="controller-news" value="<?=WEB.'news'?>">