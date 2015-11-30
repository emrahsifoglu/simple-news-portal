<div style="margin-top: 5px; margin-left: 15px;">
    <table id="customers" class="grid">
        <tr>
            <th colspan="2">CATEGORIES</th>
        </tr>
        <tr id="category-form-controls">
            <th>
                <a id="sort-name" class="sort" href="#">TITLES</a>
                <a id="filter-name" href="#">
                    <img class="filter" src="<?=IMAGES.'filter.png'?>">
                </a>
            </th>
            <th>CONTROLS</th>
        </tr>
        <tr id="category-0">
            <td>
                <input id="category-name" name="category-name" value="" maxlength="50">
            </td>
            <td colspan="2">
                <div style="text-align: left;">
                    <a id="add-category" href="#">
                        <img class="crud" src="<?=IMAGES.'add.png'?>">
                    </a>
                </div>
            </td>
        </tr>
        <tr id="category-tr">
            <td class="category-td">[[title]]</td>
            <td>
                <div style="text-align: center;">
                    <a class="update-category" id="category-[[id]]" href="[[id]]">
                        <img class="crud" src="<?=IMAGES.'update.png'?>">
                    </a>
                    <a class="delete-category" id="category-[[id]]" href="[[id]]">
                        <img class="crud" src="<?=IMAGES.'delete.png'?>">
                    </a>
                </div>
            </td>
        </tr>
    </table>
</div>
<input type="hidden" id="controller-category" value="<?=WEB.'categories'?>">
<input type="hidden" id="csrf_token_category" name="csrf_token_category" value="<?=$data['csrf_token_category']?>">