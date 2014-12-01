<?php
namespace phpConfigMaker\reader;
use phpConfigMaker\SettingReader;
class Number extends SettingReader {

    public function ask($prompt) {
        do {
            $line = $this->readlineTrimAll($prompt);
        } while (preg_match("~^[-+]?[0-9]+((\.|,)[0-9]+)?~", $line) === 0);
        return doubleval($line);
    }

    public function convertDefault($default) {
        return doubleval($default);
    }

    public function handleElement() {
        return "Text";
    }

}

