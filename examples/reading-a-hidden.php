<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Settings>
    <Hidden key="cool" prompt="Isn't this cool? " default="This comes from nowhere!"/>
</Settings>    
EOF;

$rt = new \ReadlineTemplate\ReadlineTemplate($template);
$rt->loadDefaultReader();

if(!$rt->isValidTemplate()){
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
