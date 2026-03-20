<?php

class Package_detail {

    public static function getPackageDetail($slug) {
        return Package::find_by_slug($slug);
    }

}
