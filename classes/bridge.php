<?php
class BNPP_Bridge //bridge class that contains dedicated functions
{
	var $dbAuthors;
	var $dbArticles;
	var $dbConferences;
	var $dbBooks;
	var $dbArticleAuthor;
	var $dbConferenceAuthor;
	var $dbBookAuthor;
	var $dbBookEditor;
	var $dbJournals;
	var $dbTags;
	var $dbArticleTag;
	var $dbConferenceTag;
	var $dbBookTag;

	var $listDivision;

	function __construct($dbAuthors, $dbArticles, $dbConferences, $dbBooks, $dbArticleAuthor, $dbConferenceAuthor, $dbBookAuthor, $dbBookEditor, $dbJournals, $dbTags, $dbArticleTag, $dbConferenceTag, $dbBookTag)
	{
		$this->dbAuthors = $dbAuthors;
		$this->dbArticles = $dbArticles;
		$this->dbConferences = $dbConferences;
		$this->dbBooks = $dbBooks;
		$this->dbArticleAuthor = $dbArticleAuthor;
		$this->dbConferenceAuthor = $dbConferenceAuthor;
		$this->dbBookAuthor = $dbBookAuthor;
		$this->dbBookEditor = $dbBookEditor;
		$this->dbJournals = $dbJournals;
		$this->dbTags = $dbTags;
		$this->dbArticleTag = $dbArticleTag;
		$this->dbConferenceTag = $dbConferenceTag;
		$this->dbBookTag = $dbBookTag;
	}

	function alterTableCharSet() //changes tables charset
	{
		global $wpdb;
		$sql = "ALTER TABLE " . $this->dbAuthors . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbAuthors . " MODIFY last_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbAuthors . " MODIFY first_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbArticles . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbArticles . " MODIFY title TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbConferences . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbConferences . " MODIFY title TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbConferences . " MODIFY book_title TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBooks . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBooks . " MODIFY title TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBooks . " MODIFY publisher TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbArticleAuthor . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbArticleAuthor . " MODIFY author_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbConferenceAuthor . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbConferenceAuthor . " MODIFY author_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBookAuthor . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBookAuthor . " MODIFY author_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBookEditor . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbBookEditor . " MODIFY editor_name TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbJournals . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
		$sql = "ALTER TABLE " . $this->dbJournals . " MODIFY journal TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
		$wpdb->query($sql);
	}

	function createAdditionalTables() //calls private functions to create additional tables
	{
		$this->CreateTableAA();
		$this->CreateTableCA();
		$this->CreateTableBA();
		$this->CreateTableBE();
		$this->CreateTableJournal();
		$this->CreateTableTags();
		$this->CreateTableTA();
		$this->CreateTableTC();
		$this->CreateTableTB();
	}

	private function CreateTableAA() //creates article-author relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbArticleAuthor'") != $this->dbArticleAuthor) 
		{
			$sql = "CREATE TABLE $this->dbArticleAuthor (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			author_id MEDIUMINT,
			author_name TINYTEXT,
			article_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}

	private function CreateTableCA() //creates conference-author relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbConferenceAuthor'") != $this->dbConferenceAuthor) 
		{
			$sql = "CREATE TABLE $this->dbConferenceAuthor (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			author_id MEDIUMINT,
			author_name TINYTEXT,
			conference_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}

	private function CreateTableBA() //creates book-author relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbBookAuthor'") != $this->dbBookAuthor) 
		{
			$sql = "CREATE TABLE $this->dbBookAuthor (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			author_id MEDIUMINT,
			author_name TINYTEXT,
			book_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}

	private function CreateTableBE() //creates book-editor relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbBookEditor'") != $this->dbBookEditor) 
		{
			$sql = "CREATE TABLE $this->dbBookEditor (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			editor_id MEDIUMINT,
			editor_name TINYTEXT,
			book_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}

	private function CreateTableJournal() //creates journal table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbJournals'") != $this->dbJournals) 
		{
			$sql = "CREATE TABLE $this->dbJournals (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			journal TINYTEXT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}
	
	private function CreateTableTags() //creates tags table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbTags'") != $this->dbTags) 
		{
			$sql = "CREATE TABLE $this->dbTags (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			tag TINYTEXT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}
	
	private function CreateTableTA() //creates tag-article relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbArticleTag'") != $this->dbArticleTag) 
		{
			$sql = "CREATE TABLE $this->dbArticleTag (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			article_id MEDIUMINT NOT NULL,
			tag_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}
	
	private function CreateTableTC() //creates tag-conference relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbConferenceTag'") != $this->dbConferenceTag) 
		{
			$sql = "CREATE TABLE $this->dbConferenceTag (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			conference_id MEDIUMINT NOT NULL,
			tag_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}
	
	private function CreateTableTB() //creates tag-book relation table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$this->dbBookTag'") != $this->dbBookTag) 
		{
			$sql = "CREATE TABLE $this->dbBookTag (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			book_id MEDIUMINT NOT NULL,
			tag_id MEDIUMINT NOT NULL,
			PRIMARY KEY (id)
			);";
			$wpdb->query($sql);
		}
	}

	function checkAuthor($author) //checks if author exists in database
	{
		global $wpdb;
		$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE (CONCAT(first_name, ' ',last_name)=%s)", $author));
		$author_id = -1;
		foreach($data as $a)
		{
			$author_id = $a->id;
		}
		if($author_id == -1)
		{
			return $author;
		} else
		{
			return $author_id;
		}
	}
	
	function checkTag($tag) //checks if tag exists in database
	{
		global $wpdb;
		$data = $wpdb->get_results($wpdb->prepare("SELECT id FROM " . $this->dbTags . " WHERE tag=%s", $tag));
		$tag_id = -1;
		foreach($data as $t)
		{
			$tag_id = $t->id;
		}
		if($tag_id == -1)
		{
			return $tag . " ";
		} else
		{
			return $tag_id;
		}
	}

	function addArticleAuthor($author, $art_id) //relates author to article
	{
		global $wpdb;
		if(is_numeric($author))
		{
			$wpdb->insert($this->dbArticleAuthor,array(
				"author_id"=>$author,
				"article_id"=>$art_id
			));
		} else 
		{
			$wpdb->insert($this->dbArticleAuthor,array(
				"author_name"=>$author,
				"article_id"=>$art_id
			));
		}
	}

	function addConferenceAuthor($author, $art_id) //relates author to conference
	{
		global $wpdb;
		if(is_numeric($author))
		{
			$wpdb->insert($this->dbConferenceAuthor,array(
				"author_id"=>$author,
				"conference_id"=>$art_id
			));
		} else 
		{
			$wpdb->insert($this->dbConferenceAuthor,array(
				"author_name"=>$author,
				"conference_id"=>$art_id
			));
		}
	}

	function addBookAuthor($author, $art_id) //relates author to book
	{
		global $wpdb;
		if(is_numeric($author))
		{
			$wpdb->insert($this->dbBookAuthor,array(
				"author_id"=>$author,
				"book_id"=>$art_id
			));
		} else 
		{
			$wpdb->insert($this->dbBookAuthor,array(
				"author_name"=>$author,
				"book_id"=>$art_id
			));
		}
	}

	function addBookEditor($editor, $art_id) //relates editor to book
	{
		global $wpdb;
		if(is_numeric($editor))
		{
			$wpdb->insert($this->dbBookEditor,array(
				"editor_id"=>$editor,
				"book_id"=>$art_id
			));
		} else 
		{
			$wpdb->insert($this->dbBookEditor,array(
				"editor_name"=>$editor,
				"book_id"=>$art_id
			));
		}
	}
	
	function addArticleTag($tag, $art_id) //relates tag to article
	{
		global $wpdb;
		if(is_numeric($tag))
		{
			$wpdb->insert($this->dbArticleTag,array(
				"tag_id"=>$tag,
				"article_id"=>$art_id
			));
		} else 
		{
			$tag = substr($tag, 0, -1);
			$wpdb->insert($this->dbTags,array(
				"tag"=>$tag
			));
			$t_id;
			$data = $wpdb->get_results("SELECT id FROM " . $this->dbTags . " ORDER By id DESC LIMIT 1");
			foreach($data as $el)
			{
				$t_id = $el->id;
			}
			$wpdb->insert($this->dbArticleTag,array(
				"tag_id"=>$t_id,
				"article_id"=>$art_id
			));
		}
	}
	
	function addConferenceTag($tag, $art_id) //relates tag to conference
	{
		global $wpdb;
		if(is_numeric($tag))
		{
			$wpdb->insert($this->dbConferenceTag,array(
				"tag_id"=>$tag,
				"conference_id"=>$art_id
			));
		} else 
		{
			$tag = substr($tag, 0, -1);
			$wpdb->insert($this->dbTags,array(
				"tag"=>$tag
			));
			$t_id;
			$data = $wpdb->get_results("SELECT id FROM " . $this->dbTags . " ORDER By id DESC LIMIT 1");
			foreach($data as $el)
			{
				$t_id = $el->id;
			}
			$wpdb->insert($this->dbConferenceTag,array(
				"tag_id"=>$t_id,
				"conference_id"=>$art_id
			));
		}
	}
	
