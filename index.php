<?php
$f3 = require('lib/base.php');
require('lib/log.php');
include('src/DuckyProcess.php');

$f3->route('GET /ducky/@b64ducky',
    function($f3, $params) {
        DuckyProcess::instance()->parseDuckyRequest($f3,$params);
        $f3->reroute('http://www.qualogy.com');
    }
);

$f3->route('GET @boss: /boss',
    function ($f3) {
        $sp= DuckyProcess::instance();
        $all = $sp->dao->gotDuckyOverview();
        if(count($all) > 0) {
            $f3->set('headers', array_keys($all[0]));
        } else {
            $f3->set('headers', false);
        }
        $f3->set('all',$all);
        $f3->set("baseuri", DuckyProcess::THIS_APP_URL);
        echo \Template::instance()->render('views/admin.html');
    }
);


$f3->run();