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
                    "ip_host" text, 
                    "times_seen" int DEFAULT 1, 
                    "created" DEFAULT CURRENT_TIMESTAMP NOT NULL, 
                    "lastseen" DEFAULT CURRENT_TIMESTAMP NOT NULL)
        ;
SQL;
        $this->db->exec($create);
    }

    function hasDucky($username, $duckyid, $pcname) {
        $result = $this->db->exec('SELECT * FROM targets WHERE duckyid = ? and ps_pcname = ? and ps_username = ?',[$duckyid, $pcname, $username])[0];
        return count($result ) > 0;
    }

    function updateDucky($username, $duckyid, $pcname) {
        $this->logger->write( "Updating private ducky", 'r');
        $this->db->exec('update targets set lastseen = CURRENT_TIMESTAMP, times_seen = times_seen + 1 WHERE duckyid = ? and ps_pcname = ? and ps_username = ?',[$duckyid, $pcname, $username])[0];
        $this->logger->write( "Updating private ducky2", 'r');
    }

    function receiveNewDucky($username, $duckyid, $pcname, $ipaddy, $ipsending,$ipsendinghost) {
        $this->logger->write( "Saving private ducky", 'r');
        $this->db->exec("INSERT INTO targets (duckyid,
                    ps_pcname, 
                    ps_username, 
                    ps_ipname,
                     ip, 
                    ip_host) VALUES (?,?,?,?,?,?)",[$duckyid, $pcname, $username, $ipaddy, $ipsending, $ipsendinghost]);
    }

    function gotDuckyOverview() {
        return $this->db->exec(<<<SQL
            SELECT  
            duckyid as "Ducky stick ID",
            ps_pcname as "Target PC Name",
            ps_username as "Target username",
            ps_ipname as "Target local IP" ,
            ip as "Target external IP",
            ip_host as "Target exteral DNS",
            created as "First seen",
            lastseen as "Last seen", 
            times_seen as "Times inserted" 
            from targets;
SQL
      );
    }
}