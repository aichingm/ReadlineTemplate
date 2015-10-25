<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Settings>
    <Text key="textA" prompt="Enter some text: "/>
    <Text key="textB" prompt="Enter an empty text: " default="This is so cool"/>
        
    <Integer key="integerA" prompt="Enter a cool integer: "/>
    <Integer key="integerB" prompt="Do not enter a cool integer: " default="1337"/>
</Settings>       
EOF;

$rt = new \ReadlineTemplate\ReadlineTemplate($template);
$rt->loadDefaultReader();

if (!$rt->isValidTemplate()) {
    echo "Failed to validate the template";
    exit();
}

$data = $rt->run();
foreach ($data["configuration"] as $key => $value) {
    if (is_array($value)) {
        echo "$key:  " . var_export($value, true) . PHP_EOL;
    } elseif (is_bool($value)) {
        echo "$key: " . ($value ? "true" : "false") . PHP_EOL;
    } else {
        echo "$key:  $value" . PHP_EOL;
    }
}
