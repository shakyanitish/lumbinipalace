<?php

class Blog_detail {

    public static function getBlogDetail($slug) {
        return Blog::find_by_slug($slug);
    }

}
