<?php

class Config extends DatabaseObject
{

    protected static $table_name = "tbl_configs";
    protected static $db_fields = array(
        'id', 'sitetitle', 'icon_upload', 'logo_upload', 'fb_upload', 'twitter_upload', 'gallery_upload', 'contact_upload', 'other_upload', 'facility_upload', 'offer_upload',
        'sitename', 'location_type', 'location_map', 'location_image', 'fiscal_address', 'mail_address', 'contact_info', 'address', 'email_address', 'breif',
        'copyright', 'meta_title', 'site_keywords', 'site_description', 'google_anlytics', 'fb_messenger', 'book_type', 'hotel_code', 'booking_code', 'hotel_page',
        'template', 'admin_template', 'headers', 'footer', 'search_box', 'robot_txt', 'schema_code', 'search_result', 'action', 'linksrc', 'contact_info2', 'pobox',
        'pixel_code', 'whatsapp_a', 'whatsapp','upcomingcontent','upcoming','mapping','room_reservation_number');

    public $id;
    public $sitetitle;
    public $sitename;
    public $location_type;
    public $location_map;
    public $location_image;
    public $fiscal_address;
    public $mail_address;
    public $contact_info;
    public $address;
    public $email_address;
    public $breif;
    public $copyright;
    public $icon_upload;
    public $logo_upload;
    public $logo;
    public $schema_code;
    public $fb_upload;
    public $twitter_upload;
    public $gallery_upload;
    public $contact_upload;
    public $other_upload;
    public $facility_upload;
    public $offer_upload;
    public $meta_title;
    public $site_keywords;
    public $site_description;
    public $fb_messenger;
    public $google_anlytics;
    public $book_type;
    public $hotel_page;
    public $hotel_code;
    public $booking_code;
    public $template;
    public $admin_template;
    public $headers;
    public $footer;
    public $search_box;
    public $search_result;
    public $action;
    public $robot_txt;
    public $linksrc;
    public $contact_info2;
    public $pobox;
    public $room_reservation_number;

    public $whatsapp_a;
    public $pixel_code;

    public $whatsapp;
    public $upcoming;
    public $upcomingcontent;
    public $mapping;





    //returns the current admin template
    public static function getCurrentTemplate($temp = "admin_template")
    {
        global $db;
        $query = $db->query("SELECT {$temp} FROM " . self::$table_name . " LIMIT 1");
        $result = $db->fetch_array($query);
        return $result[$temp];
    }

    public static function getField($field = "", $return = false)
    {
        global $db;
        $sql = "SELECT {$field} FROM " . self::$table_name . " LIMIT 1";
        $query = $db->query($sql);
        $result = $db->fetch_array($query);

        if ($return) {
            return $result[$field];
        } else {
            echo $result[$field];
            return true;
        }
    }

    //Find a single row in the database where id is provided.
    public static function find_by_id($id = 0)
    {
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_field($field)
    {
        global $db;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE id=1 LIMIT 1";
        $result = $db->query($sql);
        $return = $db->fetch_array($result);
        // pr($return);
        return !empty($return) ? $return[$field]: false;
    }
    public static function getconfig_info()
    {
        global $db;
        $sql = "SELECT action FROM " . self::$table_name . " WHERE id=1 LIMIT 1";
        $result = self::find_by_sql($sql);
        return !empty($result) ? array_shift($result) : false;
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