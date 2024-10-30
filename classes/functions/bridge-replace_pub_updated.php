<?php 
preg_match_all('/' . $rbracket . '.*' . $lbracket . '/', $content, $matches);//get tags from content
foreach($matches[0] as $m){
	//for numbered lists
	$num_st = ""; //<ol>
	$num_en = ""; //</ol>
	if(get_option('bnpp_ordered_list')=='checked')
	{
		$num_st = "<ol>";
		$num_en = "</ol>";
	}
	//foreach get options
	$result = "";
	$shortcode = $m;
	$m = substr($m, strlen($rbracket), strlen($m) - strlen($rbracket) - strlen($lbracket) + 1);
	$options = explode(" ", $m);
	//shortcode fields
	$auth = "all";
	$subj = "all";
	$prep = "all";
	$year = "0";
	$tag = "null";
	$id = "0";
	$sort = "null";
	$btitle = "null";
	foreach($options as $opt){
		$sub = substr($opt, 0, 4);
		if($sub == 'auth'){
			$auth = substr($opt, 5);
		} else if ($sub == 'subj'){
			$subj = substr($opt, 5);
		} else if ($sub == 'prep'){
			$prep = substr($opt, 5);
		} else if ($sub == 'year'){
			$year = substr($opt, 5);
		} else if ($sub == 'tag='){
			$tag = substr($opt, 4);
		} else if ($sub == 'sort'){
			$sort = substr($opt, 5);
		} else if (substr($opt, 0, 6) == 'btitle'){
			$btitle = substr($opt, 7);
		} else if (substr($opt, 0, 2) == 'id'){
			$id = substr($opt, 3);
		}
	}
	$this->listDivision = NULL;
	$orderBy = "date";
	if($sort != "null"){
		$orderBy = $sort;
	} else {
		switch(get_option('bnpp_list_division')) {
			case "year":
				$orderBy = "year";
				break;
			case "title":
				$orderBy = "title";
				break;
			case "journal":
				$orderBy = "journal";
				break;
			default:
				$orderBy = "date";
				break;
		}
	}
	if($auth != "all"){ //author specific publications
		if($subj == "all"){
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->article_id;
				} else {
					$ids .= $a->article_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($prep == "only" ? " AND preprint IS NOT NULL" : ($prep == "ex" ? " AND preprint IS NULL": "")) . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillArticleElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_article_head') . $num_st . $result_temp . $num_en;
			}
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->conference_id;
				} else {
					$ids .= $a->conference_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillConferenceElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_conference_head') . $num_st . $result_temp . $num_en;
			}
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->book_id;
				} else {
					$ids .= $a->book_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillBookElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_book_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "mixed") {
			$result_temp = "";
			$ids_a = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids_a != ""){
					$ids_a .= ", " . $a->article_id;
				} else {
					$ids_a .= $a->article_id;
				}
			}
			$ids_c = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids_c != ""){
					$ids_c .= ", " . $a->conference_id;
				} else {
					$ids_c .= $a->conference_id;
				}
			}
			$ids_b = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids_b != ""){
					$ids_b .= ", " . $a->book_id;
				} else {
					$ids_b .= $a->book_id;
				}
			}
			foreach($wpdb->get_results("(SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'ar' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids_a . ")) 
			UNION (SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'co' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids_c . ")) 
			UNION (SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'bo' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids_b . ")) ORDER BY $orderBy " . get_option('bnpp_list_order')) as $pub)
			{
				$result_temp .= $this->fillMixedElement($pub);
			}
			if($result_temp != ""){
				$result .= $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "articles") {
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->article_id;
				} else {
					$ids .= $a->article_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($prep == "only" ? " AND preprint IS NOT NULL" : ($prep == "ex" ? " AND preprint IS NULL": "")) . ($year != "0" ? " AND year=" . $year : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillArticleElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_article_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "conferences") {
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->conference_id;
				} else {
					$ids .= $a->conference_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillConferenceElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_conference_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "books") {
			$result_temp = "";
			$ids = "";
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id IN (SELECT id FROM " . $this->dbAuthors . " WHERE slug='" . $auth . "')") as $a){
				if($ids != ""){
					$ids .= ", " . $a->book_id;
				} else {
					$ids .= $a->book_id;
				}
			}
			if($ids!=""){
				foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
				{
					$result_temp .= $this->fillBookElement($ar);
				}
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_book_head') . $num_st . $result_temp . $num_en;
			}
		}
	} else if ($id != "0") { //id specific publication
		if ($subj == "articles") {
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE id=" . $id) as $ar)
			{
				$result .= $this->fillArticleElement($ar);
			}
		} else if ($subj == "conferences") {
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE id=" . $id) as $co)
			{
				$result .= $this->fillConferenceElement($co);
			}
		} else if ($subj == "books") {
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE id=" . $id) as $bo)
			{
				$result .= $this->fillBookElement($bo);
			}
		}
	} else { //any author publications
		if($subj == "all"){
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($prep == "only" ? " AND preprint IS NOT NULL" : ($prep == "ex" ? " AND preprint IS NULL": "")) . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillArticleElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_article_head') . $num_st . $result_temp . $num_en;
			}
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillConferenceElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_conference_head') . $num_st . $result_temp . $num_en;
			}
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillBookElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_book_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "mixed") {
			$result_temp = "";
			foreach($wpdb->get_results("(SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'ar' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . ")  
			UNION (SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'co' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . ") 
			UNION (SELECT id, title, year, pages, doi, url, issn, supplementary, file_link, date, is_public, char1, char2, char3, arxiv, 'bo' as type" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $pub)
			{
				$result_temp .= $this->fillMixedElement($pub);
			}
			if($result_temp != ""){
				$result .= $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "articles") {
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbArticleAuthor . " where article_id=" . $this->dbArticles . ".id limit 1) as author" : "") . " FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL" . ($prep == "only" ? " AND preprint IS NOT NULL" : ($prep == "ex" ? " AND preprint IS NULL": "")) . ($year != "0" ? " AND year=" . $year : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillArticleElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_article_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "conferences") {
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbConferenceAuthor . " where conference_id=" . $this->dbConferences . ".id limit 1) as author" : "") . " FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($btitle != "null" ? " AND book_title='" . $btitle . "'": "") . ($tag != "null" ? " AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillConferenceElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_conference_head') . $num_st . $result_temp . $num_en;
			}
		} else if ($subj == "books") {
			$result_temp = "";
			foreach($wpdb->get_results("SELECT *" . ($orderBy=="author" ? ", (select case when author_id is null then author_name else (select last_name from wp_books_and_papers_authors where id=author_id) end from " . $this->dbBookAuthor . " where book_id=" . $this->dbBooks . ".id limit 1) as author" : "") . " FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL" . ($year != "0" ? " AND year=" . $year : "") . ($tag != "null" ? " AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id IN (SELECT id FROM " . $this->dbTags . " WHERE tag='". $tag . "'))" : "") . " ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
			{
				$result_temp .= $this->fillBookElement($ar);
			}
			if($result_temp != ""){
				$result .= get_option('bnpp_book_head') . $num_st . $result_temp . $num_en;
			}
		}
	}
	if ($result != ""){
		$content = str_replace($shortcode, $result, $content);
	}
}
?>