<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Boolean" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Boolean extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        return $this->readlineYesNo($prompt, true);
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        if ($default == "true") {
            return true;
        }
        return false;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Boolean"
     * @return string Returns "Boolean"
     */
    public function handleElement() {
        return "Boolean";
    }

    /**
     * {@inheritdoc}
     */
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($this->convertDefault($dependencyEquals), $value);
    }

}
