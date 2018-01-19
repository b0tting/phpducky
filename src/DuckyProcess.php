<?php
/**
 * Created by PhpStorm.
 * User: Mark
 * Date: 18-1-2018
 * Time: 16:05
 */
include("DuckyDao.php");


class DuckyProcess extends \Prefab
{
    //0 is ducky ident
    const DUCKY_PCNAME_LOCATION = 1;
    const DUCKY_USERNAME_LOCATION = 2;
    const DUCKY_IPADDRESS_LOCATION = 3;
    const DUCKY_ID_LOCATION = 0;

    const THIS_APP_URL = "https://www.qaulogy.com/ducky";
    public $dao;

    function __construct(){
        $this->dao = new DuckyDao();
        $this->logger = new Log('duckyprocess.log');
    }

    function parseDuckyRequest($f3, $params) {
        $encoded = $params["b64ducky"];
        $this->incomingDucky($encoded, $f3->get('IP'));

    }

    function incomingDucky($encoded, $ip) {
        try {
            $this->logger->write( "Incoming ducky at " . $encoded, 'r');
            $encoded = str_replace("_", "B", $encoded);
            $decoded = base64_decode($encoded);
            $decoded = str_replace("\0", "", $decoded);
            if(strpos($decoded, "ducky") !== False) {
                $this->logger->write("Succesful decode to " . $decoded, 'r');
                $duckystruct = explode(";", $decoded);

                $this->dao->receiveNewDucky(
                        $duckystruct[DuckyProcess::DUCKY_USERNAME_LOCATION],
                        $duckystruct[DuckyProcess::DUCKY_ID_LOCATION],
                        $duckystruct[DuckyProcess::DUCKY_PCNAME_LOCATION],
                        $duckystruct[DuckyProcess::DUCKY_IPADDRESS_LOCATION],
                        $ip
                    );
                $this->logger->write( "Succesful parse of ducky " . $duckystruct[DuckyProcess::DUCKY_ID_LOCATION], 'r');
            } else {
                $this->logger->write( "B64 decode bad result for duckystring " . $encoded . ", got " . $decoded, 'r');
            }
        } catch (ErrorException  $e) {
            $this->logger->write( "Error parsing duckystring " . $encoded, 'r');
            $this->logger->write($e->getMessage());
        }
    }
}