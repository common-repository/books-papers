<?php
class BNPP_Conference extends BNPP_Item //specified class for conferences
{
	var $bookTitle;
	var $confPages;
	
	function __construct($table_name)
	{
		$this->table_name = $table_name;
	}
	
	static function CreateTableConferences($table) //creates conferences table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$table'") != $table) 
		{
			$sql = "CREATE TABLE $table (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			title TINYTEXT NOT NULL,
			year MEDIUMINT NOT NULL,
			pages TINYTEXT,
			doi TINYTEXT,
			url TINYTEXT,
			issn TINYTEXT,
			supplementary TINYTEXT,
			file_link MEDIUMTEXT,
			date DATE,
			is_public TINYINT(1),
			char1 TINYINT(1),
			char2 TINYINT(1),
			char3 TINYINT(1),
			book_title TINYTEXT NOT NULL,
			conf_page TINYTEXT,
			arxiv TINYTEXT,
			PRIMARY KEY  (id)
			);";
			
			$wpdb->query($sql);
		}
	}
	
	function InsertConferenceInfo() //inserts conference info
	{
		global $wpdb;
		$wpdb->insert($this->table_name,array(
			"title"=>$this->title,
			"year"=>$this->year,
			"pages"=>$this->pages,
			"doi"=>$this->doi,
			"url"=>$this->url,
			"issn"=>$this->issn,
			"supplementary"=>$this->supplementary,
			"file_link"=>$this->fileLink,
			"date"=>$this->date,
			"is_public"=>$this->isPublic,
			"book_title"=>$this->bookTitle,
			"conf_page"=>$this->confPages,
			"arxiv"=>$this->arxiv,
			"char1"=>$this->char1,
			"char2"=>$this->char2,
			"char3"=>$this->char3
		));
	}
}
?>