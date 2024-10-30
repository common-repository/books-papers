<?php
/* 
Plugin Name: Books & Papers
Plugin URI: 
Version: 0.20220219
Author: Research in Theory of Magnetism Department of Taras Shevchenko National University of Kyiv
Author_URI: http://ritm.knu.ua/
Description: Plugin that provides tools for publication management and ability to auto-fill pages with a list of publications.
*/

define('bnpp_plugin_dir', plugin_dir_path(__FILE__)); //defines default plugin directory
//sets some option values that can be changed in settings
add_option('bnpp_custom_db_prefix','Books_n_Papers');
add_option('bnpp_upload_dir', 'downloads');
add_option('bnpp_upload_dir_abs', true);
add_option('bnpp_previous_db_prefix',get_option('custom_db_prefix'));
add_option('bnpp_lstyle_value');
add_option('bnpp_custom_char1_name');
add_option('bnpp_custom_char1_value');
add_option('bnpp_custom_char2_name');
add_option('bnpp_custom_char2_value');
add_option('bnpp_custom_char3_name');
add_option('bnpp_custom_char3_value');
add_option('bnpp_timeout_step',10);
add_option('bnpp_article_head','<h3>Articles</h3>');
add_option('bnpp_article_list','[authors] [title] ([year]) [doi] [arxiv] [filelink] [supplementary]');
add_option('bnpp_conference_head','<h3>Conferences</h3>');
add_option('bnpp_conference_list','[authors] [title] ([year]) [doi] [arxiv] [filelink] [supplementary]');
add_option('bnpp_book_head','<h3>Books</h3>');
add_option('bnpp_book_list','[authors] [title] ([year]) [doi] [arxiv] [filelink] [supplementary]');
add_option('bnpp_ordered_list','checked');
add_option('bnpp_list_division','none');
add_option('bnpp_list_division_style','');
add_option('bnpp_list_order','DESC');
add_option('bnpp_drop_db_tables',false);
add_option('bnpp_tables_created',false);
add_option('bnpp_author_sort','id');
add_option('bnpp_author_sort_order', 'ASC');
add_option('bnpp_paper_sort','date');
add_option('bnpp_paper_sort_order', 'ASC');
//compatibility options
add_option('bnpp_comp_pub', true);
add_option('bnpp_comp_auth', false);
add_option('bnpp_comp_clear', true);
add_option('bnpp_comp_rbracket', "[publications");
add_option('bnpp_comp_lbracket', "]");
add_option('bnpp_comp_perf_default', false);
add_option('bnpp_comp_perf_auth', false);
add_option('bnpp_comp_legacy', false);

class BNPP_BooksNPapers //main class
{
	//db table names
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
	//bradge class that contains dedicated functions
	var $bridge;
	
	function __construct($custom_prefix)
	{
		global $wpdb;
		if(!get_option('bnpp_drop_db_tables'))
		{
			//setting up table names
			$this->dbAuthors = $wpdb->prefix . $custom_prefix . "_Authors";
			$this->dbArticles = $wpdb->prefix . $custom_prefix . "_Articles";
			$this->dbConferences = $wpdb->prefix . $custom_prefix . "_Conferences";
			$this->dbBooks = $wpdb->prefix . $custom_prefix . "_Books";
			$this->dbArticleAuthor = $wpdb->prefix . $custom_prefix . "_Article_Authors";
			$this->dbConferenceAuthor = $wpdb->prefix . $custom_prefix . "_Conference_Authors";
			$this->dbBookAuthor = $wpdb->prefix . $custom_prefix . "_Book_Authors";
			$this->dbBookEditor = $wpdb->prefix . $custom_prefix . "_Book_Editor";
			$this->dbJournals = $wpdb->prefix . $custom_prefix . "_Journals";
			$this->dbTags = $wpdb->prefix . $custom_prefix . "_Tags";
			$this->dbArticleTag = $wpdb->prefix . $custom_prefix . "_Article_Tag";
			$this->dbConferenceTag = $wpdb->prefix . $custom_prefix . "_Conference_Tag";
			$this->dbBookTag = $wpdb->prefix . $custom_prefix . "_Book_Tag";
			if(!get_option('bnpp_tables_created')){
				//setting up tables
				BNPP_Author::CreateTableAuthors($this->dbAuthors);
				BNPP_Article::CreateTableArticles($this->dbArticles);
				BNPP_Conference::CreateTableConferences($this->dbConferences);
				BNPP_Book::CreateTableBooks($this->dbBooks);
			}
			//setting up bridge
			$this->bridge = new BNPP_Bridge($this->dbAuthors, $this->dbArticles, $this->dbConferences, $this->dbBooks, $this->dbArticleAuthor, $this->dbConferenceAuthor, $this->dbBookAuthor, $this->dbBookEditor, $this->dbJournals, $this->dbTags, $this->dbArticleTag, $this->dbConferenceTag, $this->dbBookTag);
			if(!get_option('bnpp_tables_created')){
				$this->bridge->createAdditionalTables();
				$this->bridge->alterTableCharSet();
				update_option('bnpp_tables_created',true);
			}
			if(get_option('bnpp_previous_db_prefix')!=get_option('bnpp_custom_db_prefix'))
			{
				$this->bridge->copyDBInfo();
			}
		}
	}
	
	function printMainAdminPage() //prints content of plugin title page
	{
		include(bnpp_plugin_dir . "./pages/title-page.php");
	}
	function printAddAuthorPage() //prints plugin author addition page
	{
		include(bnpp_plugin_dir . "./pages/add-author-page.php");
		if(isset($_POST['firstName'])&&isset($_POST['lastName'])&& check_admin_referer('bnp_add_author') && current_user_can('publish_pages') && !preg_match("/[\"'*?<>|]$/", $_POST["firstName"]) && !preg_match("/[\"'*?<>|]$/", $_POST["lastName"])) //checks vital inputs for author creation
		{
			$author = new BNPP_Author($this->dbAuthors); //creates author object and fills data fields
			$author->firstName = sanitize_text_field($_POST['firstName']);
			$author->lastName = sanitize_text_field($_POST['lastName']);
			if(!preg_match("/[\"'*?<>|]$/", $_POST["email"])){
				$author->email = sanitize_email($_POST['email']);
			}
			if(!preg_match("/[\"'*?<>|]$/", $_POST["url"])){
				$author->personalUrl = sanitize_text_field($_POST['url']);
			}
			if(!preg_match("/[\"'*?<>|]$/", $_POST["slug"])){
				$author->personalUrl = sanitize_text_field($_POST['slug']);
			}
			$author->InsertAuthorInfo(); //calls author creation function
			$timeout = get_option('bnpp_timeout_step') * 100;
		}
	}
	
