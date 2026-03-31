<?php

class Offer_detail {
    public static function find_by_slug($slug) {
        return Offers::find_by_slug($slug);
    }

}
