<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Template>
    <File key="aExistingCoolFile" exists="true" prompt="Which existing file is cool? "/>
    <File key="aNotExistingCoolFile" exists="false" prompt="Which not existing file is cool? "/>
    <File key="aCoolFile" exists="" prompt="Which existing or not existing file is cool? "/>
    <File key="aCoolPhpFile" exists="true" extension=".php" prompt="Which php file is cool? "/>
</Template> 
        
EOF;

$rt = new \ReadlineTemplate\ReadlineTemplate($template);
$rt->loadDefaultReader();

if (!$rt->isValidTemplate()) {
    echo "Failed to validate the template";
    exit();
}

list($data, $extra)  = $rt->run();
foreach ($data as $key => $value) {
    if (is_array($value)) {
        echo "$key:  " . var_export($value, true) . PHP_EOL;
    } elseif (is_bool($value)) {
        echo "$key: " . ($value ? "true" : "false") . PHP_EOL;
    } else {
        echo "$key:  $value" . PHP_EOL;
    }
}
