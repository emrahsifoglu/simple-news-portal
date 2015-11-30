<div style="margin-top: 5px; margin-left: 15px;">
    <table id="categories" class="grid">
       <tr id="customer-form-controls">
            <th>
                <a id="sort-title" class="sort" href="#">CATEGORIES</a>
                <a id="filter-title" href="#">
                    <img class="filter" src="<?=IMAGES.'filter.png'?>">
                </a>
            </th>
        </tr>
        <?php
            foreach ($data['categories'] as $c) {
                echo '<tr id="category-tr">';
                echo '<td id="'.$c[0].'" class="category-td"><a href="'.WEB.'news/category/'.$c[0].'">'.$c[1].'</a></td>'.PHP_EOL;
                echo '</tr>';
            }
        ?>
    </table>
</div>
<input type="hidden" id="controller-category" value="<?=WEB.'categories'?>">