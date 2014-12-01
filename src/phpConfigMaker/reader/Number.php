<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "Number" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Number extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        do {
            $line = $this->readlineTrimAll($prompt);
        } while (preg_match("~^[-+]?[0-9]+((\.|,)[0-9]+)?~", $line) === 0);
        return doubleval($line);
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        return doubleval($default);
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Number"
     * @return string Returns "Number"
     */
    public function handleElement() {
        return "Number";
    }

}
