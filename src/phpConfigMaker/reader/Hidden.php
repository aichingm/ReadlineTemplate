<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "Hidden" elements. This class will generate no out put or prompt. It will quietly generate a configuration entry.
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Hidden extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        return $this->getSetting()->getAttribute("default");
    }
    /**
     * Returns the type of element which can be handled by this class in this case "Hidden"
     * @return string Returns "Hidden"
     */
    public function handleElement() {
        return "Hidden";
    }

    /**
     * {@inheritdoc}
     */
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($this->convertDefault($dependencyEquals), $value);
    }

}
