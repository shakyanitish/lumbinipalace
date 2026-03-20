<?php 
class Mlink extends DatabaseObject {
	protected static $table_name = "tbl_mlink";
	protected static $db_fields = array('mod_class', 'm_url', 'act_id');

	public $mod_class;
	public $m_url;
	public $act_id;

	//Find a single row in the database where slug is provided.
	public static function find_by_slug($slug=0){
		global $db;
		$sql = "SELECT * FROM ".self::$table_name." WHERE m_url='$slug' LIMIT 1";
		$result_array = self::find_by_sql($sql);
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
	
	//Instantiate all the attributes of the Class.
	static function instantiate($record){
		$object  = new self;
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	//Check if the attribute exists in the class.
	function has_attribute($attribute){
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}

	//Return an array of attribute keys and thier values.
	protected function attributes(){
		$attributes = array();
		foreach(self::$db_fields as $field):
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		endforeach;
		return $attributes;
	}

}