	function addBookTag($tag, $art_id) //relates tag to book
	{
		global $wpdb;
		if(is_numeric($tag))
		{
			$wpdb->insert($this->dbBookTag,array(
				"tag_id"=>$tag,
				"book_id"=>$art_id
			));
		} else 
		{
			$tag = substr($tag, 0, -1);
			$wpdb->insert($this->dbTags,array(
				"tag"=>$tag
			));
			$t_id;
			$data = $wpdb->get_results("SELECT id FROM " . $this->dbTags . " ORDER By id DESC LIMIT 1");
			foreach($data as $el)
			{
				$t_id = $el->id;
			}
			$wpdb->insert($this->dbBookTag,array(
				"tag_id"=>$t_id,
				"book_id"=>$art_id
			));
		}
	}

	function checkJournal($journal) //checks if journal exists in database
	{
		global $wpdb;
		$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbJournals . " WHERE (journal=%s)", $journal));
		$journal_id = -1;
		foreach($data as $a)
		{
			$journal_id = $a->id;
		}
		if($journal_id == -1)
		{
			return $journal;
		} else
		{
			return $journal_id;
		}
	}

	function addArticle($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $journal, $volume, $issue, $char1, $char2, $char3, $preprint) //adds article data to table
	{
		global $wpdb;
		$article = new BNPP_Article($this->dbArticles);
		$article->title = $title;
		$article->year = $year;
		$article->pages = $pages;
		$article->doi = $doi;
		$article->url = $url;
		$article->issn = $issn;
		$article->supplementary = $supp;
		$article->fileLink = $file;
		$article->date = $date;
		$article->isPublic = $public;
		$article->preprint = $preprint;
		$article->SLaSiIsUsed = $slasi;
		$article->arxiv = $arxiv;
		if(is_numeric($journal))
		{
			$article->journal = $journal;
		} else 
		{
			$wpdb->insert($this->dbJournals,array(
				"journal"=>$journal
			));
			$j_id;
			$data = $wpdb->get_results("SELECT id FROM " . $this->dbJournals . " ORDER By id DESC LIMIT 1");
			foreach($data as $el)
			{
				$j_id = $el->id;
			}
			$article->journal = $j_id;
		}
		$article->volume = $volume;
		$article->issue = $issue;
		$article->char1 = $char1;
		$article->char2 = $char2;
		$article->char3 = $char3;
		$article->InsertArticleInfo();
	}

	function addConference($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $bookTitle, $confPages, $char1, $char2, $char3) //adds conference data to table
	{
		$article = new BNPP_Conference($this->dbConferences);
		$article->title = $title;
		$article->year = $year;
		$article->pages = $pages;
		$article->doi = $doi;
		$article->url = $url;
		$article->issn = $issn;
		$article->supplementary = $supp;
		$article->fileLink = $file;
		$article->date = $date;
		$article->isPublic = $public;
		$article->arxiv = $arxiv;
		$article->bookTitle = $bookTitle;
		$article->confPages = $confPages;
		$article->char1 = $char1;
		$article->char2 = $char2;
		$article->char3 = $char3;
		$article->InsertConferenceInfo();
	}

	function addBook($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $publisher, $chapter, $isbn, $char1, $char2, $char3) //adds book data to table
	{
		$article = new BNPP_Book($this->dbBooks);
		$article->title = $title;
		$article->year = $year;
		$article->pages = $pages;
		$article->doi = $doi;
		$article->url = $url;
		$article->issn = $issn;
		$article->supplementary = $supp;
		$article->fileLink = $file;
		$article->date = $date;
		$article->isPublic = $public;
		$article->arxiv = $arxiv;
		$article->publisher = $publisher;
		$article->chapter = $chapter;
		$article->isbn = $isbn;
		$article->char1 = $char1;
		$article->char2 = $char2;
		$article->char3 = $char3;
		$article->InsertBookInfo();
	}

	function removeArticle($answer, $articleID) //removes article data from table
	{
		global $wpdb;
		if($answer=="yes")
		{
			$sql = $wpdb->prepare("DELETE FROM $this->dbArticles WHERE (id = %d)", $articleID);
			$wpdb->query($sql);
			$timeout = get_option('bnpp_timeout_step') * 100;
			echo "<script>window.onload = function () { document.getElementById('remove').innerHTML = 'Article " . $articleID . " has been removed.';setTimeout(function() {window.location=document.location.href;},$timeout); } </script>";
		}
	}

	function removeConference($answer, $articleID) //removes conference data from table
	{
		global $wpdb;
		if($answer=="yes")
		{
			$sql = $wpdb->prepare("DELETE FROM $this->dbConferences WHERE (id = %d)", $articleID);
			$wpdb->query($sql);
			$timeout = get_option('bnpp_timeout_step') * 100;
			echo "<script>window.onload = function () { document.getElementById('remove').innerHTML = 'Conference " . $articleID . " has been removed.';setTimeout(function() {window.location=document.location.href;},$timeout); } </script>";
		}
	}

	function removeBook($answer, $articleID) //removes book data from table
	{
		global $wpdb;
		if($answer=="yes")
		{
			$sql = $wpdb->prepare("DELETE FROM $this->dbBooks WHERE (id = %d)", $articleID);
			$wpdb->query($sql);
			$timeout = get_option('bnpp_timeout_step') * 100;
			echo "<script>window.onload = function () { document.getElementById('remove').innerHTML = 'Book " . $articleID . " has been removed.';setTimeout(function() {window.location=document.location.href;},$timeout); } </script>";
		}
	}

	function deleteFile($file)
	{
		global $wpdb;
		$noRef = true; //references to file (if exist - file wont be deleted)
		$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->dbArticles WHERE (file_link = %s)", $file));
		foreach($data as $d)
		{
			$noRef = false;
		}
		$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->dbConferences WHERE (file_link = %s)", $file));
		foreach($data as $d)
		{
			$noRef = false;
		}
		$data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->dbBooks WHERE (file_link = %s)", $file));
		foreach($data as $d)
		{
			$noRef = false;
		}
		if($noRef)
		{
			$uploaddir = "";
			if (get_option('bnpp_upload_dir_abs'))
			{
				$uploaddir .= ABSPATH;
			}
			$uploaddir .= get_option('bnpp_upload_dir');
			unlink($uploaddir . '/' . $file);
		}
	}

	function manageArticle($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $journal, $volume, $issue, $articleID, $char1, $char2, $char3, $preprint) //updates article data
	{
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $this->dbArticles SET title = %s, year = %s, pages = %s, doi = %s, url = %s, issn = %s, supplementary = %s, file_link = %s, date = %s, is_public = ", array(str_replace("\\\"", "\"", $title), $year, $pages, $doi, $url, $issn, $supp, $file, $date));
		if($public != "") 
		{
			$sql.= esc_sql($public);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", preprint = ";
		if($preprint != "") 
		{
			$sql.= esc_sql($preprint);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char1 = ";
		if($char1 != "") 
		{
			$sql.= esc_sql($char1);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char2 = ";
		if($char2 != "") 
		{
			$sql.= esc_sql($char2);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char3 = ";
		if($char3 != "") 
		{
			$sql.= esc_sql($char3);
		} else
		{
			$sql.= "NULL";
		}
		$sql.= $wpdb->prepare(", arxiv = %s, journal = %s, volume = %s, issue = %s WHERE (id = %d)", array($arxiv, $journal, $volume, $issue, $articleID));
		$wpdb->query($sql);
		$timeout = get_option('bnpp_timeout_step') * 100;
		echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Article " . $articleID . " has been updated.';setTimeout(function() {window.location=document.location.href;},$timeout); }</script>";
	}

	function manageConference($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $bookTitle, $confPages, $articleID, $char1, $char2, $char3) //updates conference data
	{
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $this->dbConferences SET title = %s, year = %s, pages = %s, doi = %s, url = %s, issn = %s, supplementary = %s, file_link = %s, date = %s, is_public = ", array(str_replace("\\\"", "\"", $title), $year, $pages, $doi, $url, $issn, $supp, $file, $date));
		if($public != "") 
		{
			$sql.= esc_sql($public);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char1 = ";
		if($char1 != "") 
		{
			$sql.= esc_sql($char1);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char2 = ";
		if($char2 != "") 
		{
			$sql.= esc_sql($char2);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char3 = ";
		if($char3 != "") 
		{
			$sql.= esc_sql($char3);
		} else
		{
			$sql.= "NULL";
		}
		$sql.= $wpdb->prepare(", arxiv = %s, book_title = %s, conf_page = %s WHERE (id = %d)", array($arxiv, str_replace("\\\"", "\"", $bookTitle), $confPages, $articleID));
		$wpdb->query($sql);
		$timeout = get_option('bnpp_timeout_step') * 100;
		echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Conference " . $articleID . " has been updated.';setTimeout(function() {window.location=document.location.href;},$timeout); }</script>";
	}

	function manageBook($title, $year, $pages, $doi, $url, $issn, $supp, $file, $date, $public, $arxiv, $publisher, $chapter, $isbn, $articleID, $char1, $char2, $char3) //updates book data
	{
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $this->dbBooks SET title = %s, year = %s, pages = %s, doi = %s, url = %s, issn = %s, supplementary = %s, file_link = %s, date = %s, is_public = ", array(str_replace("\\\"", "\"", $title), $year, $pages, $doi, $url, $issn, $supp, $file, $date));
		if($public != "") 
		{
			$sql.= esc_sql($public);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char1 = ";
		if($char1 != "") 
		{
			$sql.= esc_sql($char1);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char2 = ";
		if($char2 != "") 
		{
			$sql.= esc_sql($char2);
		} else
		{
			$sql.= "NULL";
		}
		$sql.=", char3 = ";
		if($char3 != "") 
		{
			$sql.= esc_sql($char3);
		} else
		{
			$sql.= "NULL";
		}
		$sql.= $wpdb->prepare(", arxiv = %s, publisher = %s, chapter = %s, isbn = %s WHERE (id = %d)", array($arxiv, str_replace("\\\"", "\"", $publisher), $chapter, $isbn, $articleID));
		$wpdb->query($sql);
		$timeout = get_option('bnpp_timeout_step') * 100;
		echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Book " . $articleID . " has been updated.';setTimeout(function() {window.location=document.location.href;},$timeout); }</script>";
	}
	private function replaceOptionalContent($match, $el)
	{
		global $wpdb;
		$savedLine = $match; //saves original match line
		$checkMatch = true; //checks if all tags were replaced
		while(preg_match('/\[(.*?)\]/',$match,$newMatches))
		{
			if($newMatches[1]=="filelink")
				$newMatches[1]="file_link";
			if($newMatches[1]=="journal")
			{
				foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbJournals . " WHERE id=%d", $el->{$newMatches[1]})) as $j)
				{
					if ($j->journal == "")
						$checkMatch = false;
				}
			}
			if($el->{$newMatches[1]}==null || $el->{$newMatches[1]}=="" || $el->{$newMatches[1]}=="0")
				$checkMatch = false;
			$match = str_replace($newMatches[0],"",$match);
		}
		if($checkMatch)
			return $savedLine;
		else
			return "";
	}

	private function fillArticleElement($ar) //creates one article cell for a list
	{
		global $wpdb;//$a_elem = str_replace('\&quot;',"",str_replace('"',"&quot;",$a_elem));
		$a_elem = "<span style='";
		if(get_option("bnpp_lstyle_value") != "") { $a_elem .= get_option("bnpp_lstyle_value"); }
		if(get_option("bnpp_custom_char1_name") != "" && !is_null($ar->char1)) { $a_elem .= get_option("bnpp_custom_char1_value"); }
		if(get_option("bnpp_custom_char2_name") != "" && !is_null($ar->char2)) { $a_elem .= get_option("bnpp_custom_char2_value"); }
		if(get_option("bnpp_custom_char3_name") != "" && !is_null($ar->char3)) { $a_elem .= get_option("bnpp_custom_char3_value"); }
		$a_elem .= "'>" . get_option('bnpp_article_list') . "</span><br>";
		while(preg_match('/{{(.*?)}}/',$a_elem,$matches))
		{
			$a_elem = str_replace($matches[0],$this->replaceOptionalContent($matches[1],$ar),$a_elem);
		}
		$a_elem = preg_replace('/\[title\]/',$ar->title,$a_elem);
		$a_elem = preg_replace('/\[year\]/',$ar->year,$a_elem);
		$a_elem = preg_replace('/\[pages\]/',$ar->pages,$a_elem);
		$a_elem = preg_replace('/\[doi\]/',$ar->doi,$a_elem);
		$a_elem = preg_replace('/\[url\]/',$ar->url,$a_elem);
		$a_elem = preg_replace('/\[issn\]/',$ar->issn,$a_elem);
		$a_elem = preg_replace('/\[supplementary\]/',$ar->supplementary,$a_elem);
		$a_elem = preg_replace('/\[filelink\]/',$ar->file_link,$a_elem);
		$a_elem = preg_replace('/\[date\]/',$ar->date,$a_elem);
		if($ar->volume != 0)
			$a_elem = preg_replace('/\[volume\]/',$ar->volume,$a_elem);
		else
			$a_elem = preg_replace('/\[volume\]/','',$a_elem);
		if($ar->issue != 0)
			$a_elem = preg_replace('/\[issue\]/',$ar->issue,$a_elem);
		else
			$a_elem = preg_replace('/\[issue\]/','',$a_elem);
		$a_elem = preg_replace('/\[arxiv\]/',$ar->arxiv,$a_elem);
		$a_elem_authors = "";
		try {
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbArticleAuthor . " WHERE article_id=%d", $ar->id)) as $a)
			{
				if(!is_null($a->author_id))
				{
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE id=%d", $a->author_id)) as $au)
					{
						$a_elem_authors .= "$au->first_name  $au->last_name, ";
					}
				} else {
					$a_elem_authors .= "$a->author_name, ";
				}
			}
			$a_elem_authors = substr($a_elem_authors, 0, -2);
			$a_elem = preg_replace('/\[authors\]/',$a_elem_authors,$a_elem);
			$a_elem_tags = "";
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbArticleTag . " WHERE article_id=%d", $ar->id)) as $a)
			{
				foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbTags . " WHERE id=%d", $a->tag_id)) as $au)
				{
					$a_elem_tags .= "$au->tag; ";
				}
			}
			$a_elem_tags = substr($a_elem_tags, 0, -2);
			$a_elem = preg_replace('/\[tags\]/',$a_elem_tags,$a_elem);
			$a_elem_journal = "";
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbJournals . " WHERE id=%d", $ar->journal)) as $j)
			{
				$a_elem_journal .= $j->journal;
			}
		} catch (Exception $e) {}
		$a_elem = preg_replace('/\[journal\]/',$a_elem_journal,$a_elem);
		$a_elem = str_replace('&quot;','',$a_elem);
		//$a_elem = str_replace('\&quot;',"",str_replace('"',"&quot;",$a_elem));
		if(get_option('bnpp_ordered_list')=='checked')
		{
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $ar->year)
					{
						$this->listDivision = $ar->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$ar->year</h3><li>" . $a_elem . "</li>";
					} else {
						return "<li>" . $a_elem . "</li>";
					}
				case "title":
					if($this->listDivision != $ar->title)
					{
						$this->listDivision = $ar->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$ar->title</h3><li>" . $a_elem . "</li>";
					} else {
						return "<li>" . $a_elem . "</li>";
					}
				case "journal":
					if($this->listDivision != $ar->journal)
					{
						$this->listDivision = $ar->journal;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$a_elem_journal</h3><li>" . $a_elem . "</li>";
					}
				default:
					return "<li>" . $a_elem . "</li>";
					break;
			}
		} else {
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $ar->year)
					{
						$this->listDivision = $ar->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$ar->year</h3>" . $a_elem;
					} else {
						return $a_elem;
					}
				case "title":
					if($this->listDivision != $ar->title)
					{
						$this->listDivision = $ar->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$ar->title</h3>" . $a_elem;
					} else {
						return $a_elem;
					}
				case "journal":
					if($this->listDivision != $ar->journal)
					{
						$this->listDivision = $ar->journal;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$a_elem_journal</h3>" . $a_elem;
					}
				default:
					return $a_elem;
					break;
			}
		}
	}

	private function fillConferenceElement($co) //creates one conference cell for a list
	{
		global $wpdb;
		$c_elem = "<span style='";
		if(get_option("bnpp_lstyle_value") != "") { $c_elem .= get_option("bnpp_lstyle_value"); }
		if(get_option("bnpp_custom_char1_name") != "" && !is_null($co->char1)) { $c_elem .= get_option("bnpp_custom_char1_value"); }
		if(get_option("bnpp_custom_char2_name") != "" && !is_null($co->char2)) { $c_elem .= get_option("bnpp_custom_char2_value"); }
		if(get_option("bnpp_custom_char3_name") != "" && !is_null($co->char3)) { $c_elem .= get_option("bnpp_custom_char3_value"); }
		$c_elem .= "'>" . get_option('bnpp_conference_list') . "</span><br>";
		while(preg_match('/{{(.*?)}}/',$c_elem,$matches))
		{
			$c_elem = str_replace($matches[0],$this->replaceOptionalContent($matches[1],$co),$c_elem);
		}
		$c_elem = preg_replace('/\[title\]/',$co->title,$c_elem);
		$c_elem = preg_replace('/\[year\]/',$co->year,$c_elem);
		$c_elem = preg_replace('/\[pages\]/',$co->pages,$c_elem);
		$c_elem = preg_replace('/\[doi\]/',$co->doi,$c_elem);
		$c_elem = preg_replace('/\[url\]/',$co->url,$c_elem);
		$c_elem = preg_replace('/\[issn\]/',$co->issn,$c_elem);
		$c_elem = preg_replace('/\[supplementary\]/',$co->supplementary,$c_elem);
		$c_elem = preg_replace('/\[filelink\]/',$co->file_link,$c_elem);
		$c_elem = preg_replace('/\[date\]/',$co->date,$c_elem);
		$c_elem = preg_replace('/\[booktitle\]/',$co->book_title,$c_elem);
		$c_elem = preg_replace('/\[confpages\]/',$co->conf_page,$c_elem);
		$c_elem = preg_replace('/\[arxiv\]/',$co->arxiv,$c_elem);
		$c_elem_authors = "";
		try {
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE conference_id=%d", $co->id)) as $c)
			{
				if(!is_null($c->author_id))
				{
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE id=%d", $c->author_id)) as $au)
					{
						$c_elem_authors .= "$au->first_name  $au->last_name, ";
					}
				} else {
					$c_elem_authors .= "$c->author_name, ";
				}
			}
			$c_elem_authors = substr($c_elem_authors, 0, -2);
			$c_elem_tags = "";
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbConferenceTag . " WHERE conference_id=%d", $co->id)) as $c)
			{
				foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbTags . " WHERE id=%d", $c->tag_id)) as $au)
				{
					$c_elem_tags .= "$au->tag; ";
				}
			}
			$c_elem_tags = substr($c_elem_tags, 0, -2);
			$c_elem = preg_replace('/\[tags\]/',$c_elem_tags,$c_elem);
		} catch (Exception $e) {}
		$c_elem = preg_replace('/\[authors\]/',$c_elem_authors,$c_elem);
		$c_elem = str_replace('&quot;','',$c_elem);
		//$c_elem = str_replace('\&quot;',"",str_replace('"',"&quot;",$c_elem));
		if(get_option('bnpp_ordered_list')=='checked')
		{
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $co->year)
					{
						$this->listDivision = $co->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->year</h3><li>" . $c_elem . "</li>";
					} else {
						return "<li>" . $c_elem . "</li>";
					}
				case "title":
					if($this->listDivision != $co->title)
					{
						$this->listDivision = $co->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->title</h3><li>" . $c_elem . "</li>";
					} else {
						return "<li>" . $c_elem . "</li>";
					}
				case "book_title":
					if($this->listDivision != $co->book_title)
					{
						$this->listDivision = $co->book_title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->book_title</h3><li>" . $c_elem . "</li>";
					}
				default:
					return "<li>" . $c_elem . "</li>";
					break;
			}
		} else {
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $co->year)
					{
						$this->listDivision = $co->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->year</h3>" . $c_elem;
					} else {
						return $c_elem;
					}
				case "title":
					if($this->listDivision != $co->title)
					{
						$this->listDivision = $co->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->title</h3>" . $c_elem;
					} else {
						return $c_elem;
					}
				case "book_title":
					if($this->listDivision != $co->book_title)
					{
						$this->listDivision = $co->book_title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$co->book_title</h3>" . $c_elem;
					}
				default:
					return $c_elem;
					break;
			}
		}
	}

	private function fillBookElement($bo) //creates one book cell for a list
	{
		global $wpdb;
		$b_elem = "<span style='";
		if(get_option("bnpp_lstyle_value") != "") { $b_elem .= get_option("bnpp_lstyle_value"); }
		if(get_option("bnpp_custom_char1_name") != "" && !is_null($bo->char1)) { $b_elem .= get_option("bnpp_custom_char1_value"); }
		if(get_option("bnpp_custom_char2_name") != "" && !is_null($bo->char2)) { $b_elem .= get_option("bnpp_custom_char2_value"); }
		if(get_option("bnpp_custom_char3_name") != "" && !is_null($bo->char3)) { $b_elem .= get_option("bnpp_custom_char3_value"); }
		$b_elem .= "'>" . get_option('bnpp_book_list') . "</span><br>";
		while(preg_match('/{{(.*?)}}/',$b_elem,$matches))
		{
			$b_elem = str_replace($matches[0],$this->replaceOptionalContent($matches[1],$bo),$b_elem);
		}
		$b_elem = preg_replace('/\[title\]/',$bo->title,$b_elem);
		$b_elem = preg_replace('/\[year\]/',$bo->year,$b_elem);
		$b_elem = preg_replace('/\[pages\]/',$bo->pages,$b_elem);
		$b_elem = preg_replace('/\[doi\]/',$bo->doi,$b_elem);
		$b_elem = preg_replace('/\[url\]/',$bo->url,$b_elem);
		$b_elem = preg_replace('/\[issn\]/',$bo->issn,$b_elem);
		$b_elem = preg_replace('/\[supplementary\]/',$bo->supplementary,$b_elem);
		$b_elem = preg_replace('/\[filelink\]/',$bo->file_link,$b_elem);
		$b_elem = preg_replace('/\[date\]/',$bo->date,$b_elem);
		$b_elem = preg_replace('/\[publisher\]/',$bo->publisher,$b_elem);
		$b_elem = preg_replace('/\[chapter\]/',$bo->chapter,$b_elem);
		$b_elem = preg_replace('/\[isbn\]/',$bo->isbn,$b_elem);
		$b_elem = preg_replace('/\[arxiv\]/',$bo->arxiv,$b_elem);
		$b_elem_authors = "";
		try{
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookAuthor . " WHERE book_id=%d", $bo->id)) as $a)
			{
				if(!is_null($a->author_id))
				{
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE id=%d", $a->author_id)) as $au)
					{
						$b_elem_authors .= "$au->first_name  $au->last_name, ";
					}
				} else {
					$b_elem_authors .= "$a->author_name, ";
				}
			}
			$b_elem_authors = substr($b_elem_authors, 0, -2);
			$b_elem = preg_replace('/\[authors\]/',$b_elem_authors,$b_elem);
			$b_elem_editors = "";
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookEditor . " WHERE book_id=%d", $bo->id)) as $a)
			{
				if(!is_null($a->editor_id))
				{
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE id=%d", $a->editor_id)) as $au)
					{
						$b_elem_editors .= "$au->first_name $au->last_name, ";
					}
				} else {
					$b_elem_editors .= "$a->editor_name, ";
				}
			}
			$b_elem_editors = substr($b_elem_editors, 0, -2);
			$b_elem_tags = "";
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbBookTag . " WHERE book_id=%d", $bo->id)) as $a)
			{
				foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbTags . " WHERE id=%d", $a->tag_id)) as $au)
				{
					$b_elem_tags .= "$au->tag; ";
				}
			}
			$b_elem_tags = substr($b_elem_tags, 0, -2);
			$b_elem = preg_replace('/\[tags\]/',$b_elem_tags,$b_elem);
		} catch (Exception $e) {}
		$b_elem = preg_replace('/\[editors\]/',$b_elem_editors,$b_elem);
		$b_elem = str_replace('&quot;','',$b_elem);
		//$b_elem = str_replace('\&quot;',"",str_replace('"',"&quot;",$b_elem));
		if(get_option('bnpp_ordered_list')=='checked')
		{
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $bo->year)
					{
						$this->listDivision = $bo->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->year</h3><li>" . $b_elem . "</li>";
					} else {
						return "<li>" . $b_elem . "</li>";
					}
				case "title":
					if($this->listDivision != $bo->title)
					{
						$this->listDivision = $bo->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->title</h3><li>" . $b_elem . "</li>";
					} else {
						return "<li>" . $b_elem . "</li>";
					}
				case "publisher":
					if($this->listDivision != $bo->publisher)
					{
						$this->listDivision = $bo->publisher;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->publisher</h3><li>" . $b_elem . "</li>";
					}
				default:
					return "<li>" . $b_elem . "</li>";
					break;
			}
		} else {
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $bo->year)
					{
						$this->listDivision = $bo->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->year</h3>" . $b_elem;
					} else {
						return $b_elem;
					}
				case "title":
					if($this->listDivision != $bo->title)
					{
						$this->listDivision = $bo->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->title</h3>" . $b_elem;
					} else {
						return $b_elem;
					}
				case "publisher":
					if($this->listDivision != $bo->publisher)
					{
						$this->listDivision = $bo->publisher;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$bo->publisher</h3>" . $b_elem;
					}
				default:
					return $b_elem;
					break;
			}
		}
	}
	
	private function fillMixedElement($pub) //creates one article cell for a list
	{
		global $wpdb;
		$p_elem = "<span style='";
		if(get_option("bnpp_lstyle_value") != "") { $p_elem .= get_option("bnpp_lstyle_value"); }
		if(get_option("bnpp_custom_char1_name") != "" && !is_null($pub->char1)) { $p_elem .= get_option("bnpp_custom_char1_value"); }
		if(get_option("bnpp_custom_char2_name") != "" && !is_null($pub->char2)) { $p_elem .= get_option("bnpp_custom_char2_value"); }
		if(get_option("bnpp_custom_char3_name") != "" && !is_null($pub->char3)) { $p_elem .= get_option("bnpp_custom_char3_value"); }
		$p_elem .= "'>" . get_option('bnpp_article_list') . "</span><br>";
		while(preg_match('/{{(.*?)}}/',$p_elem,$matches))
		{
			$p_elem = str_replace($matches[0],$this->replaceOptionalContent($matches[1],$pub),$p_elem);
		}
		$p_elem = preg_replace('/\[title\]/',$pub->title,$p_elem);
		$p_elem = preg_replace('/\[year\]/',$pub->year,$p_elem);
		$p_elem = preg_replace('/\[pages\]/',$pub->pages,$p_elem);
		$p_elem = preg_replace('/\[doi\]/',$pub->doi,$p_elem);
		$p_elem = preg_replace('/\[url\]/',$pub->url,$p_elem);
		$p_elem = preg_replace('/\[issn\]/',$pub->issn,$p_elem);
		$p_elem = preg_replace('/\[supplementary\]/',$pub->supplementary,$p_elem);
		$p_elem = preg_replace('/\[filelink\]/',$pub->file_link,$p_elem);
		$p_elem = preg_replace('/\[date\]/',$pub->date,$p_elem);
		$p_elem = preg_replace('/\[arxiv\]/',$pub->arxiv,$p_elem);
		$p_elem_authors = "";
		$p_elem_tags = "";
		try {
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . ($pub->type == "ar" ? $this->dbArticleAuthor : ($pub->type == "co" ? $this->dbConferenceAuthor : $this->dbBookAuthor)) . " WHERE article_id=%d", $pub->id)) as $a)
			{
				if(!is_null($a->author_id))
				{
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE id=%d", $a->author_id)) as $au)
					{
						$p_elem_authors .= "$au->first_name  $au->last_name, ";
					}
				} else {
					$p_elem_authors .= "$a->author_name, ";
				}
			}
			$p_elem_authors = substr($p_elem_authors, 0, -2);
			$p_elem = preg_replace('/\[authors\]/',$p_elem_authors,$p_elem);
			foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . ($pub->type == "ar" ? $this->dbArticleTag : ($pub->type == "co" ? $this->dbConferenceTag : $this->dbBookTag)) . " WHERE article_id=%d", $pub->id)) as $a)
			{
				foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbTags . " WHERE id=%d", $a->tag_id)) as $au)
				{
					$p_elem_tags .= "$au->tag; ";
				}
			}
			$p_elem_tags = substr($p_elem_tags, 0, -2);
			$p_elem = preg_replace('/\[tags\]/',$p_elem_tags,$p_elem);
		} catch (Exception $e) {}
		$p_elem = str_replace('&quot;','',$p_elem);
		if(get_option('bnpp_ordered_list')=='checked')
		{
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $pub->year)
					{
						$this->listDivision = $pub->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$pub->year</h3><li>" . $p_elem . "</li>";
					} else {
						return "<li>" . $p_elem . "</li>";
					}
				case "title":
					if($this->listDivision != $pub->title)
					{
						$this->listDivision = $pub->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$pub->title</h3><li>" . $p_elem . "</li>";
					} else {
						return "<li>" . $p_elem . "</li>";
					}
				default:
					return "<li>" . $p_elem . "</li>";
					break;
			}
		} else {
			switch(get_option('bnpp_list_division'))
			{
				case "year":
					if($this->listDivision != $pub->year)
					{
						$this->listDivision = $pub->year;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$pub->year</h3>" . $p_elem;
					} else {
						return $p_elem;
					}
				case "title":
					if($this->listDivision != $pub->title)
					{
						$this->listDivision = $pub->title;
						return "<h3 style='".get_option('bnpp_list_division_style')."'>$pub->title</h3>" . $p_elem;
					} else {
						return $p_elem;
					}
				default:
					return $p_elem;
					break;
			}
		}
	}

	function replacePublications($content) //replaces tagged text with publication data
	{
		global $wpdb;
		//compatibility
		if (get_option('bnpp_comp_pub')) {
			//compatibility
			$rbracket = preg_quote(get_option('bnpp_comp_rbracket'));
			$lbracket = preg_quote(get_option('bnpp_comp_lbracket'));
			//check which system to use
			if(!get_option('bnpp_comp_legacy')){ //use new system
				include(bnpp_plugin_dir . "./classes/functions/bridge-replace_pub_updated.php");
			} else { //use legacy system
				include(bnpp_plugin_dir . "./classes/functions/bridge-replace_publications.php");
			}
			//if no options executed, removes tag
			if(get_option('bnpp_comp_clear')){
				$content = preg_replace('/' . $rbracket . '.*' . $lbracket . '/', "",$content);
			}
		}
		return $content;
	}

	function replaceAuthorName($content) //replaces author name by criteria
	{
		global $wpdb;
		//compatibility options
		if (get_option('bnpp_comp_auth')) {
		if(!get_option('bnpp_drop_db_tables'))
		{
			$p_url = get_page_link();
			$author = "";
			$criteria = "";
			try{
				foreach($wpdb->get_results("SELECT * FROM " . $this->dbAuthors) as $au)
				{
					if($au->personal_url != "" && ($au->personal_url . "/" != $p_url) && ($au->personal_url != $p_url))
					{
						$author = '<a href="' . $au->personal_url . '">' . $au->first_name . ' ' . $au->last_name . '</a>';
					} else 
					{
						$author = $au->first_name . ' ' . $au->last_name;
					}

					$criteria = "$au->first_name $au->last_name"; // Name Surname
					$content = str_replace($criteria, $author, $content);

					$criteria = "$au->first_name  $au->last_name"; // Name  Surname
					$content = str_replace($criteria, $author, $content);

					/*$criteria = substr($au->first_name, 0, 1) . ". $au->last_name"; // N. Surname
				$content = str_replace($criteria, $author, $content);*/

					$criteria = "$au->last_name, $au->first_name"; // Surname, Name
					$content = str_replace($criteria, $author, $content);

					/*$criteria = "$au->last_name, " . substr($au->first_name, 0, 1) . "."; // Surname, N.
				$content = str_replace($criteria, $author, $content);*/
				}
			}catch (Exception $e) {}
		}
		}
		return $content;
	}

	function importArticle($data) //extracts and imports article data from text file (.bib)
	{
		global $wpdb;
		$j = 0;
		$title = "";
		$journal = "";
		$year = "";
		$volume = "";
		$issue = "";
		$pages = "";
		$doi = "";
		$url = "";
		$issn = "";
		$supplementary = "";
		$arxiv = "";
		$file = "";
		$public = "";
		$firstName = "";
		$lastName = "";
		$nextAuthor = false;
		$newAuthors = "";
		$authorIDs = array();
		$data = $this->escapeSpecialCharacters($data);
		while($j<strlen($data))
		{
			if($nextAuthor)
			{
				$j+=4;
				$firstName = "";
				$lastName = "";
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							if($firstName != "") { $firstName.=" "; }
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; } else { $nextAuthor = false; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,6) == "author")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							if($firstName != "") { $firstName.=" "; }
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,5) == "title")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0))
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					$title.=substr($data,$j,1);
					$j++;
				}
			} else if(substr($data,$j,7) == "journal")
			{
				$j+=7;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0))
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					$journal.=substr($data,$j,1);
					$j++;
				}
			} else if(substr($data,$j,4) == "year")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$year.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,6) == "volume")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$volume.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "issue")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$issue.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "pages")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$pages.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "doi")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$doi.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "url")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$url.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "issn")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$issn.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,13) == "supplementary")
			{
				$j+=13;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$supplementary.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "arxiv")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$arxiv.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "file")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$file.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,6) == "public")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$public.=substr($data,$j,1);
					}
					$j++;
				}
			}
			$j++;
		}
		$j_id;
		$data1 = $wpdb->get_results($wpdb->prepare("SELECT id FROM " . $this->dbJournals . " WHERE journal = %s", $journal));
		$chVal = true;
		foreach($data1 as $c){$chVal=false;}
		if($chVal)
		{
			$wpdb->insert($this->dbJournals,array(
				"journal"=>$journal
			));
			$data1 = $wpdb->get_results("SELECT id FROM " . $this->dbJournals . " ORDER By id DESC LIMIT 1");
			foreach($data1 as $el)
			{
				$j_id = $el->id;
			}
		} else { foreach($data1 as $el) { $j_id = $el->id; } }
		$wpdb->insert($this->dbArticles,array(
			"title"=>$title,
			"journal"=>$j_id,
			"year"=>$year,
			"volume"=>$volume,
			"issue"=>$issue,
			"pages"=>$pages,
			"doi"=>$doi,
			"url"=>$url,
			"issn"=>$issn,
			"supplementary"=>$supplementary,
			"arxiv"=>$arxiv,
			"file_link"=>$file,
			"date"=>date("ymd"),
			"is_public"=>1
		));
		$art_id;
		$data1 = $wpdb->get_results("SELECT id FROM " . $this->dbArticles . " ORDER By id DESC LIMIT 1");
		foreach($data1 as $el)
		{
			$art_id = $el->id;
		}
		if($public == "true")
		{
			$wpdb->query("UPDATE $this->dbArticles SET is_public = 0 WHERE (id = $art_id)");
		}
		foreach($authorIDs as $au_id)
		{
			$wpdb->insert($this->dbArticleAuthor,array(
				"author_id"=>$au_id,
				"article_id"=>$art_id
			));
		}
		return $newAuthors;
	}

	function importBook($data) //extracts and imports book data from text file (.bib)
	{
		global $wpdb;
		$newAuthors = "";
		$j = 0;
		$title = "";
		$publisher = "";
		$chapter = "";
		$isbn = "";
		$year = "";
		$pages = "";
		$doi = "";
		$url = "";
		$issn = "";
		$supplementary = "";
		$arxiv = "";
		$file = "";
		$public = "";
		$firstName = "";
		$lastName = "";
		$nextAuthor = false;
		$nextEditor = false;
		$authorIDs = array();
		$editorIDs = array();
		$data = $this->escapeSpecialCharacters($data);
		while($j<strlen($data))
		{
			if($nextAuthor)
			{
				$j+=4;
				$firstName = "";
				$lastName = "";
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; } else { $nextAuthor = false; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,6) == "author")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if($nextEditor)
			{
				$j+=4;
				$firstName = "";
				$lastName = "";
				$chName = false;
				while(substr($data,$j,1) != "}" && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextEditor = true; } else { $nextEditor = false; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($editorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($editorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,6) == "editor")
			{
				$j+=6;
				$firstName = "";
				$lastName = "";
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$chName = false;
				while(substr($data,$j,1) != "}" && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextEditor = true; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($editorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($editorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,5) == "title")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0))
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					$title.=substr($data,$j,1);
					$j++;
				}
			} else if(substr($data,$j,9) == "publisher")
			{
				$j+=9;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$publisher.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "year")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$year.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,7) == "chapter")
			{
				$j+=7;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$chapter.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "isbn")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$isbn.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "pages")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$pages.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "doi")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$doi.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "url")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$url.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "issn")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$issn.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,13) == "supplementary")
			{
				$j+=13;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$supplementary.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "arxiv")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$arxiv.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "file")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$file.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,6) == "public")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$public.=substr($data,$j,1);
					}
					$j++;
				}
			}
			$j++;
		}
		$wpdb->insert($this->dbBooks,array(
			"title"=>$title,
			"publisher"=>$publisher,
			"year"=>$year,
			"chapter"=>$chapter,
			"isbn"=>$isbn,
			"pages"=>$pages,
			"doi"=>$doi,
			"url"=>$url,
			"issn"=>$issn,
			"supplementary"=>$supplementary,
			"arxiv"=>$arxiv,
			"file_link"=>$file,
			"date"=>date("ymd"),
			"is_public"=>1
		));
		$art_id;
		$data1 = $wpdb->get_results("SELECT id FROM " . $this->dbBooks . " ORDER By id DESC LIMIT 1");
		foreach($data1 as $el)
		{
			$art_id = $el->id;
		}
		if($public == "true")
		{
			$wpdb->query("UPDATE $this->dbBooks SET is_public = 0 WHERE (id = $art_id)");
		}
		foreach($authorIDs as $au_id)
		{
			$wpdb->insert($this->dbBookAuthor,array(
				"author_id"=>$au_id,
				"book_id"=>$art_id
			));
		}
		foreach($editorIDs as $au_id)
		{
			$wpdb->insert($this->dbBookEditor,array(
				"editor_id"=>$au_id,
				"book_id"=>$art_id
			));
		}
		return $newAuthors;
	}

	function importConference($data) //extracts and imports conference data from text file (.bib)
	{
		global $wpdb;
		$newAuthors = "";
		$j = 0;
		$title = "";
		$booktitle = "";
		$confpages = "";
		$year = "";
		$pages = "";
		$doi = "";
		$url = "";
		$issn = "";
		$supplementary = "";
		$arxiv = "";
		$file = "";
		$public = "";
		$firstName = "";
		$lastName = "";
		$nextAuthor = false;
		$authorIDs = array();
		$data = $this->escapeSpecialCharacters($data);
		while($j<strlen($data))
		{
			if($nextAuthor)
			{
				$j+=4;
				$firstName = "";
				$lastName = "";
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; } else { $nextAuthor = false; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,6) == "author")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$chName = false;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0) && substr($data,$j,4) != " and")
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					if(substr($data,$j,1) == ",")
					{
						$chName = true;
					}
					$firstName.=substr($data,$j,1);
					$j++;
				}
				if($chName)
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while(substr($tempName,$i,1) != ",")
					{
						$lastName.=substr($tempName,$i,1);
						$i++;
					}
					$i+=2;
					while($i<strlen($tempName))
					{
						$firstName.=substr($tempName,$i,1);
						$i++;
					}
				} else
				{
					$tempName = $firstName;
					$firstName = "";
					$i=0;
					while($i<strlen($tempName))
					{
						if(substr($tempName,$i,1)==" ")
						{
							$firstName.=$lastName;
							$lastName="";
						} else
						{
							$lastName.=substr($tempName,$i,1);
						}
						$i++;
					}
				}
				if (substr($data,$j,4) == " and") { $nextAuthor = true; }
				$check = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName)));
				$chVal = true;
				foreach($check as $c){$chVal=false; array_push($authorIDs,$c->id);}
				if($chVal)
				{
					$wpdb->insert($this->dbAuthors,array(
						"first_name"=>$firstName,
						"last_name"=>$lastName
					));
					$newAuthors .= "$firstName $lastName, ";
					foreach($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $this->dbAuthors . " WHERE last_name = %s AND first_name = %s", array($lastName, $firstName))) as $c)
					{
						array_push($authorIDs,$c->id);
					}
				}
			} else if(substr($data,$j,5) == "title")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0))
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					$title.=substr($data,$j,1);
					$j++;
				}
			} else if(substr($data,$j,9) == "booktitle")
			{
				$j+=9;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				$innerBrackets = 0;
				while(!(substr($data,$j,1) == "}" && $innerBrackets == 0))
				{
					if(substr($data,$j,1)=="{")
					{
						$innerBrackets++;
					}
					if($innerBrackets>0 && substr($data,$j,1)=="}")
					{
						$innerBrackets--;
					}
					$booktitle.=substr($data,$j,1);
					$j++;
				}
			} else if(substr($data,$j,4) == "year")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$year.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,9) == "confpages")
			{
				$j+=9;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$confpages.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "pages")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$pages.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "doi")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$doi.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,3) == "url")
			{
				$j+=3;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$url.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "issn")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$issn.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,13) == "supplementary")
			{
				$j+=13;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$supplementary.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,5) == "arxiv")
			{
				$j+=5;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$arxiv.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,4) == "file")
			{
				$j+=4;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$file.=substr($data,$j,1);
					}
					$j++;
				}
			} else if(substr($data,$j,6) == "public")
			{
				$j+=6;
				while(substr($data,$j,1) != "{")
				{
					$j++;
				}
				$j++;
				while(substr($data,$j,1) != "}")
				{
					if(substr($data,$j,1)!="'" && substr($data,$j,1) != '"')
					{
						$public.=substr($data,$j,1);
					}
					$j++;
				}
			}
			$j++;
		}
		$wpdb->insert($this->dbConferences,array(
			"title"=>$title,
			"book_title"=>$booktitle,
			"year"=>$year,
			"conf_page"=>$confpages,
			"pages"=>$pages,
			"doi"=>$doi,
			"url"=>$url,
			"issn"=>$issn,
			"supplementary"=>$supplementary,
			"arxiv"=>$arxiv,
			"file_link"=>$file,
			"date"=>date("ymd"),
			"is_public"=>1
		));
		$art_id;
		$data1 = $wpdb->get_results("SELECT id FROM " . $this->dbConferences . " ORDER By id DESC LIMIT 1");
		foreach($data1 as $el)
		{
			$art_id = $el->id;
		}
		if($public == "true")
		{
			$wpdb->query($wpdb->prepare("UPDATE $this->dbConferences SET is_public = 0 WHERE (id = %d)", $art_id));
		}
		foreach($authorIDs as $au_id)
		{
			$wpdb->insert($this->dbConferenceAuthor,array(
				"author_id"=>$au_id,
				"conference_id"=>$art_id
			));
		}
		return $newAuthors;
	}
	//escapes special chars
	private function escapeSpecialCharacters($data)
	{
		//escapes html quotes
		$data = str_replace("\'","&#039;",$data);
		$data = str_replace('\"',"&quot;",$data);
		//escapes german symbols
		$data = str_replace('\\\&quot;{O}',"&#214;",$data);
		$data = str_replace('\&quot;{O}',"&#214;",$data);
		$data = str_replace('\"{O}',"&#214;",$data);
		$data = str_replace('\\\&quot;{o}',"&#246;",$data);
		$data = str_replace('\&quot;{o}',"&#246;",$data);
		$data = str_replace('\"{o}',"&#246;",$data);
		$data = str_replace('\\\&quot;{A}',"&#196;",$data);
		$data = str_replace('\&quot;{A}',"&#196;",$data);
		$data = str_replace('\"{A}',"&#196;",$data);
		$data = str_replace('\\\&quot;{a}',"&#228;",$data);
		$data = str_replace('\&quot;{a}',"&#228;",$data);
		$data = str_replace('\"{a}',"&#228;",$data);
		$data = str_replace('\\\&quot;{U}',"&#220;",$data);
		$data = str_replace('\&quot;{U}',"&#220;",$data);
		$data = str_replace('\"{U}',"&#220;",$data);
		$data = str_replace('\\\&quot;{u}',"&#252;",$data);
		$data = str_replace('\&quot;{u}',"&#252;",$data);
		$data = str_replace('\"{u}',"&#252;",$data);
		//escapes double brackets
		$data = str_replace('{{',"{",$data);
		$data = str_replace('}}',"}",$data);
		return $data;
	}
	function requestDataByDOI($doi) //requests paper data by doi from external resource
	{
		//$url = "http://www.doi2bib.org/doi2bib?id=" . $doi;
		$url = "http://api.crossref.org/works/" . $doi . "/transform/application/x-bibtex";
		$data = $this->getWebPage($url);
		$page = $data['content'];
		if($page != "Not Found")
		{
			$page = str_replace("\n", '', $page);
			$page = str_replace("'", "\\'", $page);
			return $page;
		} else
		{
			return 'Document Not Found';
		}
	}
	private function getWebPage($url)
	{
		$user_agent = 'BookAndPapers WordPress Plugin';
		$options = array(
			CURLOPT_CUSTOMREQUEST  => "GET",        	//set request type post or get
			CURLOPT_POST           => false,        	//set to GET
			CURLOPT_USERAGENT      => $user_agent, 		//set user agent
			CURLOPT_COOKIEFILE     =>"cookie.txt", 		//set cookie file
			CURLOPT_COOKIEJAR      =>"cookie.txt", 		//set cookie jar
			CURLOPT_RETURNTRANSFER => true,     		// return web page
			CURLOPT_HEADER         => false,    		// don't return headers
			CURLOPT_FOLLOWLOCATION => true,     		// follow redirects
			CURLOPT_ENCODING       => "",       		// handle all encodings
			CURLOPT_AUTOREFERER    => true,     		// set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      		// timeout on connect
			CURLOPT_TIMEOUT        => 120,      		// timeout on response
			CURLOPT_MAXREDIRS      => 10,       		// stop after 10 redirects
		);
		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );
		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}
	function handleDOIData($data, $j_option_values, $t_option_values, $option_values) //parses bib format to better fitting one
	{
		$result="";
		$j=0;
		while($j<strlen($data)&&substr($data,$j,1)!="@")
		{
			$j++;
		}
		$j++;
		while($j<strlen($data) && substr($data,$j,1)!="{")
		{
			$result.=substr($data,$j,1);
			$j++;
		}
		$result = strtolower($result);
		$workType = "article";
		if($result == "conference" || $result == "inproceedings")
		{
			$result = "<table><tr><td style=\"min-width: 100px;\"><label for=\"paperType\">Work Type</label></td><td><select id=\"paperType\" name=\"paperType\" onchange=\"typeSelection()\"><option value=\"article\">Article</option><option value=\"conference\" selected>Conference</option><option value=\"book\">Book</option></select></td></tr>";
			$workType = "conference";
		} else if ($result == "book" || $result == "inbook")
		{
			$result = "<table><tr><td style=\"min-width: 100px;\"><label for=\"paperType\">Work Type</label></td><td><select id=\"paperType\" name=\"paperType\" onchange=\"typeSelection()\"><option value=\"article\">Article</option><option value=\"conference\">Conference</option><option value=\"book\" selected>Book</option></select></td></tr>";
			$workType = "book";
		} else
		{
			$result = "<table><tr><td style=\"min-width: 100px;\"><label for=\"paperType\">Work Type</label></td><td><select id=\"paperType\" name=\"paperType\" onchange=\"typeSelection()\"><option value=\"article\" selected>Article</option><option value=\"conference\">Conference</option><option value=\"book\">Book</option></select></td></tr>";
		}
		$result = "<div style=\"display:none\" class=\"InformationText\">Here you can add publication data.<br>For <span id=\"type\">Article</span> publication next fields are necessary:<br><span id=\"help\">Authors;<br>Title;<br>Journal;<br>Year</span><br></div>" . $result;
		$result .= "<tr><td style=\"vertical-align:top;\"><label for=\"authors0\">Authors:</label></td><td id=\"authorSelection\"><input placeholder=\"author\" list=\"authors0\" name=\"author0\" id=\"author0\"><datalist id=\"authors0\">$option_values</datalist><br><span class=\"button\" id=\"addAuthorToSelection\" onclick=\"UpdateAuthorSelection()\">Add Another Author</span></td></tr><tr><td id=\"book0\" style=\"vertical-align:top;\"></td><td id=\"book1\"></td></tr><tr><td><label for=\"title\">Title</label></td><td><input oninput=\"changeInputWidthDefault(\'title\')\" id=\"title\" name=\"title\" type=\"text\" placeholder=\"title\"/></td></tr><tr id=\"book2\"></tr><tr id=\"conf0\">";
		if($workType == "conference"){
			$result .= "<td><label for=\"bookTitle\">Book Title</label></td><td><input oninput=\"changeInputWidthDefault(\'bookTitle\')\" id=\"bookTitle\" name=\"bookTitle\" type=\"text\" placeholder=\"book title\"/></td>";
		}
		$result .= "</tr><tr id=\"article0\">";
		if($workType == "article"){
			$result .= "<td><label for=\"journal\">Journal</label></td><td><input oninput=\"changeInputWidthDefault(\'journal\')\" id=\"journal\" list=\"j_list\" name=\"journal\" type=\"text\" placeholder=\"journal\"/><datalist id=\"j_list\">$j_option_values</datalist></td>";
		}
		$result .= "</tr><tr><td><label for=\"year\">Year</label></td><td><input style=\"width:153px\" id=\"year\" name=\"year\" type=\"number\" min=\"1900\" max=\"2050\" placeholder=\"year\"/></td></tr><tr id=\"book3\">";
		if($workType == "book"){
			$result .= "<td><label for=\"chapter\">Chapter</label></td><td><input oninput=\"changeInputWidthDefault(\'chapter\')\" id=\"chapter\" name=\"chapter\" type=\"text\" placeholder=\"chapter\"/></td>";
		}
		$result .= "</tr><tr id=\"article1\">";
		if($workType == "article"){
			$result .= "<td><label for=\"volume\">Volume</label></td><td><input id=\"volume\" name=\"volume\" type=\"number\" min=\"0\" placeholder=\"volume\"/></td>";
		}
		$result .= "</tr><tr id=\"article2\">";
		if($workType == "article"){
			$result .= "<td><label for=\"issue\">Issue</label></td><td><input id=\"issue\" name=\"issue\" type=\"number\" min=\"0\" placeholder=\"issue\"/></td>";
		}
		$result .= "</tr><tr><td><label for=\"pages\">Pages</label></td><td><input oninput=\"changeInputWidthDefault(\'pages\')\" id=\"pages\" name=\"pages\" type=\"text\" placeholder=\"pages\"/></td></tr><tr id=\"conf1\">";
		if($workType == "conference"){
			$result .= "<td><label for=\"confPages\">Conference Pages</label></td><td><input oninput=\"changeInputWidthDefault(\'confPages\')\" id=\"confPages\" name=\"confPages\" type=\"text\" placeholder=\"conference pages\"/></td>";
		}
		$result .= "</tr><tr><td><label for=\"doi\">DOI</label></td><td><input oninput=\"changeInputWidthDefault(\'doi_input\')\" id=\"doi_input\" name=\"doi\" type=\"text\" placeholder=\"doi\"/></td></tr><tr><td><label for=\"url\">URL</label></td><td><input oninput=\"changeInputWidthDefault(\'url\')\" id=\"url\" name=\"url\" type=\"text\" placeholder=\"url\"/></td></tr><tr id=\"book4\">";
		if($workType == "book"){
			$result .= "<td><label for=\"isbn\">ISBN</label></td><td><input oninput=\"changeInputWidthDefault(\'isbn\')\" id=\"isbn\" name=\"isbn\" type=\"text\" placeholder=\"isbn\"/></td>";
		}
		$result .= "</tr><tr><td><label for=\"issn\">ISSN</label></td><td><input oninput=\"changeInputWidthDefault(\'issn\')\" id=\"issn\" name=\"issn\" type=\"text\" placeholder=\"issn\"/></td></tr><tr><td><label for=\"supp\">Supplementary</label></td><td><input oninput=\"changeInputWidthDefault(\'supp\')\" id=\"supp\" name=\"supp\" type=\"text\" placeholder=\"supplementary\"/></td></tr><tr><td><label for=\"arxiv\">arXiv</label></td><td><input oninput=\"changeInputWidthDefault(\'arxiv\')\" id=\"arxiv\" name=\"arxiv\" type=\"text\" placeholder=\"arxiv\"/></td></tr><tr><td style=\"vertical-align:top;\"><label for=\"tags0\">Tags:</label></td><td id=\"tagSelection\"><input placeholder=\"tag\" list=\"tags\" name=\"tag\" id=\"tag\"><datalist id=\"tags\">$t_option_values</datalist><br><span class=\"button\" id=\"addTagToSelection\" onclick=\"UpdateTagSelection()\">Add Tag</span></td></tr><tr><td><label for=\"file\">File</label></td><td><input id=\"file\" name=\"file\" type=\"file\"/></td></tr><tr><td><label for=\"date\">Date</label></td><td><input id=\"date\" name=\"date\" type=\"date\"/></td></tr><tr><td><label for=\"public\">Paper is public</label></td><td><input id=\"public\" name=\"public\" type=\"checkbox\" value=\"true\" checked/></td></tr><tr id=\"article3\"><td><label for=\"preprint\">It is Preprint</label></td><td><input id=\"preprint\" name=\"preprint\" type=\"checkbox\" value=\"true\"/></td></tr>";
		if(get_option('bnpp_custom_char1_name')!="") //checks if custom characteristics were set
		{
			$result .= "<tr><td><label for=\'char1\'>".get_option('bnpp_custom_char1_name')."</label></td><td><input id=\'char1\' name=\'char1\' type=\'checkbox\' value=\'true\'/></td></tr>";
		}
		if(get_option('bnpp_custom_char2_name')!="")
		{
			$result .= "<tr><td><label for=\'char2\'>".get_option('bnpp_custom_char2_name')."</label></td><td><input id=\'char2\' name=\'char2\' type=\'checkbox\' value=\'true\'/></td></tr>";
		}
		if(get_option('bnpp_custom_char3_name')!="")
		{
			$result .= "<tr><td><label for=\'char3\'>".get_option('bnpp_custom_char3_name')."</label></td><td><input id=\'char3\' name=\'char3\' type=\'checkbox\' value=\'true\'/></td></tr>";
		}
		$result .= "<p id=\"unprocessed_header\">Unprocessed DOI fields:</p><p id=\"unprocessed_body\">";
		$data = substr($data, $j + 1, -1) . ",";
		$data_array = explode("},", $data);
		for($i = 0; $i < count($data_array) - 1; $i++){
			$fields = explode(" = ", $data_array[$i]);
			$field_name = explode("\t",$fields[0]);
			$field_data = "";
			for($k = 1; $k < count($fields); $k++){
				$temp_string_array = explode(",",$fields[$k]);
				if($k == count($fields) - 1){
					$field_data = $fields[$k];
				} else {
					$field_data = $temp_string_array[0];
				}
				if(substr($field_data,0,1) == "{"){
					$field_data = substr($field_data,1);
				}
				if($field_name[count($field_name)-1] == "author"){
					$result.="<span id=\"author_data_desc\">Authors: </span><span id=\"author_data\">" . $field_data . "</span><br id=\"author_data_br\">";
				}
				if($field_name[count($field_name)-1] == "editor"){
					$result.="<span id=\"editor_data_desc\">Editors: </span><span id=\"editor_data\">" . $field_data . "</span><br id=\"editor_data_br\">";
				}
				if($field_name[count($field_name)-1] == "title"){
					$result.="<span id=\"title_data_desc\">Title: </span><span id=\"title_data\">" . $field_data . "</span><br id=\"title_data_br\">";
				}
				if($field_name[count($field_name)-1] == "booktitle"){
					$result.="<span id=\"booktitle_data_desc\">Book Title: </span><span id=\"booktitle_data\">" . $field_data . "</span><br id=\"booktitle_data_br\">";
				}
				if($field_name[count($field_name)-1] == "journal"){
					$result.="<span id=\"journal_data_desc\">Journal: </span><span id=\"journal_data\">" . $field_data . "</span><br id=\"journal_data_br\">";
				}
				if($field_name[count($field_name)-1] == "publisher"){
					$result.="<span id=\"publisher_data_desc\">Publisher: </span><span id=\"publisher_data\">" . $field_data . "</span><br id=\"publisher_data_br\">";
				}
				if($field_name[count($field_name)-1] == "year"){
					$result.="<span id=\"year_data_desc\">Year: </span><span id=\"year_data\">" . $field_data . "</span><br id=\"year_data_br\">";
				}
				if($field_name[count($field_name)-1] == "volume"){
					$result.="<span id=\"volume_data_desc\">Volume: </span><span id=\"volume_data\">" . $field_data . "</span><br id=\"volume_data_br\">";
				}
				if($field_name[count($field_name)-1] == "issue"){
					$result.="<span id=\"issue_data_desc\">Issue: </span><span id=\"issue_data\">" . $field_data . "</span><br id=\"issue_data_br\">";
				}
				if($field_name[count($field_name)-1] == "chapter"){
					$result.="<span id=\"chapter_data_desc\">Chapter: </span><span id=\"chapter_data\">" . $field_data . "</span><br id=\"chapter_data_br\">";
				}
				if($field_name[count($field_name)-1] == "pages"){
					$result.="<span id=\"pages_data_desc\">Pages: </span><span id=\"pages_data\">" . $field_data . "</span><br id=\"pages_data_br\">";
				}
				if($field_name[count($field_name)-1] == "confpages"){
					$result.="<span id=\"confpages_data_desc\">Conference Pages: </span><span id=\"confpages_data\">" . $field_data . "</span><br id=\"confpages_data_br\">";
				}
				if($field_name[count($field_name)-1] == "doi"){
					$result.="<span id=\"doi_data_desc\">DOI: </span><span id=\"doi_data\">" . $field_data . "</span><br id=\"doi_data_br\">";
				}
				if($field_name[count($field_name)-1] == "isbn"){
					$result.="<span id=\"isbn_data_desc\">ISBN: </span><span id=\"isbn_data\">" . $field_data . "</span><br id=\"isbn_data_br\">";
				}
				if($field_name[count($field_name)-1] == "issn"){
					$result.="<span id=\"issn_data_desc\">ISSN: </span><span id=\"issn_data\">" . $field_data . "</span><br id=\"issn_data_br\">";
				}
				if($field_name[count($field_name)-1] == "supplementary"){
					$result.="<span id=\"supplementary_data_desc\">Supplementary: </span><span id=\"supplementary_data\">" . $field_data . "</span><br id=\"supplementary_data_br\">";
				}
				if($field_name[count($field_name)-1] == "arxiv"){
					$result.="<span id=\"arxiv_data_desc\">ArXiv: </span><span id=\"arxiv_data\">" . $field_data . "</span><br id=\"arxiv_data_br\">";
				}
				if($field_name[count($field_name)-1] == "url"){
					$result.="<span id=\"url_data_desc\">URL: </span><span id=\"url_data\">" . $field_data . "</span><br id=\"url_data_br\">";
				}
				if($k != count($fields) - 1){
					$field_name[count($field_name)-1] = $temp_string_array[1];
				}
			}
		}
		$result .= "</p><p>Publication data:</p>";
		$result .= "</table><button class=\"button\" id=\"addPaperSubmit\" type=\"submit\">Add Work</button>";
		return $result;
	}
	//merges one authors data with another
	function mergeAuthors($main, $second)
	{
		global $wpdb;
		$sql = $wpdb->prepare("UPDATE $this->dbArticleAuthor SET author_id = %s WHERE (author_id = %d)", array($main, $second));
		$wpdb->query($sql);
		$sql = $wpdb->prepare("UPDATE $this->dbConferenceAuthor SET author_id = %s WHERE (author_id = %d)", array($main, $second));
		$wpdb->query($sql);
		$sql = $wpdb->prepare("UPDATE $this->dbBookAuthor SET author_id = %s WHERE (author_id = %d)", array($main, $second));
		$wpdb->query($sql);
		$sql = $wpdb->prepare("UPDATE $this->dbBookEditor SET editor_id = %s WHERE (editor_id = %d)", array($main, $second));
		$wpdb->query($sql);
		$sql = $wpdb->prepare("DELETE FROM $this->dbAuthors WHERE (id = %d)", $second);
		$wpdb->query($sql);
	}
	//copies information from previous tables to new
	function copyDBInfo()
	{
		//DROP [TEMPORARY] TABLE [IF EXISTS] table_name
		global $wpdb;
		//copies information
		$sql = "INSERT INTO $this->dbAuthors SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Authors";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbArticles SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Articles";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbConferences SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Conferences";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbBooks SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Books";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbArticleAuthor SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Article_Authors";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbConferenceAuthor SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Conference_Authors";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbBookAuthor SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Book_Authors";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbBookEditor SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Book_Editor";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbJournals SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Journal";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbTags SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Tags";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbArticleTag SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Article_Tag";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbConferenceTag SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Conference_Tag";
		$wpdb->query($sql);
		$sql = "INSERT INTO $this->dbBookTag SELECT * FROM $wpdb->prefix" . get_option('bnpp_previous_db_prefix') . "_Book_Tag";
		$wpdb->query($sql);
		//deletes previous tables
		$this->dropTables(get_option('bnpp_previous_db_prefix'));
		//saves new prefix
		update_option('bnpp_previous_db_prefix',get_option('bnpp_custom_db_prefix'));
	}
	//request abstract
	function requestAbstractByDOI($doi)
	{
		$url = "http://api.crossref.org/works/" . $doi . "/transform/application/vnd.crossref.unixsd+xml";
		$data = $this->getWebPage($url);
		$page = $data['content'];
		if($page != "Not Found")
		{
			$page = str_replace("\n", '', $page);
			$page = str_replace("'", "\\'", $page);
			return $page;
		} else
		{
			return 'Document Not Found';
		}
	}
	//drops plugin db tables
	function dropTables($prefix)
	{
		global $wpdb;
		$prefix = esc_sql($prefix);
		$sql = "DROP TABLE $wpdb->prefix" . $prefix . "_Authors, $wpdb->prefix" . $prefix . "_Articles, $wpdb->prefix" . $prefix . "_Conferences, $wpdb->prefix" . $prefix . "_Books, $wpdb->prefix" . $prefix . "_Article_Authors, $wpdb->prefix" . $prefix . "_Conference_Authors, $wpdb->prefix" . $prefix . "_Book_Authors, $wpdb->prefix" . $prefix . "_Book_Editor, $wpdb->prefix" . $prefix . "_Journals, $wpdb->prefix" . $prefix . "_Tags, $wpdb->prefix" . $prefix . "_Article_Tag, $wpdb->prefix" . $prefix . "_Book_Tag, $wpdb->prefix" . $prefix . "_Conference_Tag";
		$wpdb->query($sql);
	}
}
?>
