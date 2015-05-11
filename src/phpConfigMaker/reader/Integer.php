<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "Integer" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Integer extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        do {
            $line = $this->readlineTrimAll($prompt);
        } while (preg_match("~^[-+]?[0-9]+$~", $line) !== 1);
        return intval($line);
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        return intval($default);
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Integer"
     * @return string Returns "Integer"
     */
    public function handleElement() {
        return "Integer";
    }

}
