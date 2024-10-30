<?php
class BNPP_Book extends BNPP_Item //specified class for books
{
	var $publisher;
	var $chapter;
	var $isbn;
	
	function __construct($table_name)
	{
		$this->table_name = $table_name;
	}
	
	static function CreateTableBooks($table) //creates books table
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
			publisher TINYTEXT NOT NULL,
			chapter TINYTEXT,
			isbn TINYTEXT,
			arxiv TINYTEXT,
			PRIMARY KEY  (id)
			);";
			
			$wpdb->query($sql);
		}
	}
	
	function InsertBookInfo() //inserts book info
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
			"publisher"=>$this->publisher,
			"chapter"=>$this->chapter,
			"isbn"=>$this->isbn,
			"arxiv"=>$this->arxiv,
			"char1"=>$this->char1,
			"char2"=>$this->char2,
			"char3"=>$this->char3
		));
	}
}
?>