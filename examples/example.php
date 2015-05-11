<?php
require __DIR__.'/../src/phpConfigMaker/autoload.php';
$pcm = \phpConfigMaker\PhpConfigMaker::init(__DIR__."/settings.xml");
$data = $pcm->run();
var_dump($data);
$writer = new \phpConfigMaker\writer\PhpWriter($data["configuration"]);
$writer->write("out.php");