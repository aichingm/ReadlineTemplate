<?php
require '../src/phpConfigMaker/autoload.php';
$pcm = \phpConfigMaker\PhpConfigMaker::init("settings.xml");
$data = $pcm->run();
var_dump($data);
$writer = new \phpConfigMaker\writer\PhpWriter($data["configuration"]);
$writer->write("out.php");