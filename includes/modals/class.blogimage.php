<?php
class BlogImage extends DatabaseObject {

	protected static $table_name = "tbl_blog_images";
	protected static $db_fields = array('id', 'blogid', 'title', 'detail', 'status', 'sortorder', 'registered', 'image');
	
	var $id;
	var $blogid;
	var $title;
	var $detail;
	var $status;
	var $sortorder;
	var $registered;
	var $image;

	public static function getImagelist_by($blogid='',$postnumbers='', $offset=''){
		global $db;
		$limt  = (!empty($postnumbers) and !empty($offset))?"LIMIT ".$postnumbers." OFFSET ".$offset:'';
		$sql = "SELECT * FROM ".self::$table_name." WHERE status=1 AND blogid=$blogid ORDER BY sortorder ASC $limt";
		return self::find_by_sql($sql);
	}

	public static function getTotalImages($id=''){
		global $db;
		$cond = !empty($id)?' AND blogid='.$id:'';
		$query = "SELECT COUNT(id) AS tot FROM ".self::$table_name." WHERE status=1 $cond ";
		$sql = $db->query($query);
		$ret = $db->fetch_array($sql);
		return $ret['tot'];
	}

	//Find all the rows in the current database table.
	static function getBlogImages($id=0, $total=150, $offset=0){
		global $db;
		return self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE status=1 AND blogid='{$id}' ORDER BY sortorder ASC  LIMIT {$total} OFFSET {$offset}");
	}

	//FIND THE HIGHEST MAX NUMBER.
	static function find_maximum($field="sortorder"){
		global $db;
		$result = $db->query("SELECT MAX({$field}) AS maximum FROM ".self::$table_name);
		$return = $db->fetch_array($result);
		return ($return) ? ($return['maximum']+1) : 1 ;
	}
	
	//FIND THE HIGHEST MAX NUMBER BY PARENT ID.
	static function find_maximum_byparent($field="sortorder",$pid=""){
		global $db;
		$result = $db->query("SELECT MAX({$field}) AS maximum FROM ".self::$table_name." WHERE blogid={$pid}");
		$return = $db->fetch_array($result);
		return ($return) ? ($return['maximum']+1) : 1 ;
	}

	//Find all the rows in the current database table.
	static function getAllImg(){
		global $db;
		return self::find_by_sql("SELECT * FROM ".self::$table_name."  WHERE status=1 ORDER BY sortorder ASC");
	}
	
	//Find all the rows in the current database table.
	static function find_all(){
		global $db;
		return self::find_by_sql("SELECT * FROM ".self::$table_name." ORDER BY sortorder ASC");
	}

	//Find a single row in the database where id is provided.
	static function find_by_id($id=0){
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	//Find rows from the database provided the SQL statement.
	static function find_by_sql($sql=""){
		global $db;
		$result_set = $db->query($sql);
		$object_array = array();
		while($row = $db->fetch_array($result_set)){
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	private static function instantiate($record){
		$object = new self;
		foreach ($record as $attribute => $value){
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		return $object;
	}

	private function has_attribute($attribute){
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}

	protected function attributes(){
		$attributes = array();
		foreach(self::$db_fields as $field){
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	protected function sanitized_attributes(){
		global $db;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $db->escape_value($value);
		}
		return $clean_attributes;
	}

	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}

	public function create(){
		global $db;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($db->query($sql)){
			$this->id = $db->insert_id();
			return true;
		} else {
			return false;
		}
	}

	public function update(){
		global $db;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value){
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$db->escape_value($this->id);
		$db->query($sql);
		return ($db->affected_rows() == 1) ? true : false;
	}

	public function delete(){
		global $db;
		$sql = "DELETE FROM ".self::$table_name;
		$sql .= " WHERE id=".$db->escape_value($this->id);
		$db->query($sql);
		return ($db->affected_rows() == 1) ? true : false;
	}
}
