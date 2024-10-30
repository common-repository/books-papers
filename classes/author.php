<?php
class BNPP_Author //class for authors
{
	var $table_name;
	var $firstName;
	var $lastName;
	var $email;
	var $personalUrl;
	var $slug;
	
	function __construct($table_name)
	{
		$this->table_name = $table_name;
	}
	
	function InsertAuthorInfo() //insert information about author
	{
		global $wpdb;
		$wpdb->insert($this->table_name,array(
			"first_name"=>$this->firstName,
			"last_name"=>$this->lastName,
			"email"=>$this->email,
			"personal_url"=>$this->personalUrl,
			"slug"=>$this->slug
		));
	}
	
	static function CreateTableAuthors($table) //creates authors table
	{
		global $wpdb;
		if($wpdb->get_var("show tables like '$table'") != $table) 
		{
			$sql = "CREATE TABLE $table (
			id MEDIUMINT NOT NULL AUTO_INCREMENT,
			first_name TINYTEXT NOT NULL,
			last_name TINYTEXT NOT NULL,
			email TINYTEXT,
			personal_url TINYTEXT,
			slug TINYTEXT,
			PRIMARY KEY  (id)
			);";
			$wpdb->query($sql);
		}
	}
}
?>