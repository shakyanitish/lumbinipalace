<?php
class Services extends DatabaseObject {

	protected static $table_name = "tbl_services";
	protected static $db_fields = array('id', 'slug','title', 'sub_title', 'image', 'icon', 'linksrc', 'linktype', 'content', 'status', 'added_date', 'sortorder', 'type', 'meta_title', 'service_type','meta_keywords', 'meta_description', 'iconimage','brief','booklinksrc','booklinktype','contact_info','fiscal_address','email_address','facebook_link','x_link','instagram_link','youtube_link', 'linkedin_link','tiktok_link','explorelinksrc','explorelinktype', 'bannerimage','heading');
	
	public $id;
	public $slug;
	public $title;
	public $image;
	public $icon;
	public $linksrc;
	public $linktype;
	public $content;
	public $status;
	public $added_date;	
	public $sortorder;
	public $type;
	public $sub_title;
	public $meta_title;
	public $meta_keywords;
	public $meta_description;
	public $service_type;
	public $iconimage;
	public $brief;
	public $booklinksrc;
	public $booklinktype;
	public $explorelinksrc;
	public $explorelinktype;
	public $contact_info;
	public $fiscal_address;
	public $email_address;
	public $facebook_link;
	public $x_link;
	public $instagram_link;
	public $youtube_link;
	public $linkedin_link;
	public $tiktok_link;
	public $bannerimage;
	public $heading;
	

		
	//Find all published rows in the current database table.
	public static function getservice_list($limit='',$type=1) {
		global $db;
		$lmt = !empty($limit)?' LIMIT '.$limit:'';
		$sql="SELECT * FROM ".self::$table_name." WHERE status='1' AND service_type=$type ORDER BY sortorder DESC $lmt";
		return self::find_by_sql($sql);
	}

	public static function checkDupliName($title='')
	{
		global $db;
		$query = $db->query("SELECT title FROM ".self::$table_name." WHERE title='$title' LIMIT 1");
		$result= $db->num_rows($query);
		if($result>0) {return true;}
	}
	
	//FIND THE HIGHEST MAX NUMBER.
	public static function find_maximum($field="sortorder"){
		global $db;
		$result = $db->query("SELECT MAX({$field}) AS maximum FROM ".self::$table_name);
		$return = $db->fetch_array($result);
		return ($return) ? ($return['maximum']+1) : 1 ;
	}




	//Link with menu
		public static function get_internal_link($Lsel='',$LType=0)
	{
		global $db;		
		$sql = "SELECT id, slug, title,brief, type FROM ".self::$table_name." WHERE status='1' AND service_type='1' ORDER BY sortorder ASC";
		$pages = self::find_by_sql($sql);		
		$linkpageDis = ($Lsel==1)?'hide':'';
		
		$result='';		
		if($pages):
		$result.='<optgroup label="Services">';
			foreach($pages as $pageRow):
				$chkChild  = Services::getTotalSub($pageRow->type);
				$sel = ($Lsel==($pageRow->slug)) ?'selected':'';
				$result.='<option value="'.$pageRow->slug.'" '.$sel.'>&nbsp;&nbsp; '.$pageRow->title.'</option>';

				// Sub package list
				// $subRec = Services::getPackage_limit($pageRow->id);
				// if($subRec){
				// 	foreach($subRec as $Nrow){
				// 		$sel = ($Lsel==$Nrow->slug) ?'selected':'';
				// 		$result.='<option value="'.$Nrow->slug.'" '.$sel.'>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; '.$Nrow->title.'</option>';
				// 	}
				// }

			endforeach;
		$result.='</optgroup>';	
		endif;

		$sql = "SELECT id, slug, title, type FROM ".self::$table_name." WHERE status='1' and service_type='2' ORDER BY sortorder ASC";
		$pages = self::find_by_sql($sql);		
		$linkpageDis = ($Lsel==1)?'hide':'';
		
		$result='';		
		if($pages):
		$result.='<optgroup label="Academics">';
			foreach($pages as $pageRow):
				$chkChild  = Services::getTotalSub($pageRow->type);
				$sel = ($Lsel==($pageRow->slug)) ?'selected':'';
				$result.='<option value="'.$pageRow->slug.'" '.$sel.'>&nbsp;&nbsp; '.$pageRow->title.'</option>';

				// // Sub package list
				// $subRec = Services::getPackage_limit($pageRow->id);
				// if($subRec){
				// 	foreach($subRec as $Nrow){
				// 		$sel = ($Lsel==$Nrow->slug) ?'selected':'';
				// 		$result.='<option value="'.$Nrow->slug.'" '.$sel.'>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;'.$Nrow->title.'</option>';
				// 	}
				// }

			endforeach;
		$result.='</optgroup>';	
		endif;
		return $result;
	}



