<?php

class Blog extends DatabaseObject
{

    protected static $table_name = "tbl_blog";
    protected static $db_fields = array('id', 'slug', 'title', 'author', 'brief', 'content', 'status', 'meta_keywords', 'meta_description', 'blog_date','linksrc','linktype', 'archive_date', 'sortorder', 'type', 'viewcount', 'added_date', 'image', 'source', 'category');

    public $id;
    public $slug;
    public $title;
    public $author;
    public $brief;
    public $content;
    public $status;
    public $meta_keywords;
    public $meta_description;
    public $blog_date;
    public $linksrc;
    public $linktype;
    public $archive_date;
    public $sortorder;
    public $type;
    public $viewcount;
    public $added_date;
    public $image;
    public $source;
    public $category;

    //get latest blog
    public static function get_latestblog_by($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? 'LIMIT ' . $limit : '';
        $sql = "SELECT id, slug, title, author, brief, image, blog_date, source, viewcount, linksrc, linktype, category FROM " . self::$table_name . " WHERE status='1' ORDER BY blog_date DESC $cond";
        // $sql = "SELECT id, slug, title, author, brief, content, image, blog_date, source, viewcount, linksrc, linktype FROM " . self::$table_name . " WHERE status='1' ORDER BY blog_date DESC $cond";

        $result = self::find_by_sql($sql);
        return $result;
    }

    public static function get_blogarchives_by($month = '', $year = '')
    {
        global $db;
        $sql = "SELECT id, slug, title, author, brief, image, blog_date, source, viewcount FROM " . self::$table_name . " WHERE status='1' AND EXTRACT(MONTH FROM `blog_date`)='$month' AND EXTRACT(YEAR FROM `blog_date`)='$year'  ORDER BY blog_date DESC";
        $result = self::find_by_sql($sql);
        return $result;
    }
    static function getPackageByType($type, $limit=''){
		global $db;
		$cond = !empty($limit)?' LIMIT '.$limit :'';
		$sql = "SELECT * FROM ".self::$table_name." WHERE status=1 AND type != $type ORDER BY sortorder DESC $cond";
		return self::find_by_sql($sql);
	}
    /************************** Package link  by me ***************************/
	public static function get_internal_link($Lsel='',$LType=0)
	{
		global $db;		
		$sql = "SELECT id, slug, title, type FROM ".self::$table_name." WHERE status='1' ORDER BY sortorder ASC";
		$pages = self::find_by_sql($sql);		
		$linkpageDis = ($Lsel==1)?'hide':'';
		
		$result='';		
		if($pages):
		$result.='<optgroup label="Blog">';
			foreach($pages as $pageRow):
				$chkChild  = Subpackage::getTotalSub($pageRow->type);
				$sel = ($Lsel==($pageRow->slug)) ?'selected':'';
				$result.='<option value="'.$pageRow->slug.'" '.$sel.'>&nbsp;&nbsp;'.$pageRow->title.'</option>';

				// Sub package list
				$subRec = Subpackage::getPackage_limit($pageRow->id);
				if($subRec){
					foreach($subRec as $Nrow){
						$sel = ($Lsel==$Nrow->slug) ?'selected':'';
						$result.='<option value="'.$Nrow->slug.'" '.$sel.'>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;'.$Nrow->title.'</option>';
					}
				}

			endforeach;
		$result.='</optgroup>';	
		endif;
		return $result;
	}

    public static function get_popularblog($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? 'LIMIT ' . $limit : '';
        $sql = "SELECT slug, title, author, image, brief, blog_date FROM " . self::$table_name . " WHERE status='1' AND MONTH(blog_date) = MONTH(CURRENT_DATE) ORDER BY viewcount DESC $cond";
        $result = self::find_by_sql($sql);
        return $result;
    }

    public static function get_popularvdoblog($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? 'LIMIT ' . $limit : '';
        $sql = "SELECT slug, title, author, image, source FROM " . self::$table_name . " WHERE status='1' AND MONTH(blog_date) = MONTH(CURRENT_DATE) AND type='0' ORDER BY viewcount DESC $cond";
        $result = self::find_by_sql($sql);
        return $result;
    }

    //GET ALL NEWS
    public static function get_allblog()
    {
        global $db;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status='1' ORDER BY blog_date DESC";
        return self::find_by_sql($sql);
    }

    //FIND THE HIGHEST MAX NUMBER.
    public static function find_maximum($field = "sortorder")
    {
        global $db;
        $result = $db->query("SELECT MAX({$field}) AS maximum FROM " . self::$table_name);
        $return = $db->fetch_array($result);
        return ($return) ? ($return['maximum'] + 1) : 1;
    }

    //Find all the rows in the current database table.
    public static function find_all()
    {
        global $db;
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " ORDER BY sortorder DESC");
    }

    //Get sortorder by id
    public static function field_by_id($id = 0, $fields = "")
    {
        global $db;
        $sql = "SELECT $fields FROM " . self::$table_name . " WHERE id={$id} LIMIT 1";
        $result = $db->query($sql);
        $return = $db->fetch_array($result);
        return ($return) ? $return[$fields] : '';
    }

    //Find a single row in the database where slug is provided.
    static function find_by_slug($slug = '')
    {
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE slug='$slug' LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    //Find a single row in the database where id is provided.
    public static function find_by_id($id = 0)
    {
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    //Find rows from the database provided the SQL statement.
    public static function find_by_sql($sql = "")
    {
        global $db;
        $result_set = $db->query($sql);
        $object_array = array();
        while ($row = $db->fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    //Instantiate all the attributes of the Class.
    private static function instantiate($record)
    {
        $object = new self;
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    //Check if the attribute exists in the class.
    private function has_attribute($attribute)
    {
        $object_vars = $this->attributes();
        return array_key_exists($attribute, $object_vars);
    }

    //Return an array of attribute keys and thier values.
    protected function attributes()
    {
        $attributes = array();
        foreach (self::$db_fields as $field):
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        endforeach;
        return $attributes;
    }

    //Prepare attributes for database.
    protected function sanitized_attributes()
    {
        global $db;
        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value):
            $clean_attributes[$key] = $db->escape_value($value);
        endforeach;
        return $clean_attributes;
    }

    //Save the changes.
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    //Add  New Row to the database
    public function create()
    {
        global $db;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . "(";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if ($db->query($sql)) {
            $this->id = $db->insert_id();
            return true;
        } else {
            return false;
        }
    }

    //Update a row in the database.
    public function update()
    {
        global $db;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();

        foreach ($attributes as $key => $value):
            $attribute_pairs[] = "{$key}='{$value}'";
        endforeach;

        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= join(", ", array_values($attribute_pairs));
        $sql .= " WHERE id=" . $db->escape_value($this->id);
        $db->query($sql);
        return ($db->affected_rows() == 1) ? true : false;
        //return true;
    }


    //front end function start here
    public static function getAllBlog($total = 10, $offset = 0)
    {
        global $db;
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder DESC LIMIT {$total} OFFSET {$offset}");
    }

    // GET NEWS LIST.
    public static function getBlogList($total = 5)
    {
        global $db;
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder DESC LIMIT $total");
    }

    // GET SPECIFIC NEWS
    public static function getBlog($id = 0)
    {
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} AND status=1 LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    // get total number of records published
    public static function getTotalBlog()
    {
        global $db;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status='1'";
        $query = $db->query($sql);
        return $db->num_rows($query);
    }
}

?>