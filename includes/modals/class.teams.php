<?php

class Teams{

    public static function getTeam($slug) {
        return Article::find_by_slug($slug);
    }

}
