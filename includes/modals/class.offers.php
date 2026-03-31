<?php

class Offers extends DatabaseObject
{

    protected static $table_name = "tbl_offers";
    protected static $db_fields = array(
        'id', 'slug', 'title','tag', 'image', 'offer_date','offerpopup','start_date', 'linksrc', 'linktype', 'rate', 'discount', 'brief', 'content', 'adults', 'children',
        'status', 'added_date', 'sortorder', 'type', 'list_image','homepage', 'deadline_type');

    public $id;
    public $slug;
    public $title;
    public $tag;
    public $image;
    public $linksrc;
    public $linktype;
    public $content;
    public $offer_date;
    public $start_date;
    public $rate;
    public $discount;
    public $brief;
    public $adults;
    public $children;
    public $offerpopup;
    public $status;
    public $added_date;
    public $sortorder;
    public $type;
    public $list_image;
    public $homepage;
    public $deadline_type;

    //Find all published rows in the current database table.
    public static function get_offer_by($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 AND (deadline_type='alltime' OR (CURDATE() BETWEEN start_date AND offer_date)) ORDER BY sortorder DESC $cond");
    }

    public static function get_offer_by_popup($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 AND offerpopup=1 AND (deadline_type='alltime' OR (CURDATE() BETWEEN start_date AND offer_date)) ORDER BY sortorder DESC $cond");
    }

    public static function get_internal_link($Lsel = '', $LType = 0)
    {
        global $db;
        $sql = "SELECT slug, title FROM " . self::$table_name . " WHERE status='1' ORDER BY sortorder ASC";
        $pages = Offers::find_by_sql($sql);
        $linkpageDis = ($Lsel == 1) ? 'hide' : '';

        $result = '';
        if ($pages):
            $result .= '<optgroup label="Offers">';
            foreach ($pages as $pageRow):
                $sel = ($Lsel == ("offer/" . $pageRow->slug)) ? 'selected' : '';
                $result .= '<option value="offer/' . $pageRow->slug . '" ' . $sel . '>&nbsp;&nbsp;' . $pageRow->title . '</option>';
            endforeach;
            $result .= '</optgroup>';
        endif;
        return $result;
    }

    public static function get_offer_rand()
    {
        global $db;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status=1  ORDER BY RAND() LIMIT 1 ";
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function checkDupliName($title = '')
    {
        global $db;
        $query = $db->query("SELECT title FROM " . self::$table_name . " WHERE title='$title' LIMIT 1");
        $result = $db->num_rows($query);
        if ($result > 0) {
            return true;
        }
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
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder DESC");
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
}

?>