<?php

require __DIR__ . '/../src/Autoload.php';

$template = <<<EOF
<Template>
    <NamedListChoice key="coolDatabase" prompt="Select one: ">
        <Item name="m" value="mariadb" text="MariaDB is Cool"/>
        <Item name="c" value="couchdb" text="CouchDB is cooler"/>
        <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
    </NamedListChoice>
    <NamedListChoice key="coolDatabases" prompt="Select multiple: " separator="," min="2" max="2">
        <Item name="m" value="mariadb" text="MariaDB is Cool"/>
        <Item name="c" value="couchdb" text="CouchDB is cooler"/>
        <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
    </NamedListChoice>
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
