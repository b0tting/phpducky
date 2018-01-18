<?php
$f3 = require('lib/base.php');
require('lib/log.php');
include('src/SpoofProcess.php');

$f3->route('GET /logo/@uid',
    function($f3, $params) {
        $params["step"] = SpoofProcess::STEP_1_CODE;
        SpoofProcess::instance()->recordStep($f3,$params);
        $img = new Image('img/logo.png');
        $img->render();
    }
);

$f3->route('GET /openauth/@uid',
    function($f3, $params) {
        $params["step"] = SpoofProcess::STEP_2_CODE;
        SpoofProcess::instance()->recordStep($f3,$params);
        $f3->set("baseuri", SpoofProcess::THIS_APP_URL);
        $f3->set("spoofuri", SpoofProcess::THIS_APP_URL . '/openauth2/'. $params["uid"]);
        echo \Template::instance()->render('views/Gmail.htm');
    }
);

$f3->route('GET|POST /openauth2/@uid',
    function($f3, $params) {
        // Record step 3 details
        $params["step"] = SpoofProcess::STEP_3_CODE;
        $params["username"] = $f3->get('POST.username');
        SpoofProcess::instance()->recordStep($f3, $params);

        // Onward!
        $details = SpoofProcess::instance()->getDetailsForUid($params["uid"]);
        $f3->set("name", $details["name"]);
        $f3->set('mail',$params["username"]);
        $f3->set('uid',$params["uid"]);
        $f3->set("baseuri", SpoofProcess::THIS_APP_URL);
        $f3->set("spoofuri", SpoofProcess::THIS_APP_URL . '/happyform/');
        echo \Template::instance()->render('views/Gmail2.htm');
    }
);
$f3->route('GET|POST /happyform/',
    function($f3,$params) {
        // Record step 4 details
        $params["step"] = SpoofProcess::STEP_4_CODE;
        $params["uid"] = $f3->get('POST.uid');
        $params["pwlength"] = $f3->get('POST.pwlength');
        SpoofProcess::instance()->recordStep($f3, $params);

        $details = SpoofProcess::instance()->getDetailsForUid($params["uid"]);
        $f3->set("baseuri", SpoofProcess::THIS_APP_URL);
        $f3->set("name", $details["name"]);
        $f3->set("uid", $f3->get('POST.uid'));
        $f3->set("doneuri", SpoofProcess::THIS_APP_URL . "/happyform/done");
        echo \Template::instance()->render('views/form.html');
    }
);

$f3->route('GET /happyform/done/@uid',
    function($f3,$params) {
        // Record step 4 details
        $params["step"] = SpoofProcess::STEP_5_CODE;

        SpoofProcess::instance()->recordStep($f3, $params);

        $f3->set("baseuri", SpoofProcess::THIS_APP_URL);
        echo \Template::instance()->render('views/formdone.html');
    }
);
$f3->route('GET /action/@uid/@step/*','SpoofProcess->recordStep');
$f3->route('GET /boss/invitemail/@id','SpoofProcess->sendInviteMail');
$f3->route('GET /boss/csv',
    function($f3) {
        header("Cache-Control: must-revalidate");
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=\"qaulogy_spoof.csv\"");
        $csv  = SpoofProcess::instance()->csvReport();
        print($csv);
    }
);

$f3->route('GET /boss/pseudonimyze',
    function($f3) {
        SpoofProcess::instance()->pseudonimyze();
        $f3->reroute('@boss');
    });

$f3->route('GET /boss/@name/@mail', "SpoofProcess->addNewTarget");
$f3->route('GET @boss: /boss',
    function ($f3) {
        $sp= SpoofProcess::instance();
        $all = $sp->dao->getOverview();
        if(count($all) > 0) {
            $f3->set('headers', array_keys($all[0]));
        } else {
            $f3->set('headers', false);
        }
        $f3->set('all',$all);
        $f3->set("baseuri", SpoofProcess::THIS_APP_URL);
        echo \Template::instance()->render('views/admin.html');
    }
);

$f3->run();