<?php

namespace phpConfigMaker\reader;
use phpConfigMaker\SettingReader;

class Text extends SettingReader {

    public function ask($prompt) {
        $line = $this->readlineTrimAll($prompt);
        return $line != "" ? $line : null;
    }

    public function handleElement() {
        return "Text";
    }

}

