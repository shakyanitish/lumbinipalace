<?php

class Partners{

    public static function getPartner($slug) {
        return Article::find_by_slug($slug);
    }

}
