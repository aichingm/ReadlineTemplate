<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Template>
    <MultiListChoice key="coolCharactersAre" prompt="Which one is cooler? "  separator=",">
        <Item value="A"/>
        <Item value="B"/>
        <Item value="C"/>
        <Item value="D"/>
    </MultiListChoice>
    <MultiListChoice key="2coolCharactersAre" prompt="Which one is cooler? " min="2" max="2" separator=",">
        <Item value="A"/>
        <Item value="B"/>
        <Item value="C"/>
        <Item value="D"/>
    </MultiListChoice>
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
