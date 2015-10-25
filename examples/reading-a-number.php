<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Settings>
    <Number key="coolNumber" prompt="What is a cool number? "/>
    <Number key="coolNumberbetween" prompt="What is a cool number between 5 and 10? " min="5" max="10"/>
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