	function printManageAuthorsPage() //prints content of author management page
	{
		global $wpdb;
		try{
			$data = $wpdb->get_results("SELECT * FROM $this->dbAuthors ORDER BY " . get_option('bnpp_author_sort') . " " . get_option('bnpp_author_sort_order')); //queries author data from db
			//queries unlisted authors
			$dataAA = $wpdb->get_results("SELECT * FROM " . $this->dbArticleAuthor . " WHERE author_id IS NULL AND author_name IS NOT NULL");
			$dataCA = $wpdb->get_results("SELECT * FROM " . $this->dbConferenceAuthor . " WHERE author_id IS NULL AND author_name IS NOT NULL");
			$dataBA = $wpdb->get_results("SELECT * FROM " . $this->dbBookAuthor . " WHERE author_id IS NULL AND author_name IS NOT NULL");
			$dataBE = $wpdb->get_results("SELECT * FROM " . $this->dbBookEditor . " WHERE editor_id IS NULL AND editor_name IS NOT NULL");
		}catch (Exception $e) {}
		include(bnpp_plugin_dir . "./pages/manage-author-page.php");
		if(isset($_POST["checkManage"])&& check_admin_referer('bnp_manage_authors') && current_user_can('publish_pages')) //checks vitals
		{
			$timeout = get_option('bnpp_timeout_step') * 100;
			if(sanitize_text_field($_POST["checkManage"])=="remove") //checks if remove call was recieved
			{
				if(sanitize_text_field($_POST["answer"])=="yes")
				{ //removes author data from table
					$sql = $wpdb->prepare("DELETE FROM $this->dbAuthors WHERE (id = %d)", sanitize_text_field($_POST['authorID']));
					$wpdb->query($sql);
					echo "<script>window.onload = function () { document.getElementById('remove').innerHTML = 'Author " . sanitize_text_field($_POST['authorID']) . " has been removed.';setTimeout(function() {window.location=document.location.href;},$timeout); } </script>";
				}
			} else if(sanitize_text_field($_POST["checkManage"])=="update") //checks if modification call was recieved
			{
				if(!preg_match("/[\"'*?<>|]$/", $_POST["firstName"]) && !preg_match("/[\"'*?<>|]$/", $_POST["lastName"])){
					$sql = $wpdb->prepare("UPDATE $this->dbAuthors SET first_name = %s, last_name = %s, email = %s, personal_url = %s, slug = %s WHERE (id = %d)", array(sanitize_text_field($_POST["firstName"]), sanitize_text_field($_POST["lastName"]), (!preg_match("/[\"'*?<>|]$/", $_POST["email"]) ? sanitize_email($_POST["email"]) : ""), (!preg_match("/[\"'*?<>|]$/", $_POST["url"]) ? sanitize_text_field($_POST["url"]) : ""), (!preg_match("/[\"'*?<>|]$/", $_POST["slug"]) ? sanitize_text_field($_POST["slug"]) : ""), sanitize_text_field($_POST["authorID"]))); //updates author data in table
					$wpdb->query($sql);
				}
				echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Author " . sanitize_text_field($_POST['authorID']) . " has been updated.';setTimeout(function() {window.location=document.location.href;},$timeout); }</script>";
			}
		}
		if(isset($_POST["mergeList1"])&& check_admin_referer('bnp_merge_authors') && current_user_can('publish_pages'))
		{
			$timeout = get_option('bnpp_timeout_step') * 100;
			$counter=1;
			while($counter<=sanitize_text_field($_POST["numberOfAuthors"]))
			{
				if(sanitize_text_field($_POST["mergeAuthorId"])!=sanitize_text_field($_POST["mergeList" . $counter]))
				{
					$this->bridge->mergeAuthors(sanitize_text_field($_POST["mergeAuthorId"]), sanitize_text_field($_POST["mergeList" . $counter]));
				}
				$counter++;
			}
			echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Author " . sanitize_text_field($_POST['mergeAuthorId']) . " has been updated.';setTimeout(function() {window.location=document.location.href;},$timeout); }</script>";
		}
		if(isset($_POST["sortMethod"])&& check_admin_referer('bnp_sort_authors') && current_user_can('publish_pages'))
		{
			update_option('bnpp_author_sort',sanitize_text_field($_POST["sortMethod"]));
			update_option('bnpp_author_sort_order',sanitize_text_field($_POST["sortMethodOrder"]));
			echo "<script>window.location=document.location.href;</script>";
		}
		if(isset($_POST["promote"])&& check_admin_referer('bnp_promote_authors') && current_user_can('publish_pages') && !preg_match("/[\"'*?<>|]$/", $_POST["firstName"]) && !preg_match("/[\"'*?<>|]$/", $_POST["lastName"]))
		{
			$author = new BNPP_Author($this->dbAuthors); //creates author object and fills data fields
			$author->firstName = sanitize_text_field($_POST['firstName']);
			$author->lastName = sanitize_text_field($_POST['lastName']);
			if(!preg_match("/[\"'*?<>|]$/", $_POST["email"])){
				$author->email = sanitize_email($_POST['email']);
			}
			if(!preg_match("/[\"'*?<>|]$/", $_POST["url"])){
				$author->personalUrl = sanitize_text_field($_POST['url']);
			}
			if(!preg_match("/[\"'*?<>|]$/", $_POST["slug"])){
				$author->personalUrl = sanitize_text_field($_POST['slug']);
			}
			$author->InsertAuthorInfo(); //calls author creation function
			$newAuthor = $wpdb->get_results("SELECT * FROM $this->dbAuthors ORDER BY id DESC LIMIT 1");
			foreach($newAuthor as $na)
			{
				if(sanitize_text_field($_POST["relation"])=="aa")
				{
					$sql = $wpdb->prepare("UPDATE $this->dbArticleAuthor SET author_name = NULL, author_id = %d WHERE (id = %d)", array($na->id, sanitize_text_field($_POST["promote"]))); //updates author data in table
					$wpdb->query($sql);
				} else if (sanitize_text_field($_POST["relation"])=="ca")
				{
					$sql = $wpdb->prepare("UPDATE $this->dbConferenceAuthor SET author_name = NULL, author_id = %d WHERE (id = %d)", array($na->id, sanitize_text_field($_POST["promote"]))); //updates author data in table
					$wpdb->query($sql);
				} else if(sanitize_text_field($_POST["relation"])=="ba")
				{
					$sql = $wpdb->prepare("UPDATE $this->dbBookAuthor SET author_name = NULL, author_id = %d WHERE (id = %d)", array($na->id, sanitize_text_field($_POST["promote"]))); //updates author data in table
					$wpdb->query($sql);
				} else
				{
					$sql = $wpdb->prepare("UPDATE $this->dbEditorAuthor SET editor_name = NULL, editor_id = %d WHERE (id = %d)", array($na->id, sanitize_text_field($_POST["promote"]))); //updates author data in table
					$wpdb->query($sql);
				}
			}
			$timeout = get_option('bnpp_timeout_step') * 100;
			echo "<script>window.onload = function () { document.getElementById('success').innerHTML = 'Author $author->firstName $author->lastName was added.';setTimeout(function() {window.location=document.location.href;},$timeout);  } </script>";
		}
	}
	
