<?php

class DB
{
    private static $instance = null;

    public static function init()
    {
        self::$instance = new PDO('sqlite:mydb.sq3', '', '', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public static function getInstance()
    {
        return self::$instance;
    }
}