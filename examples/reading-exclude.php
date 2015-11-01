<?php
require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Template>
    <Text key="password" prompt="I sware this will not end up in the collected data! What is your password? " exclude="from-data"/>
    <Text key="name" prompt="What's your name? (This will end up in data) " />
</Template> 
EOF;

$rt = new \ReadlineTemplate\ReadlineTemplate($template);
$rt->loadDefaultReader();

if (!$rt->isValidTemplate()) {
    echo "Failed to validate the template";
    exit();
}

list($data, $extra)  = $rt->run();
echo "data:".PHP_EOL;
foreach ($data as $key => $value) {
    if (is_array($value)) {
        echo "    $key:  " . var_export($value, true) . PHP_EOL;
    } elseif (is_bool($value)) {
        echo "    $key: " . ($value ? "true" : "false") . PHP_EOL;
    } else {
        echo "    $key:  $value" . PHP_EOL;
    }
}
echo "extra:".PHP_EOL;
foreach ($extra as $key => $value) {
    if (is_array($value)) {
        echo "    $key:  " . var_export($value, true) . PHP_EOL;
    } elseif (is_bool($value)) {
        echo "    $key: " . ($value ? "true" : "false") . PHP_EOL;
    } else {
        echo "    $key:  $value" . PHP_EOL;
    }
}