	function clean_input($text_input){ //cleans input text data from prohibited symbols
		if(preg_match("/[\"'*<>|]$/", $text_input)){
			$to_replace = array("'", "\"", "*", "<", ">", "|", "\\");
			$text_input = str_replace($to_replace, "", $text_input);
		}
		return $text_input;
	}
	
	function printAddPaperPage() //prints content of paper addition page
	{
		global $wpdb;
		try{
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " ORDER BY last_name,first_name ASC"); //queries author data from db
			$option_values = ""; //presets datalist inner content
			foreach($data as $author) //fills datalist inner content
			{
				$option_values = $option_values . "<option label=\"(Internal) $author->first_name $author->last_name\" value=\"$author->first_name $author->last_name\"></option>";
			}
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbJournals . " ORDER BY journal ASC"); //queries journal data from db
			$j_option_values = ""; //presets datalist inner content
			foreach($data as $journal) //fills datalist inner content
			{
				$j_option_values = $j_option_values . "<option label=\"(Internal)\" value=\"$journal->journal\"></option>";
			}
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " ORDER BY tag ASC"); //queries tag data from db
			$t_option_values = ""; //presets datalist inner content
			foreach($data as $tag) //fills datalist inner content
			{
				$t_option_values = $t_option_values . "<option label=\"(Internal)\" value=\"$tag->tag\"></option>";
			}
		}catch (Exception $e) {}
		include(bnpp_plugin_dir . "./pages/add-paper-page.php");
		if(isset($_POST["title"])&& check_admin_referer('bnp_add_paper') && current_user_can('publish_pages')) //checks vital
		{
			//sets temporal values of custom characteristics
			$char1val = NULL;
			$char2val = NULL;
			$char3val = NULL;
			//checks if custom characteristics were set
			if(isset($_POST["char1"]))
			{
				$char1val = sanitize_text_field($_POST["char1"]); //if true, sets submitted values to variables
			}
			if(isset($_POST["char2"]))
			{
				$char2val = sanitize_text_field($_POST["char2"]);
			}
			if(isset($_POST["char3"]))
			{
				$char3val = sanitize_text_field($_POST["char3"]);
			}
			if(sanitize_text_field($_POST["paperType"])=="article") //inserts article paper info
			{
				$fileName = "";
				if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
					$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
				else
					$fileName = sanitize_mime_type($_FILES['file']['name']);
				$this->bridge->addArticle(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->bridge->checkJournal($this->clean_input(sanitize_text_field($_POST["journal"]))), sanitize_text_field($_POST["volume"]), sanitize_text_field($_POST["issue"]), $char1val, $char2val, $char3val, sanitize_text_field($_POST["preprint"])); //writes article data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbArticles . " ORDER By id DESC LIMIT 1"); //gets id of previously created article
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with article
						$this->bridge->addArticleAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates every tag with article
						$this->bridge->addArticleTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
				if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
				{
					$uploaddir = "";
					if (get_option('bnpp_upload_dir_abs'))
					{
						$uploaddir .= ABSPATH;
					}
					$uploaddir .= get_option('bnpp_upload_dir');
					if (!is_dir($uploaddir))
					{
						mkdir($uploaddir);
					}
					$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
						echo "File was successfully uploaded.\n";
					} else {
						echo "File upload has failed.\n";
					}
				}
			} else if(sanitize_text_field($_POST["paperType"])=="conference") //inserts conference info
			{
				$fileName = "";
				if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
					$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
				else
					$fileName = sanitize_mime_type($_FILES['file']['name']);
				$this->bridge->addConference(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), sanitize_text_field($_POST["bookTitle"]), $this->clean_input(sanitize_text_field($_POST["confPages"])), $char1val, $char2val, $char3val); //writes conference data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbConferences . " ORDER By id DESC LIMIT 1"); //gets id of previously created conference
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with conference
						$this->bridge->addConferenceAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates every tag with conference
						$this->bridge->addConferenceTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
				if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
				{
					$uploaddir = "";
					if (get_option('bnpp_upload_dir_abs'))
					{
						$uploaddir .= ABSPATH;
					}
					$uploaddir .= get_option('bnpp_upload_dir');
					if (!is_dir($uploaddir))
					{
						mkdir($uploaddir);
					}
					$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
						echo "File was successfully uploaded.\n";
					} else {
						echo "File upload has failed.\n";
					}
				}
			} else if(sanitize_text_field($_POST["paperType"])=="book") //inserts book info
			{
				$fileName = "";
				if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
					$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
				else
					$fileName = sanitize_mime_type($_FILES['file']['name']);
				$this->bridge->addBook(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->clean_input(sanitize_text_field($_POST["publisher"])), $this->clean_input(sanitize_text_field($_POST["chapter"])), $this->clean_input(sanitize_text_field($_POST["isbn"])), $char1val, $char2val, $char3val); //writes book data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbBooks . " ORDER By id DESC LIMIT 1"); //gets id of previously created book
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with book
						$this->bridge->addBookAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["editor" . $k]))
					{ //relates every editor with book
						$this->bridge->addBookEditor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["editor" . $k]))), $art_id);
					}
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates every tag with book
						$this->bridge->addBookTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
				if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
				{
					$uploaddir = "";
					if (get_option('bnpp_upload_dir_abs'))
					{
						$uploaddir .= ABSPATH;
					}
					$uploaddir .= get_option('bnpp_upload_dir');
					if (!is_dir($uploaddir))
					{
						mkdir($uploaddir);
					}
					$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
					if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
						echo "File was successfully uploaded.\n";
					} else {
						echo "File upload has failed.\n";
					}
				}
			}
		}
	}
	
	function printManagePapersPage() //prints content of paper management page
	{
		global $wpdb;
		try{
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbArticles . " ORDER BY " . get_option('bnpp_paper_sort') . " " . get_option('bnpp_paper_sort_order')); //queries article data from db
			$data5 = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " ORDER BY last_name ASC"); //queries author data from db
			$option_values = ""; //presets datalist inner content
			foreach($data5 as $author) //fills datalist inner content
			{
				$option_values = $option_values . "<option label=\'(Internal) $author->first_name $author->last_name\' value=\'$author->first_name $author->last_name\'></option>";
			}
			$data5 = $wpdb->get_results("SELECT * FROM " . $this->dbJournals . " ORDER BY journal ASC"); //queries journal data from db
			$j_option_values = ""; //presets datalist inner content
			foreach($data5 as $journal) //fills datalist inner content
			{
				$j_option_values = $j_option_values . "<option label=\"(Internal)\" value=\"$journal->journal\"></option>";
			}
			$data5 = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " ORDER BY tag ASC"); //queries tag data from db
			$t_option_values = ""; //presets datalist inner content
			foreach($data5 as $tag) //fills datalist inner content
			{
				$t_option_values = $t_option_values . "<option label=\'(Internal)\' value=\'$tag->tag\'></option>";
			}
			$dataC = $wpdb->get_results("SELECT * FROM " . $this->dbConferences . " ORDER BY " . get_option('bnpp_paper_sort') . " " . get_option('bnpp_paper_sort_order')); //queries conference data from db
			$dataB = $wpdb->get_results("SELECT * FROM " . $this->dbBooks . " ORDER BY " . get_option('bnpp_paper_sort') . " " . get_option('bnpp_paper_sort_order')); //queries book data from db
		}catch (Exception $e) {}
		include(bnpp_plugin_dir . "./pages/manage-papers-page.php");
		if(isset($_POST["checkManage"])&& check_admin_referer('bnp_manage_papers') && current_user_can('publish_pages')) //checks vitals
		{
			if(isset($_POST["journal"])) //if article
			{
				if(sanitize_text_field($_POST["checkManage"])=="remove") //remove call
				{
					$this->bridge->removeArticle(sanitize_text_field($_POST["answer"]), sanitize_text_field($_POST['articleID']));
				}
				else if(sanitize_text_field($_POST["checkManage"])=="update") //update call
				{
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbArticleAuthor WHERE (article_id = %d)", sanitize_text_field($_POST['articleID']))); //clears all authors related to article
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["author" . $k]) && sanitize_text_field($_POST["author" . $k])!="")
						{ //creates new relations between article and authors
							$this->bridge->addArticleAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbArticleTag WHERE (article_id = %d)", sanitize_text_field($_POST['articleID']))); //clears all tags related to article
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["tag" . $k]) && sanitize_text_field($_POST["tag" . $k])!="")
						{ //creates new relations between article and tags
							$this->bridge->addArticleTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$char1val = "";
					$char2val = "";
					$char3val = "";
					if(isset($_POST["char1"]))
					{
						$char1val = sanitize_text_field($_POST["char1"]);
					}
					if(isset($_POST["char2"]))
					{
						$char2val = sanitize_text_field($_POST["char2"]);
					}
					if(isset($_POST["char3"]))
					{
						$char3val = sanitize_text_field($_POST["char3"]);
					}
					$fileName = "";
					if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
						$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
					else
						$fileName = sanitize_mime_type($_FILES['file']['name']);
					$this->bridge->manageArticle(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->bridge->checkJournal($this->clean_input(sanitize_text_field($_POST["journal"]))), sanitize_text_field($_POST["volume"]), sanitize_text_field($_POST["issue"]), sanitize_text_field($_POST["articleID"]), $char1val, $char2val, $char3val, sanitize_text_field($_POST["preprint"]));
					if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
					{
						$uploaddir = "";
						if (get_option('bnpp_upload_dir_abs'))
						{
							$uploaddir .= ABSPATH;
						}
						$uploaddir .= get_option('bnpp_upload_dir');
						if (!is_dir($uploaddir))
						{
							mkdir($uploaddir);
						}
						$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
						if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
							echo "File was successfully uploaded.\n";
						} else {
							echo "File upload has failed.\n";
						}
					}
					if(isset($_POST["file_d"]) && sanitize_text_field($_POST["file_d"])=="true" && sanitize_text_field($_POST["file_d_name"]) != "")
					{
						$this->bridge->deleteFile(sanitize_text_field($_POST["file_d_name"]));
					}
				}
			}
			else if(isset($_POST["bookTitle"])) //if conference
			{
				if(sanitize_text_field($_POST["checkManage"])=="remove") //remove call
				{
					$this->bridge->removeConference(sanitize_text_field($_POST["answer"]), sanitize_text_field($_POST['articleID']));
				}
				else if(sanitize_text_field($_POST["checkManage"])=="update") //update call
				{
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbConferenceAuthor WHERE (conference_id = %d)", sanitize_text_field($_POST['articleID'])));
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["author" . $k]) && sanitize_text_field($_POST["author" . $k])!="")
						{
							$this->bridge->addConferenceAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbConferenceTag WHERE (conference_id = %d)", sanitize_text_field($_POST['articleID']))); //clears all tags related to conference
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["tag" . $k]) && sanitize_text_field($_POST["tag" . $k])!="")
						{ //creates new relations between conference and tags
							$this->bridge->addConferenceTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$char1val = "";
					$char2val = "";
					$char3val = "";
					if(isset($_POST["char1"]))
					{
						$char1val = sanitize_text_field($_POST["char1"]);
					}
					if(isset($_POST["char2"]))
					{
						$char2val = sanitize_text_field($_POST["char2"]);
					}
					if(isset($_POST["char3"]))
					{
						$char3val = sanitize_text_field($_POST["char3"]);
					}
					$fileName = "";
					if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
						$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
					else
						$fileName = sanitize_mime_type($_FILES['file']['name']);
					$this->bridge->manageConference(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), sanitize_text_field($_POST["bookTitle"]), $this->clean_input(sanitize_text_field($_POST["confPages"])), sanitize_text_field($_POST["articleID"]), $char1val, $char2val, $char3val);
					if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
					{
						$uploaddir = "";
						if (get_option('bnpp_upload_dir_abs'))
						{
							$uploaddir .= ABSPATH;
						}
						$uploaddir .= get_option('bnpp_upload_dir');
						if (!is_dir($uploaddir))
						{
							mkdir($uploaddir);
						}
						$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
						if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
							echo "File was successfully uploaded.\n";
						} else {
							echo "File upload has failed.\n";
						}
					}
					if(isset($_POST["file_d"]) && sanitize_text_field($_POST["file_d"])=="true" && sanitize_text_field($_POST["file_d_name"]) != "")
					{
						$this->bridge->deleteFile(sanitize_text_field($_POST["file_d_name"]));
					}
				}
			}
			else if(isset($_POST["publisher"])) //if book
			{
				if(sanitize_text_field($_POST["checkManage"])=="remove") //remove call
				{
					$this->bridge->removeBook(sanitize_text_field($_POST["answer"]), sanitize_text_field($_POST['articleID']));
				}
				else if(sanitize_text_field($_POST["checkManage"])=="update") //update call
				{
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbBookAuthor WHERE (book_id = %d)", sanitize_text_field($_POST['articleID'])));
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["author" . $k]) && sanitize_text_field($_POST["author" . $k])!="")
						{
							$this->bridge->addBookAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbBookEditor WHERE (book_id = %d)", sanitize_text_field($_POST['articleID'])));
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["editor" . $k]) && sanitize_text_field($_POST["editor" . $k])!="")
						{
							$this->bridge->addBookEditor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["editor" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$wpdb->query($wpdb->prepare("DELETE FROM $this->dbBookTag WHERE (book_id = %d)", sanitize_text_field($_POST['articleID']))); //clears all tags related to book
					for($k=0;$k<=20;$k++)
					{
						if(isset($_POST["tag" . $k]) && sanitize_text_field($_POST["tag" . $k])!="")
						{ //creates new relations between book and tags
							$this->bridge->addBookTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), sanitize_text_field($_POST['articleID']));
						}
					}
					$char1val = "";
					$char2val = "";
					$char3val = "";
					if(isset($_POST["char1"]))
					{
						$char1val = sanitize_text_field($_POST["char1"]);
					}
					if(isset($_POST["char2"]))
					{
						$char2val = sanitize_text_field($_POST["char2"]);
					}
					if(isset($_POST["char3"]))
					{
						$char3val = sanitize_text_field($_POST["char3"]);
					}
					$fileName = "";
					if(isset($_POST["file_m"]) && sanitize_text_field($_POST["file_m"])!="")
						$fileName = $this->clean_input(sanitize_text_field($_POST["file_m"]));
					else
						$fileName = sanitize_mime_type($_FILES['file']['name']);
					$this->bridge->manageBook(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $fileName, sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->clean_input(sanitize_text_field($_POST["publisher"])), $this->clean_input(sanitize_text_field($_POST["chapter"])), $this->clean_input(sanitize_text_field($_POST["isbn"])), sanitize_text_field($_POST["articleID"]), $char1val, $char2val, $char3val);
					if(isset($_FILES['file']) && sanitize_mime_type($_FILES['file']['name']) != "")
					{
						$uploaddir = "";
						if (get_option('bnpp_upload_dir_abs'))
						{
							$uploaddir .= ABSPATH;
						}
						$uploaddir .= get_option('bnpp_upload_dir');
						if (!is_dir($uploaddir))
						{
							mkdir($uploaddir);
						}
						$uploadfile = $uploaddir . '/' . basename(sanitize_mime_type($_FILES['file']['name']));
						if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
							echo "File was successfully uploaded.\n";
						} else {
							echo "File upload has failed.\n";
						}
					}
					if(isset($_POST["file_d"]) && sanitize_text_field($_POST["file_d"])=="true" && sanitize_text_field($_POST["file_d_name"]) != "")
					{
						$this->bridge->deleteFile(sanitize_text_field($_POST["file_d_name"]));
					}
				}
			}
		}
		if(isset($_POST["sortMethod"])&& check_admin_referer('bnp_sort_papers') && current_user_can('publish_pages'))
		{
			update_option('bnpp_paper_sort',sanitize_text_field($_POST["sortMethod"]));
			update_option('bnpp_paper_sort_order',sanitize_text_field($_POST["sortMethodOrder"]));
			echo "<script>window.location=document.location.href;</script>";
		}
	}
	
	function printImportDataPage() //prints content of data import page
	{
		global $wpdb;
		try{
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbAuthors . " ORDER BY last_name,first_name ASC"); //queries author data from db
			$option_values = ""; //presets datalist inner content
			foreach($data as $author) //fills datalist inner content
			{
				$option_values = $option_values . "<option label=\"(Internal) $author->first_name $author->last_name\" value=\"$author->first_name $author->last_name\"></option>";
			}
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbJournals . " ORDER BY journal ASC"); //queries journal data from db
			$j_option_values = ""; //presets datalist inner content
			foreach($data as $journal) //fills datalist inner content
			{
				$j_option_values = $j_option_values . "<option label=\"(Internal)\" value=\"$journal->journal\"></option>";
			}
			$data = $wpdb->get_results("SELECT * FROM " . $this->dbTags . " ORDER BY tag ASC"); //queries tag data from db
			$t_option_values = ""; //presets datalist inner content
			foreach($data as $tag) //fills datalist inner content
			{
				$t_option_values = $t_option_values . "<option label=\'(Internal)\' value=\'$tag->tag\'></option>";
			}
			$newAuthors = "<script>window.onload = function () { document.getElementById('bibInputs').innerHTML = 'Next Authors were added: "; //presets function that shows which new authors were imported
		}catch (Exception $e) {}
		include(bnpp_plugin_dir . "./pages/import-data-page.php");
		if(isset($_POST["absManUpload"]) && sanitize_text_field($_POST["absManUpload"]) != "" && check_admin_referer('bnp_get_abstract') && current_user_can('publish_pages'))
		{
			$result = $this->bridge->requestAbstractByDOI(sanitize_text_field($_POST["absManUpload"]));
			//echo "<script>window.onload = function() { if(document.getElementById(\"loadAnimWrapper\")){ document.getElementById(\"loadAnimWrapper\").style = \"display:none;\"; }; document.getElementById('abstractInputHide').outerHTML = '';document.getElementById('abstractInputFields').innerHTML = '" . $result . "';}</script>";
			if(strpos($result, "abstract")){
				$result = (explode("abstract", $result)[1]);
				$result = substr($result, strpos($result, ">") + 1, strrpos($result, "<") - strpos($result, ">") - 1);
				$text = strip_tags($result);
				$result = "<h3>Abstract:</h3><p>$text</p>";
				//echo "<script>window.onload = function() { if(document.getElementById(\"loadAnimWrapper\")){ document.getElementById(\"loadAnimWrapper\").style = \"display:none;\"; }; document.getElementById('abstractInputHide').outerHTML = '';document.getElementById('abstractInputFields').innerHTML = '" . $result . "';}</script>";
			} else {
				$result = "Abstract not found.";
				//echo "<script>window.onload = function() { document.getElementById('abstractInputFields').innerHTML = '" . $result . "';}</script>";
			}
			echo $result;
		}
		if(isset($_POST["doiUpload"]) && sanitize_text_field($_POST["doiUpload"]) != ""&& check_admin_referer('bnp_doi_upload') && current_user_can('publish_pages'))
		{
			$result = $this->bridge->requestDataByDOI(sanitize_text_field($_POST["doiUpload"]));
			echo "<script>window.onload = function() { if(document.getElementById(\"loadAnimWrapper\")){ document.getElementById(\"loadAnimWrapper\").style = \"display:none;\"; }; document.getElementById('importDoiSubmit').innerHTML = '';document.getElementById('importDoiSubmit').style = 'display:none';document.getElementById('doiInputHide').innerHTML = '';document.getElementById('doiInputFields').innerHTML = '" . $result . "';doiFillDataFields();}</script>";
			if($result == 'Document Not Found')
			{
				echo "<script>window.onload = function() { document.getElementById('doiInputFields').innerHTML = '" . $result . "';}</script>";
			} else
			{
				echo "<script>window.onload = function() { if(document.getElementById(\"loadAnimWrapper\")){ document.getElementById(\"loadAnimWrapper\").style = \"display:none;\"; }; document.getElementById('importDoiSubmit').innerHTML = '';document.getElementById('importDoiSubmit').style = 'display:none';document.getElementById('doiInputHide').innerHTML = '';document.getElementById('doiInputFields').innerHTML = '" . $this->bridge->handleDOIData($result, $j_option_values, $t_option_values, $option_values) . "';doiFillDataFields();}</script>";
			}
		}
		if(isset($_POST["paperType"])&& check_admin_referer('bnp_doi_upload') && current_user_can('publish_pages'))
		{
			//sets temporal values of custom characteristics
			$char1val = NULL;
			$char2val = NULL;
			$char3val = NULL;
			//checks if custom characteristics were set
			if(isset($_POST["char1"]))
			{
				$char1val = sanitize_text_field($_POST["char1"]); //if true, sets submitted values to variables
			}
			if(isset($_POST["char2"]))
			{
				$char2val = sanitize_text_field($_POST["char2"]);
			}
			if(isset($_POST["char3"]))
			{
				$char3val = sanitize_text_field($_POST["char3"]);
			}
			if($_POST["paperType"]=="article") //inserts article paper info
			{
				$this->bridge->addArticle(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $this->clean_input(sanitize_text_field($_POST["file"])), sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->bridge->checkJournal($this->clean_input(sanitize_text_field($_POST["journal"]))), sanitize_text_field($_POST["volume"]), sanitize_text_field($_POST["issue"]), $char1val, $char2val, $char3val, sanitize_text_field($_POST["preprint"])); //writes article data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbArticles . " ORDER By id DESC LIMIT 1"); //gets id of previously created article
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with article
						$this->bridge->addArticleAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				if(isset($_POST["tag"]))
				{ //relates tags with article
					$this->bridge->addArticleTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag"]))), $art_id);
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates tags with article
						$this->bridge->addArticleTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
			} else if(sanitize_text_field($_POST["paperType"])=="conference") //inserts conference info
			{
				$this->bridge->addConference(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $this->clean_input(sanitize_text_field($_POST["file"])), sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), sanitize_text_field($_POST["bookTitle"]), $this->clean_input(sanitize_text_field($_POST["confPages"])), $char1val, $char2val, $char3val); //writes conference data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbConferences . " ORDER By id DESC LIMIT 1"); //gets id of previously created conference
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with conference
						$this->bridge->addConferenceAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				if(isset($_POST["tag"]))
				{ //relates tags with article
					$this->bridge->addConferenceTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag"]))), $art_id);
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates tags with article
						$this->bridge->addConferenceTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
			} else if(sanitize_text_field($_POST["paperType"])=="book") //inserts book info
			{
				$this->bridge->addBook(sanitize_text_field($_POST["title"]), sanitize_text_field($_POST["year"]), $this->clean_input(sanitize_text_field($_POST["pages"])), $this->clean_input(sanitize_text_field($_POST["doi"])), $this->clean_input(sanitize_text_field($_POST["url"])), $this->clean_input(sanitize_text_field($_POST["issn"])), $this->clean_input(sanitize_text_field($_POST["supp"])), $this->clean_input(sanitize_text_field($_POST["file"])), sanitize_text_field($_POST["date"]), sanitize_text_field($_POST["public"]), $this->clean_input(sanitize_text_field($_POST["arxiv"])), $this->clean_input(sanitize_text_field($_POST["publisher"])), $this->clean_input(sanitize_text_field($_POST["chapter"])), $this->clean_input(sanitize_text_field($_POST["isbn"])), $char1val, $char2val, $char3val); //writes book data in table
				$art_id;
				$data = $wpdb->get_results("SELECT id FROM " . $this->dbBooks . " ORDER By id DESC LIMIT 1"); //gets id of previously created book
				foreach($data as $el)
				{
					$art_id = $el->id; //sets queried value to variable
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["author" . $k]))
					{ //relates every author with book
						$this->bridge->addBookAuthor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["author" . $k]))), $art_id);
					}
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["editor" . $k]))
					{ //relates every editor with book
						$this->bridge->addBookEditor($this->bridge->checkAuthor($this->clean_input(sanitize_text_field($_POST["editor" . $k]))), $art_id);
					}
				}
				if(isset($_POST["tag"]))
				{ //relates tags with article
					$this->bridge->addBookTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag"]))), $art_id);
				}
				for($k = 0; $k <= 20; $k++)
				{
					if(isset($_POST["tag" . $k]))
					{ //relates tags with article
						$this->bridge->addBookTag($this->bridge->checkTag($this->clean_input(sanitize_text_field($_POST["tag" . $k]))), $art_id);
					}
				}
			}
			$timeout = get_option('bnpp_timeout_step') * 100;
			echo "<script>window.onload = function () { document.getElementById('doiInputFields').innerHTML = 'Publication " . sanitize_text_field($_POST["title"]) . " was added.';setTimeout(function() {window.location=document.location.href;},$timeout);  } </script>";
		}
		if(isset($_POST["bibUpload"])&& check_admin_referer('bnp_bib_upload') && current_user_can('publish_pages'))
		{
			for($k = 0; $k<sanitize_text_field($_POST["k"]); $k++) //for each imported publication
			{
				if(isset($_POST["article" . $k]))
				{
					$newAuthors .= $this->bridge->importArticle(mb_convert_encoding(sanitize_text_field($_POST["article" . $k]),'Windows-1251'));//imports article
				} else if(isset($_POST["book" . $k]))
				{
					$newAuthors .= $this->bridge->importBook(mb_convert_encoding(sanitize_text_field($_POST["book" . $k]),'Windows-1251'));//imports book
				} else if(isset($_POST["conference" . $k]))
				{
					$newAuthors .= $this->bridge->importConference(mb_convert_encoding(sanitize_text_field($_POST["conference" . $k]),'Windows-1251'));//imports conference
				}
			}
			$timeout = get_option('bnpp_timeout_step') * 100;
			if($newAuthors == "<script>window.onload = function () { document.getElementById('bibInputs').innerHTML = 'Next Authors were added: ") //checks if any new authors were added
			{
				echo "<script>window.onload = function () { document.getElementById('bibInputs').innerHTML = 'No new Authors were added.';setTimeout(function() {window.location=document.location.href;},$timeout);} </script>"; //if NOT shows that no new authors were added
			} else
			{
				$newAuthors .= "';setTimeout(function() {window.location=document.location.href;},$timeout); } </script>"; //if TRUE shows which authors were added
				echo $newAuthors;
			}
		}
	}
	
	function printSettingsPage() //prints content of settings sub-page
	{
		global $wpdb;
		include(bnpp_plugin_dir . "./pages/settings-page.php");
		if(isset($_POST["prefix"])&& check_admin_referer('bnp_prefix_setting') && current_user_can('publish_pages')) //checks if db prefix was set
		{
			if(sanitize_text_field($_POST["prefix"]) != "" && preg_match("/^[a-zA-Z0-9_-]*$/", $_POST["prefix"])) //if not empty, sets new prefix value
			{
				update_option('bnpp_tables_created', false);
				update_option('bnpp_custom_db_prefix',sanitize_text_field($_POST["prefix"]));
				echo "<script type='text/javascript'> window.location=document.location.href;</script>";
			} else {
				echo "<script type='text/javascript'> alert('The prefix cannot contain special characters. Please, use letters, numbers, dash and underscore only!'); window.location=document.location.href;</script>";
			}
		}
		//sets compatibility settings
		if(isset($_POST["comp_rbracket"])&& check_admin_referer('bnp_compatibility_setting') && current_user_can('publish_pages'))
		{
			if(preg_match("/^[a-zA-Z0-9_\[\](){}-]*$/", $_POST["comp_rbracket"])){
				update_option('bnpp_comp_rbracket',sanitize_text_field($_POST["comp_rbracket"]));
			} else {
				echo "<script type='text/javascript'> alert('The brackets cannot contain special characters. Please, use letters, numbers, dash, brackets and underscore only!'); window.location=document.location.href;</script>";
			}
			if(preg_match("/^[a-zA-Z0-9_\[\](){}-]*$/", $_POST["comp_lbracket"])){
				update_option('bnpp_comp_lbracket',sanitize_text_field($_POST["comp_lbracket"]));
			} else {
				echo "<script type='text/javascript'> alert('The brackets cannot contain special characters. Please, use letters, numbers, dash, brackets and underscore only!'); window.location=document.location.href;</script>";
			}
			if(isset($_POST["comp_pub"]))
				update_option('bnpp_comp_pub',true);
			else
				update_option('bnpp_comp_pub',false);
			if(isset($_POST["comp_legacy"]))
				update_option('bnpp_comp_legacy',true);
			else
				update_option('bnpp_comp_legacy',false);
			if(isset($_POST["comp_auth"]))
				update_option('bnpp_comp_auth',true);
			else
				update_option('bnpp_comp_auth',false);
			if(isset($_POST["comp_clear"]))
				update_option('bnpp_comp_clear',true);
			else
				update_option('bnpp_comp_clear',false);
			if(isset($_POST["comp_perf_default"]))
				update_option('bnpp_comp_perf_default',true);
			else
				update_option('bnpp_comp_perf_default',false);
			if(isset($_POST["comp_perf_auth"]))
				update_option('bnpp_comp_perf_auth',true);
			else
				update_option('bnpp_comp_perf_auth',false);
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		//sets file upload directory
		if(isset($_POST["upload_abs_hidden"])&& check_admin_referer('bnp_upload_dir_setting') && current_user_can('publish_pages'))
		{
			if(!preg_match("/[\"'*?<>|]$/", $_POST["upload"])){
				update_option('bnpp_upload_dir',sanitize_text_field($_POST["upload"]));
			} else {
				echo "<script type='text/javascript'> alert('Cannot accept the upload folder path. Please, avoid using these symbols for the folder: \", \', *, ?, <, >, |'); window.location=document.location.href;</script>";
			}
			if(isset($_POST["upload_abs"]))
				update_option('bnpp_upload_dir_abs',true);
			else
				update_option('bnpp_upload_dir_abs',false);
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		//sets publication list options
		if(isset($_POST["article_list"])&& check_admin_referer('bnp_list_setting') && current_user_can('publish_pages'))
		{
			$kses_filter = array('h1' => array('style' => array(), 'class' => array()),'h2' => array('style' => array(), 'class' => array()),'h3' => array('style' => array(), 'class' => array()),'h4' => array('style' => array(), 'class' => array()),'h5' => array('style' => array(), 'class' => array()),'h6' => array('style' => array(), 'class' => array()),'h7' => array('style' => array(), 'class' => array()),'h8' => array('style' => array(), 'class' => array()),'a' => array('href' => array(), 'target' => array(), 'rel' => array(), 'type' => array(), 'download' => array(), 'style' => array(), 'class' => array()),'i' => array('style' => array(), 'class' => array()),'b' => array('style' => array(), 'class' => array()),'div' => array('style' => array(), 'class' => array()),'strong' => array('style' => array(), 'class' => array()),'br' => array('style' => array(), 'class' => array()),'p' => array('style' => array(), 'class' => array()));
			update_option('bnpp_article_head',wp_kses($_POST["article_head"], $kses_filter));
			update_option('bnpp_article_list',wp_kses($_POST["article_list"], $kses_filter));
			update_option('bnpp_conference_head',wp_kses($_POST["conference_head"], $kses_filter));
			update_option('bnpp_conference_list',wp_kses($_POST["conference_list"], $kses_filter));
			update_option('bnpp_book_head',wp_kses($_POST["book_head"], $kses_filter));
			update_option('bnpp_book_list',wp_kses($_POST["book_list"], $kses_filter));
			if(isset($_POST["orderedList"]))
			{
				update_option('bnpp_ordered_list','checked');
			} else
			{
				update_option('bnpp_ordered_list','');
			}
			update_option('bnpp_list_division',sanitize_text_field($_POST["listDivision"]));
			if(preg_match("/^[a-zA-Z0-9_.,:; #()%-]*$/", $_POST["listDivisionStyle"])){
				update_option('bnpp_list_division_style',sanitize_text_field($_POST["listDivisionStyle"]));
			} else {
				echo "<script type='text/javascript'> alert('Style input contains disallowed symbols.'); window.location=document.location.href;</script>";
			}
			update_option('bnpp_list_order',sanitize_text_field($_POST["listOrder"]));
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		if(isset($_POST["lstylevalue"])&& check_admin_referer('bnp_lstyle_setting') && current_user_can('publish_pages'))
		{
			if(preg_match("/^[a-zA-Z0-9_.,:; #()%-]*$/", $_POST["lstylevalue"])){
				update_option('bnpp_lstyle_value',sanitize_text_field($_POST["lstylevalue"]));
				echo "<script type='text/javascript'> window.location=document.location.href;</script>";
			} else {
				echo "<script type='text/javascript'> alert('Style input contains disallowed symbols.'); window.location=document.location.href;</script>";
			}
		}
		//sets characteristics values
		if(isset($_POST["char1name"])&& check_admin_referer('bnp_char1_setting') && current_user_can('publish_pages'))
		{
			if(preg_match("/^[a-zA-Z0-9_-]*$/", $_POST["char1name"])){
				update_option('bnpp_custom_char1_name',sanitize_text_field($_POST["char1name"]));
			} else {
				echo "<script type='text/javascript'> alert('The characteristic name cannot contain special characters. Please, use letters, numbers, dash and underscore only!'); window.location=document.location.href;</script>";
			}
			if(preg_match("/^[a-zA-Z0-9_.,:; #()%-]*$/", $_POST["char1value"])){
				update_option('bnpp_custom_char1_value',sanitize_text_field($_POST["char1value"]));
			} else {
				echo "<script type='text/javascript'> alert('Style input contains disallowed symbols.'); window.location=document.location.href;</script>";
			}
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		if(isset($_POST["char2name"])&& check_admin_referer('bnp_char2_setting') && current_user_can('publish_pages'))
		{
			if(preg_match("/^[a-zA-Z0-9_-]*$/", $_POST["char2name"])){
				update_option('bnpp_custom_char2_name',sanitize_text_field($_POST["char2name"]));
			} else {
				echo "<script type='text/javascript'> alert('The characteristic name cannot contain special characters. Please, use letters, numbers, dash and underscore only!'); window.location=document.location.href;</script>";
			}
			if(preg_match("/^[a-zA-Z0-9_.,:; #()%-]*$/", $_POST["char2value"])){
				update_option('bnpp_custom_char2_value',sanitize_text_field($_POST["char2value"]));
			} else {
				echo "<script type='text/javascript'> alert('Style input contains disallowed symbols.'); window.location=document.location.href;</script>";
			}
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		if(isset($_POST["char3name"])&& check_admin_referer('bnp_char3_setting') && current_user_can('publish_pages'))
		{
			if(preg_match("/^[a-zA-Z0-9_-]*$/", $_POST["char3name"])){
				update_option('bnpp_custom_char3_name',sanitize_text_field($_POST["char3name"]));
			} else {
				echo "<script type='text/javascript'> alert('The characteristic name cannot contain special characters. Please, use letters, numbers, dash and underscore only!'); window.location=document.location.href;</script>";
			}
			if(preg_match("/^[a-zA-Z0-9_.,:; #()%-]*$/", $_POST["char3value"])){
				update_option('bnpp_custom_char3_value',sanitize_text_field($_POST["char3value"]));
			} else {
				echo "<script type='text/javascript'> alert('Style input contains disallowed symbols.'); window.location=document.location.href;</script>";
			}
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		if(isset($_POST["timestep"])&& check_admin_referer('bnp_timestep_setting') && current_user_can('publish_pages'))
		{
			update_option('bnpp_timeout_step',sanitize_text_field($_POST["timestep"]));
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		if(isset($_POST["drop_db"])&& check_admin_referer('bnp_dropdb_setting') && current_user_can('promote_users'))
		{
			if(get_option('bnpp_drop_db_tables'))
			{
				update_option('bnpp_drop_db_tables',false);
				update_option('bnpp_tables_created',false);
			} else
			{
				$this->bridge->dropTables(get_option('bnpp_custom_db_prefix'));
				update_option('bnpp_drop_db_tables',true);
				update_option('bnpp_db_import_debug', 'No logs');
			}
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
	}
}

//load additional classes
include(bnpp_plugin_dir . "./classes/author.php");
include(bnpp_plugin_dir . "./classes/item.php");
include(bnpp_plugin_dir . "./classes/article.php");
include(bnpp_plugin_dir . "./classes/conference.php");
include(bnpp_plugin_dir . "./classes/book.php");
include(bnpp_plugin_dir . "./classes/bridge.php");

//create object of MyPapersUpdated class
$bnp = new BNPP_BooksNPapers(get_option('bnpp_custom_db_prefix', '_Books_n_Papers_'));
function bnpp_AdminPage() //creates admin menu page
{
	global $bnp;
	if (function_exists('add_menu_page'))
	{ //hooks admin menu pages
		add_menu_page('Books and Papers', 'Books and Papers', 'publish_pages', 'bnpp_books-n-papers.php', array(&$bnp, 'printMainAdminPage'), 'dashicons-welcome-add-page', 7);
		add_submenu_page('bnpp_books-n-papers.php', 'Add Author', 'Add Author', 'publish_pages', 'bnpp_add-author-u.php', array(&$bnp, 'printAddAuthorPage'));
		add_submenu_page('bnpp_books-n-papers.php', 'Manage Authors', 'Manage Authors', 'publish_pages', 'bnpp_manage-authors-u.php', array(&$bnp, 'printManageAuthorsPage'));
		add_submenu_page('bnpp_books-n-papers.php', 'Add Work', 'Add Work', 'publish_pages', 'bnpp_add-paper-u.php', array(&$bnp, 'printAddPaperPage'));
		add_submenu_page('bnpp_books-n-papers.php', 'Manage Works', 'Manage Works', 'publish_pages', 'bnpp_manage-papers-u.php', array(&$bnp, 'printManagePapersPage'));
		add_submenu_page('bnpp_books-n-papers.php', 'Import Works', 'Import Works', 'publish_pages', 'bnpp_import-papers-u.php', array(&$bnp, 'printImportDataPage'));
		add_options_page('Books and Papers', 'Books and Papers', 'publish_pages', 'bnpp_paper-settings-u.php', array(&$bnp, 'printSettingsPage'));
	}
}
add_action('admin_menu','bnpp_AdminPage'); //hooks plugin menu page to admin menu
//adds filters to fill tagged text with data
add_filter('the_content', array($bnp->bridge ,'replacePublications'));
add_filter('the_content', array($bnp->bridge ,'replaceAuthorName'));
//adding display style to the attribute whitelist
add_filter( 'safe_style_css', function( $styles ) { $styles[] = 'display'; return $styles;} );
?>