<?php if (!get_option('bnpp_comp_perf_default')) {
	//options by year
	try{
		foreach($wpdb->get_results("SELECT year FROM " . $this->dbArticles . " UNION SELECT year FROM " . $this->dbConferences . " UNION SELECT year FROM " . $this->dbBooks) as $temp_year) {
			$orderBy = "date";
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
			$this->listDivision = NULL;
			$a_head = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " AND preprint IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles with preprint
				$a_list = "";
				foreach($articles as $ar) //fills article data
				{
					$a_list .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$a_list_ex = "";
			try {
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " AND preprint IS NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles without preprint
				foreach($articles as $ar) //fills article data
				{
					$a_list_ex .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			if ($a_list != "" || $a_list_ex != "")
			{
				$a_head = get_option('bnpp_article_head');
			}
			$a_list_all = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query all articles
				foreach($articles as $ar) //fills article data
				{
					$a_list_all .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$this->listDivision = NULL;
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					$orderBy = "year";
					break;
				case "title":
					$orderBy = "title";
					break;
				case "book_title":
					$orderBy = "book_title";
					break;
				default:
					$orderBy = "date";
					break;
			}
			try {
				$conferences = $wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query conferences
				$c_list = "";
				foreach($conferences as $co) //fills conference data
				{
					$c_list .= $this->fillConferenceElement($co);
				}
			} catch (Exception $e) {}
			if ($c_list != "")
			{
				$c_list = get_option('bnpp_conference_head') . $c_list;
			}
			$this->listDivision = NULL;
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					$orderBy = "year";
					break;
				case "title":
					$orderBy = "title";
					break;
				case "publisher":
					$orderBy = "publisher";
					break;
				default:
					$orderBy = "date";
					break;
			}
			try {
				$books = $wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query books
				$b_list = "";
				foreach($books as $bo) //fills book data
				{
					$b_list .= $this->fillBookElement($bo);
				}
			} catch (Exception $e) {}
			if ($b_list != "")
			{
				$b_list = get_option('bnpp_book_head') . $b_list;
			}
			//checks if list should be numbered
			$num_st = ""; //<ol>
			$num_en = ""; //</ol>
			if(get_option('bnpp_ordered_list')=='checked')
			{
				$num_st = "<ol>";
				$num_en = "</ol>";
			}
			//publications auth="all" subj="all" prep="all"
			if($a_list_all != "" || $c_list != "" || $b_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st .  $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/\[publications auth=.*all.*subj=.*all.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no publications from this year",$content);
			}
			//publications auth="all" subj="all" prep="only"
			if($a_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				if($c_list != "" || $b_list != "")
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/',$num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
				} else 
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/',"There are no publications from this year",$content);
				}
			}
			//publications auth="all" subj="all" prep="ex"
			if($a_list_ex!="")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				if($c_list != "" || $b_list != "")
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
				} else 
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/',"There are no publications from this year",$content);
				}
			}
			//publications auth="all" subj="articles" prep="all"
			if($a_list_all != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no articles from this year",$content);
			}
			//publications auth="all" subj="articles" prep="only"
			if($a_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no articles from this year",$content);
			}
			//publications auth="all" subj="articles" prep="ex"
			if($a_list_ex!="")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no articles from this year",$content);
			}
			//publications auth="all" subj="conferences"
			if($c_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*conferences.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*conferences.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no conferences from this year",$content);
			}
			//publications auth="all" subj="books"
			if($b_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*books.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*books.*year=' . $temp_year->year . '.*' . $lbracket . '/', "There are no books from this year",$content);
			}
			if (!get_option('bnpp_comp_perf_auth')){
				foreach($wpdb->get_results("SELECT * FROM " . $this->dbAuthors) as $au)
				{
					if(!is_null($au->slug))
					{
						$a_head = "";
						$a_list_ex = "";
						$a_list = "";
						$a_list_all = "";
						$b_list = "";
						$c_list = "";
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
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
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id=%d", $au->id)) as $a)
						{
							if($ids!="")
							{
								$ids.=", " . $a->article_id;
							} else
							{
								$ids.=$a->article_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NOT NULL AND year=" . $temp_year->year . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list .= $this->fillArticleElement($ar);
							}
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NULL AND year=" . $temp_year->year . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list_ex .= $this->fillArticleElement($ar);
							}
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list_all .= $this->fillArticleElement($ar);
							}
						}
						if ($a_list != "" || $a_list_ex != "")
						{
							$a_head = get_option('bnpp_article_head');
						}
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
							case "year":
								$orderBy = "year";
								break;
							case "title":
								$orderBy = "title";
								break;
							case "book_title":
								$orderBy = "book_title";
								break;
							default:
								$orderBy = "date";
								break;
						}
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id=%d", $au->id)) as $c)
						{
							if($ids!="")
							{
								$ids.=", " . $c->conference_id;
							} else
							{
								$ids.=$c->conference_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $co)
							{
								$c_list .= $this->fillConferenceElement($co);
							}
						}
						if ($c_list != "")
						{
							$c_list = get_option('bnpp_conference_head') . $c_list;
						}
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
							case "year":
								$orderBy = "year";
								break;
							case "title":
								$orderBy = "title";
								break;
							case "publisher":
								$orderBy = "publisher";
								break;
							default:
								$orderBy = "date";
								break;
						}
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id=%d", $au->id)) as $b)
						{
							if($ids!="")
							{
								$ids.=", " . $b->book_id;
							} else
							{
								$ids.=$b->book_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND year=" . $temp_year->year . " AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $bo)
							{
								$b_list .= $this->fillBookElement($bo);
							}
						}
						if ($b_list != "")
						{
							$b_list = get_option('bnpp_book_head') . $b_list;
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						if($a_list != "")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						}
						if($a_list_ex!="")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*all.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
						if($a_list != "")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*year=' . $temp_year->year . '.*' . $lbracket . '/', "",$content);
						}
						if($a_list_ex!="")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*year=' . $temp_year->year . '.*' . $lbracket . '/', "",$content);
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*conferences.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*books.*year=' . $temp_year->year . '.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
					}
				}}
		}
		$content = preg_replace('/' . $rbracket . ' .*year=.*' . $lbracket . '/', "There are no publications for this year.",$content);
	} catch (Exception $e) {}
	//options by tag
	try{
		foreach($wpdb->get_results("SELECT * FROM " . $this->dbTags) as $temp_tag)
		{
			$orderBy = "date";
			switch(get_option('bnpp_list_division'))
			{
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
			$this->listDivision = NULL;
			$a_head = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") AND preprint IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles with preprint
				$a_list = "";
				foreach($articles as $ar) //fills article data
				{
					$a_list .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$a_list_ex = "";
			try {
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") AND preprint IS NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles without preprint
				foreach($articles as $ar) //fills article data
				{
					$a_list_ex .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			if ($a_list != "" || $a_list_ex != "")
			{
				$a_head = get_option('bnpp_article_head');
			}
			$a_list_all = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") ORDER BY $orderBy " . get_option('bnpp_list_order')); //query all articles
				foreach($articles as $ar) //fills article data
				{
					$a_list_all .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$this->listDivision = NULL;
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					$orderBy = "year";
					break;
				case "title":
					$orderBy = "title";
					break;
				case "book_title":
					$orderBy = "book_title";
					break;
				default:
					$orderBy = "date";
					break;
			}
			try {
				$conferences = $wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id=" . $temp_tag->id . ") ORDER BY $orderBy " . get_option('bnpp_list_order')); //query conferences
				$c_list = "";
				foreach($conferences as $co) //fills conference data
				{
					$c_list .= $this->fillConferenceElement($co);
				}
			} catch (Exception $e) {}
			if ($c_list != "")
			{
				$c_list = get_option('bnpp_conference_head') . $c_list;
			}
			$this->listDivision = NULL;
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					$orderBy = "year";
					break;
				case "title":
					$orderBy = "title";
					break;
				case "publisher":
					$orderBy = "publisher";
					break;
				default:
					$orderBy = "date";
					break;
			}
			try {
				$books = $wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id=" . $temp_tag->id . ") ORDER BY $orderBy " . get_option('bnpp_list_order')); //query books
				$b_list = "";
				foreach($books as $bo) //fills book data
				{
					$b_list .= $this->fillBookElement($bo);
				}
			} catch (Exception $e) {}
			if ($b_list != "")
			{
				$b_list = get_option('bnpp_book_head') . $b_list;
			}
			//checks if list should be numbered
			$num_st = ""; //<ol>
			$num_en = ""; //</ol>
			if(get_option('bnpp_ordered_list')=='checked')
			{
				$num_st = "<ol>";
				$num_en = "</ol>";
			}
			//publications auth="all" subj="all" prep="all"
			if($a_list_all != "" || $c_list != "" || $b_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st .  $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/\[publications auth=.*all.*subj=.*all.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no publications with this tag",$content);
			}
			//publications auth="all" subj="all" prep="only"
			if($a_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				if($c_list != "" || $b_list != "")
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/',$num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
				} else 
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/',"There are no publications with this tag",$content);
				}
			}
			//publications auth="all" subj="all" prep="ex"
			if($a_list_ex!="")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
			} else
			{
				if($c_list != "" || $b_list != "")
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
				} else 
				{
					$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/',"There are no publications with this tag",$content);
				}
			}
			//publications auth="all" subj="articles" prep="all"
			if($a_list_all != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no articles with this tag",$content);
			}
			//publications auth="all" subj="articles" prep="only"
			if($a_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no articles with this tag",$content);
			}
			//publications auth="all" subj="articles" prep="ex"
			if($a_list_ex!="")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no articles with this tag",$content);
			}
			//publications auth="all" subj="conferences"
			if($c_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*conferences.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*conferences.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no conferences with this tag",$content);
			}
			//publications auth="all" subj="books"
			if($b_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*books.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*books.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "There are no books with this tag",$content);
			}
			if (!get_option('bnpp_comp_perf_auth')){
				foreach($wpdb->get_results("SELECT * FROM " . $this->dbAuthors) as $au)
				{
					if(!is_null($au->slug))
					{
						$a_head = "";
						$a_list_ex = "";
						$a_list = "";
						$a_list_all = "";
						$b_list = "";
						$c_list = "";
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
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
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id=%d", $au->id)) as $a)
						{
							if($ids!="")
							{
								$ids.=", " . $a->article_id;
							} else
							{
								$ids.=$a->article_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NOT NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list .= $this->fillArticleElement($ar);
							}
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list_ex .= $this->fillArticleElement($ar);
							}
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id IN (SELECT article_id FROM " . $this->dbArticleTag . " WHERE tag_id=" . $temp_tag->id . ") AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
							{
								$a_list_all .= $this->fillArticleElement($ar);
							}
						}
						if ($a_list != "" || $a_list_ex != "")
						{
							$a_head = get_option('bnpp_article_head');
						}
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
							case "year":
								$orderBy = "year";
								break;
							case "title":
								$orderBy = "title";
								break;
							case "book_title":
								$orderBy = "book_title";
								break;
							default:
								$orderBy = "date";
								break;
						}
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id=%d", $au->id)) as $c)
						{
							if($ids!="")
							{
								$ids.=", " . $c->conference_id;
							} else
							{
								$ids.=$c->conference_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND id IN (SELECT conference_id FROM " . $this->dbConferenceTag . " WHERE tag_id=" . $temp_tag->id . ") AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $co)
							{
								$c_list .= $this->fillConferenceElement($co);
							}
						}
						if ($c_list != "")
						{
							$c_list = get_option('bnpp_conference_head') . $c_list;
						}
						$this->listDivision = NULL;
						switch(get_option('bnpp_list_division'))
						{
							case "year":
								$orderBy = "year";
								break;
							case "title":
								$orderBy = "title";
								break;
							case "publisher":
								$orderBy = "publisher";
								break;
							default:
								$orderBy = "date";
								break;
						}
						$ids = "";
						foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id=%d", $au->id)) as $b)
						{
							if($ids!="")
							{
								$ids.=", " . $b->book_id;
							} else
							{
								$ids.=$b->book_id;
							}
						}
						if($ids!=""){
							foreach($wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND id IN (SELECT book_id FROM " . $this->dbBookTag . " WHERE tag_id=" . $temp_tag->id . ") AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $bo)
							{
								$b_list .= $this->fillBookElement($bo);
							}
						}
						if ($b_list != "")
						{
							$b_list = get_option('bnpp_book_head') . $b_list;
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						if($a_list != "")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						}
						if($a_list_ex!="")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*all.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
						if($a_list != "")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "",$content);
						}
						if($a_list_ex!="")
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
						} else
						{
							$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', "",$content);
						}
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*conferences.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*books.*tag=' . $temp_tag->tag . '.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
					}
				}}
		}
		$content = preg_replace('/' . $rbracket . ' .*tag=.*' . $lbracket . '/', "There are no publications with this tag.",$content);
	} catch (Exception $e) {}

	//options by id
	try{
		//articles
		foreach($wpdb->get_results("SELECT id FROM " . $this->dbArticles) as $temp_ids)
		{
			$orderBy = "date";
			switch(get_option('bnpp_list_division'))
			{
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
			$this->listDivision = NULL;
			$a_head = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id=" . $temp_ids->id . " AND preprint IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles with preprint
				$a_list = "";
				foreach($articles as $ar) //fills article data
				{
					$a_list .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$a_list_ex = "";
			try {
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id=" . $temp_ids->id . " AND preprint IS NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles without preprint
				foreach($articles as $ar) //fills article data
				{
					$a_list_ex .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			if ($a_list != "" || $a_list_ex != "")
			{
				$a_head = get_option('bnpp_article_head');
			}
			$a_list_all = "";
			try{
				$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND id=" . $temp_ids->id . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query all articles
				foreach($articles as $ar) //fills article data
				{
					$a_list_all .= $this->fillArticleElement($ar);
				}
			} catch (Exception $e) {}
			$this->listDivision = NULL;
			//checks if list should be numbered
			$num_st = ""; //<ol>
			$num_en = ""; //</ol>
			if(get_option('bnpp_ordered_list')=='checked')
			{
				$num_st = "<ol>";
				$num_en = "</ol>";
			}
			//publications subj="articles" prep="only"
			if($a_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*prep=.*only.*id=' . $temp_ids->id . '.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*prep=.*only.*id=' . $temp_ids->id . '.*' . $lbracket . '/', "There are no articles with this id",$content);
			}
			//publications subj="articles" prep="ex"
			if($a_list_ex!="")
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*prep=.*ex.*id=' . $temp_ids->id . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*prep=.*ex.*id=' . $temp_ids->id . '.*' . $lbracket . '/', "There are no articles with this id",$content);
			}
			//publications subj="articles" prep="all"
			if($a_list_all != "")
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*id=' . $temp_ids->id . '.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*articles.*id=' . $temp_ids->id . '.*' . $lbracket . '/', "There are no articles with this id",$content);
			}
		}
		//conferences
		foreach($wpdb->get_results("SELECT id FROM " . $this->dbConferences) as $temp_ids)
		{
			$this->listDivision = NULL;
			$orderBy = "date";
			switch(get_option('bnpp_list_division'))
			{
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
			try {
				$conferences = $wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND id=" . $temp_ids->id . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query conferences
				$c_list = "";
				foreach($conferences as $co) //fills conference data
				{
					$c_list .= $this->fillConferenceElement($co);
				}
			} catch (Exception $e) {}
			if ($c_list != "")
			{
				$c_list = get_option('bnpp_conference_head') . $c_list;
			}
			//checks if list should be numbered
			$num_st = ""; //<ol>
			$num_en = ""; //</ol>
			if(get_option('bnpp_ordered_list')=='checked')
			{
				$num_st = "<ol>";
				$num_en = "</ol>";
			}
			//publications subj="conferences"
			if($c_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*conferences.*id=' . $temp_ids->id . '.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*conferences.*id=' . $temp_ids->id . '.*' . $lbracket . '/', "There are no conferences with this id",$content);
			}
		}
		//books
		foreach($wpdb->get_results("SELECT id FROM " . $this->dbConferences) as $temp_ids)
		{
			$this->listDivision = NULL;
			$orderBy = "date";
			switch(get_option('bnpp_list_division'))
			{
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
			try {
				$books = $wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND id=" . $temp_ids->id . " ORDER BY $orderBy " . get_option('bnpp_list_order')); //query books
				$b_list = "";
				foreach($books as $bo) //fills book data
				{
					$b_list .= $this->fillBookElement($bo);
				}
			} catch (Exception $e) {}
			if ($b_list != "")
			{
				$b_list = get_option('bnpp_book_head') . $b_list;
			}
			//checks if list should be numbered
			$num_st = ""; //<ol>
			$num_en = ""; //</ol>
			if(get_option('bnpp_ordered_list')=='checked')
			{
				$num_st = "<ol>";
				$num_en = "</ol>";
			}
			//publications subj="books"
			if($b_list != "")
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*books.*id=' . $temp_ids->id . '.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
			} else
			{
				$content = preg_replace('/' . $rbracket . ' .*subj=.*books.*id=' . $temp_ids->id . '.*' . $lbracket . '/', "There are no books with this id",$content);
			}
		}
		$content = preg_replace('/' . $rbracket . ' .*id=.*' . $lbracket . '/', "There are no publications with this id.",$content);
	} catch (Exception $e) {}}

//default options
if(!get_option('bnpp_drop_db_tables'))
{
	$orderBy = "date";
	switch(get_option('bnpp_list_division'))
	{
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
	$this->listDivision = NULL;
	$a_head = "";
	try{
		$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles with preprint
		$a_list = "";
		foreach($articles as $ar) //fills article data
		{
			$a_list .= $this->fillArticleElement($ar);
		}
	} catch (Exception $e) {}
	$a_list_ex = "";
	try {
		$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query articles without preprint
		foreach($articles as $ar) //fills article data
		{
			$a_list_ex .= $this->fillArticleElement($ar);
		}
	} catch (Exception $e) {}
	if ($a_list != "" || $a_list_ex != "")
	{
		$a_head = get_option('bnpp_article_head');
	}
	$a_list_all = "";
	try{
		$articles = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query all articles
		foreach($articles as $ar) //fills article data
		{
			$a_list_all .= $this->fillArticleElement($ar);
		}
	} catch (Exception $e) {}
	$this->listDivision = NULL;
	switch(get_option('bnpp_list_division'))
	{
		case "year":
			$orderBy = "year";
			break;
		case "title":
			$orderBy = "title";
			break;
		case "book_title":
			$orderBy = "book_title";
			break;
		default:
			$orderBy = "date";
			break;
	}
	try {
		$conferences = $wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query conferences
		$c_list = "";
		foreach($conferences as $co) //fills conference data
		{
			$c_list .= $this->fillConferenceElement($co);
		}
	} catch (Exception $e) {}
	if ($c_list != "")
	{
		$c_list = get_option('bnpp_conference_head') . $c_list;
	}
	$this->listDivision = NULL;
	switch(get_option('bnpp_list_division'))
	{
		case "year":
			$orderBy = "year";
			break;
		case "title":
			$orderBy = "title";
			break;
		case "publisher":
			$orderBy = "publisher";
			break;
		default:
			$orderBy = "date";
			break;
	}
	try {
		$books = $wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL ORDER BY $orderBy " . get_option('bnpp_list_order')); //query books
		$b_list = "";
		foreach($books as $bo) //fills book data
		{
			$b_list .= $this->fillBookElement($bo);
		}
	} catch (Exception $e) {}
	if ($b_list != "")
	{
		$b_list = get_option('bnpp_book_head') . $b_list;
	}
	//checks if list should be numbered
	$num_st = ""; //<ol>
	$num_en = ""; //</ol>
	if(get_option('bnpp_ordered_list')=='checked')
	{
		$num_st = "<ol>";
		$num_en = "</ol>";
	}
	//publications auth="all" subj="all" prep="all"
	$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*all.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st .  $c_list . $num_en . $num_st . $b_list . $num_en,$content);
	//publications auth="all" subj="all" prep="only"
	if($a_list != "")
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
	} else
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*only.*' . $lbracket . '/',$num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
	}
	//publications auth="all" subj="all" prep="ex"
	if($a_list_ex!="")
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
	} else
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*all.*prep=.*ex.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
	}
	//publications auth="all" subj="articles" prep="all"
	$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*all.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $a_list . $num_en,$content);
	//publications auth="all" subj="articles" prep="only"
	if($a_list != "")
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
	} else
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*only.*' . $lbracket . '/', "",$content);
	}
	//publications auth="all" subj="articles" prep="ex"
	if($a_list_ex!="")
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
	} else
	{
		$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*articles.*prep=.*ex.*' . $lbracket . '/', "",$content);
	}
	//publications auth="all" subj="conferences"
	$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*conferences.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
	//publications auth="all" subj="books"
	$content = preg_replace('/' . $rbracket . ' auth=.*all.*subj=.*books.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
	//author specific content
	try{
		if (!get_option('bnpp_comp_perf_auth')){
			foreach($wpdb->get_results("SELECT * FROM " . $this->dbAuthors) as $au)
			{
				if(!is_null($au->slug))
				{
					$a_head = "";
					$a_list_ex = "";
					$a_list = "";
					$a_list_all = "";
					$b_list = "";
					$c_list = "";
					$this->listDivision = NULL;
					switch(get_option('bnpp_list_division'))
					{
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
					$ids = "";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id=%d", $au->id)) as $a)
					{
						if($ids!="")
						{
							$ids.=", " . $a->article_id;
						} else
						{
							$ids.=$a->article_id;
						}
					}
					if($ids!=""){
						foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NOT NULL AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
						{
							$a_list .= $this->fillArticleElement($ar);
						}
						foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND preprint IS NULL AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
						{
							$a_list_ex .= $this->fillArticleElement($ar);
						}
						foreach($wpdb->get_results("SELECT * FROM " . $this->dbArticles . " WHERE is_public IS NOT NULL AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $ar)
						{
							$a_list_all .= $this->fillArticleElement($ar);
						}
					}
					if ($a_list != "" || $a_list_ex != "")
					{
						$a_head = get_option('bnpp_article_head');
					}
					$this->listDivision = NULL;
					switch(get_option('bnpp_list_division'))
					{
						case "year":
							$orderBy = "year";
							break;
						case "title":
							$orderBy = "title";
							break;
						case "book_title":
							$orderBy = "book_title";
							break;
						default:
							$orderBy = "date";
							break;
					}
					$ids = "";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id=%d", $au->id)) as $c)
					{
						if($ids!="")
						{
							$ids.=", " . $c->conference_id;
						} else
						{
							$ids.=$c->conference_id;
						}
					}
					if($ids!=""){
						foreach($wpdb->get_results("SELECT * FROM " . $this->dbConferences . " WHERE is_public IS NOT NULL AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $co)
						{
							$c_list .= $this->fillConferenceElement($co);
						}
					}
					if ($c_list != "")
					{
						$c_list = get_option('bnpp_conference_head') . $c_list;
					}
					$this->listDivision = NULL;
					switch(get_option('bnpp_list_division'))
					{
						case "year":
							$orderBy = "year";
							break;
						case "title":
							$orderBy = "title";
							break;
						case "publisher":
							$orderBy = "publisher";
							break;
						default:
							$orderBy = "date";
							break;
					}
					$ids = "";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id=%d", $au->id)) as $b)
					{
						if($ids!="")
						{
							$ids.=", " . $b->book_id;
						} else
						{
							$ids.=$b->book_id;
						}
					}
					if($ids!=""){
						foreach($wpdb->get_results("SELECT * FROM " . $this->dbBooks . " WHERE is_public IS NOT NULL AND `id` IN (" . $ids . ") ORDER BY $orderBy " . get_option('bnpp_list_order')) as $bo)
						{
							$b_list .= $this->fillBookElement($bo);
						}
					}
					if ($b_list != "")
					{
						$b_list = get_option('bnpp_book_head') . $b_list;
					}
					$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*all.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
					if($a_list != "")
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
					} else
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*only.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
					}
					if($a_list_ex!="")
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en . $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
					} else
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*all.*prep=.*ex.*' . $lbracket . '/', $num_st . $c_list . $num_en . $num_st . $b_list . $num_en,$content);
					}
					$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*all.*' . $lbracket . '/', $num_st . $a_head . $a_list_all . $num_en,$content);
					if($a_list != "")
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*' . $lbracket . '/', $num_st . $a_head . $a_list . $num_en,$content);
					} else
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*only.*' . $lbracket . '/', "",$content);
					}
					if($a_list_ex!="")
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*' . $lbracket . '/', $num_st . $a_head . $a_list_ex . $num_en,$content);
					} else
					{
						$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*articles.*prep=.*ex.*' . $lbracket . '/', "",$content);
					}
					$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*conferences.*' . $lbracket . '/', $num_st . $c_list . $num_en,$content);
					$content = preg_replace('/' . $rbracket . ' auth=' . $au->slug . ' subj=.*books.*' . $lbracket . '/', $num_st . $b_list . $num_en,$content);
				}
			}}
	} catch (Exception $e) {}
} ?>