<?php
require '../src/phpConfigMaker/autoload.php';
$pcm = \phpConfigMaker\PhpConfigMaker::init("settings.xml");
$writer = new \phpConfigMaker\writer\PhpWriter($pcm->run()["configuration"]);
$writer->write("out.php");