	    public static function getTotalSub($type = '')
    {
        global $db;
        $cond = !empty($type) ? ' AND type=' . $type : '';
        $query = "SELECT COUNT(id) AS tot FROM " . self::$table_name . " WHERE status=1 $cond ";
        $sql = $db->query($query);
        $ret = $db->fetch_array($sql);
        return $ret['tot'];
    }


	    public static function getPackage_limit($type = 0, $limit = '')
    {
        global $db;
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE type=$type AND status=1 ORDER BY sortorder DESC $cond ";
        return self::find_by_sql($sql);
    }

	



	
	//Find all the rows in the current database table.
	public static function find_all(){
		global $db;
		return self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE status='1' ORDER BY sortorder ASC  ");
	}
	//Find top8 the rows in the current database table.
	public static function find_8(){
		global $db;
		return self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE status='1' ORDER BY sortorder ASC LIMIT 4");
	}

	//Find a single row in the database where slug is provided.
	static function find_by_slug($slug=''){
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE slug='$slug' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}


	static function find_by_slugs($slug=''){
    global $db;
    $slug = $db->escape_value($slug);

    $sql = "SELECT * FROM ".self::$table_name."
            WHERE slug='$slug'
            AND service_type=2
            AND status='1'
            LIMIT 1";

    $result_array = self::find_by_sql($sql);
    return !empty($result_array) ? array_shift($result_array) : false;
}


	//Get sortorder by id
	public static function field_by_id($id=0,$fields=""){
		global $db;
		$sql = "SELECT $fields FROM ".self::$table_name." WHERE id={$id} LIMIT 1";
		$result = $db->query($sql);
		$return = $db->fetch_array($result);
		return ($return) ? $return[$fields] : '' ;
	}
	
	//Find a single row in the database where id is provided.
	public static function find_by_id($id=0){
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	//Find rows from the database provided the SQL statement.
	public static function find_by_sql($sql=""){
		global $db;
		$result_set = $db->query($sql);
		$object_array = array();
		while($row = $db->fetch_array($result_set)){
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	
	//Instantiate all the attributes of the Class.
	private static function instantiate($record){
		$object  = new self;
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	//Check if the attribute exists in the class.
	private function has_attribute($attribute){
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
	
	//Prepare attributes for database.
	protected function sanitized_attributes(){
		global $db;
		$clean_attributes = array();
		foreach($this->attributes() as $key=>$value):
			$clean_attributes[$key] = $db->escape_value($value);
		endforeach;
		return $clean_attributes;
	}
	
	//Save the changes.
	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}
	
	//Add  New Row to the database
	public function create(){
		global $db;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT INTO ".self::$table_name."(";
		$sql.= join(", ", array_keys($attributes));
		$sql.= ") VALUES ('";
		$sql.= join("', '", array_values($attributes));
		$sql.= "')";
		if($db->query($sql)){
			$this->id = $db->insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	//Update a row in the database.
	public function update(){
		global $db;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		
		foreach($attributes as $key=>$value):
			$attribute_pairs[] = "{$key}='{$value}'";
		endforeach;
		
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql.= join(", ", array_values($attribute_pairs));
		$sql.= " WHERE id=".$db->escape_value($this->id);
		$db->query($sql);
		return ($db->affected_rows()==1) ? true : false;
		//return true;
	}

	public static function getServiceBySlug($slug, $type) {
    global $db; // or your DB connection
    $slug = $db->real_escape_string($slug);
    $query = "SELECT * FROM services WHERE slug='$slug' AND type='$type' LIMIT 1";
    $result = $db->query($query);
    return $result->fetch_object();
}

}
?>