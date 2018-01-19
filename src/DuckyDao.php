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
        $this->logger = new Log('duckyprocess.log');
        if(!file_exists(DuckyDao::DATABASE_FILE)) {
            $this->db = new DB\SQL('sqlite:' . DuckyDao::DATABASE_FILE);
            $this->setupDatabase();
        } else {
            $this->db = new DB\SQL('sqlite:' . DuckyDao::DATABASE_FILE);
        }
    }

    protected function setupDatabase() {
        $create = <<<SQL
        CREATE TABLE targets
                    ("id" INTEGER PRIMARY KEY,
                    "duckyid" text, 
                    "ps_pcname" text,
                    "ps_username" text, 
                    "ps_ipname" text, 
                    "ip" text,
                    "created" DEFAULT CURRENT_TIMESTAMP NOT NULL)
        ;
SQL;
        $this->db->exec($create);
    }

    function receiveNewDucky($username, $duckyid, $pcname, $ipaddy, $ipsending) {
        $this->logger->write( "Saving private ducky", 'r');
        $this->db->exec("INSERT INTO targets (duckyid,
                    ps_pcname, 
                    ps_username, 
                    ps_ipname,
                     ip) VALUES (?,?,?,?,?)",[$duckyid, $pcname, $username, $ipaddy, $ipsending]);
    }

    function gotDuckyOverview() {
        return $this->db->exec(<<<SQL
            SELECT id, 
            duckyid,
            ps_pcname,
            ps_username,
            ps_ipname,
            ip,
            created 
            from targets;
SQL
      );
    }
}