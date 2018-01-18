<?php
/**
 * Created by PhpStorm.
 * User: Mark
 * Date: 18-1-2018
 * Time: 16:05
 */

class DuckyDao
{
    const DATABASE_FILE="ducky.sqlite";
    protected $db;

    function __construct() {
        if(!file_exists(DuckyDao::DATABASE_FILE)) {
            $this->db = new DB\SQL('sqlite:spoof.sqlite');
            $this->setupDatabase();
        } else {
            $this->db = new DB\SQL('sqlite:spoof.sqlite');
        }
    }

    protected function setupDatabase() {
        //include_once("BaseLineLoad.php");
        //baseLineLoad($this->db);
        pass;
    }

    function receiveNewDucky()

}