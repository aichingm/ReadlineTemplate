<?php
namespace phpConfigMaker\reader;
use phpConfigMaker\SettingReader;

class Integer extends SettingReader {

    public function ask($prompt) {
        do {
            $line = $this->readlineTrimAll($prompt);
        } while (preg_match("~^[-+]?[0-9]+~", $line) === 0);
        return intval($line);
    }

    public function convertDefault($default) {
        return intval($default);
    }

    public function handleElement() {
        return "Text";
    }

}

