<?php

use app\mvc\Model;

class NewsModel extends Model {

    /**
     * @return \NewsModel
     */
    public function __construct(){
        parent::__construct('News', 'news');
        $this->Id = 0;
    }

    /**
     * @access public
     * @param int|string $category
     * @param int $position
     * @param int $item_per_page
     * @return array
     */
    public function findByCategoryWithLimit($category, $position=0, $item_per_page=0){
        $select = 'news';
        $fields = ['news.id, news.title, news.description'];
        $where = (filter_var($category, FILTER_VALIDATE_INT)) ? ['loc.category_id' => $category] : [];
        if (is_numeric($category)) $select .= ' JOIN list_of_categories loc ON loc.news_id = news.id';
        $limit = (is_numeric($position) && is_numeric($item_per_page)) ? " ORDER BY news.id ASC LIMIT {$position},{$item_per_page}" : '';
        return $this->select($select, $fields, $where, $limit);
    }

    /**
     * @access public
     * @param int|string
     * @return int
     */
    public function getCountByCategory($category){
        if (is_numeric($category)){
            return $this->select('news JOIN list_of_categories loc ON loc.news_id = news.id',['COUNT(*)'], ['loc.category_id' => $category])[0][0];
        } else {
            return $this->select('news',['COUNT(*)'])[0][0];
        }
    }
    /**
     * @access public
     * @param int
     * @return array
     */
    public function loadAllWithLimit($limit) {
		$list = "(select loc.news_id as news_id,
						 loc.category_id as category_id,
						 @rn := if(@cur = loc.category_id, @rn+1, 1) as rn
				 ,       @cur := loc.category_id
				 from    list_of_categories loc
				 join    (select @rn := 0, @cur := '0') as i
				 order by loc.category_id) as list";
		$news = $this->select($list." JOIN news n ON n.id = list.news_id WHERE list.rn < $limit order by category_id", ['n.id, n.title, n.description, n.picture, n.insert_date, list.category_id as category_id']);	
		return $news;
	}
} 