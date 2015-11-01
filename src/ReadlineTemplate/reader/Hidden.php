<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Hidden" elements. This class will generate no out put or prompt. It will quietly generate a data entry.
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Hidden extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        return $this->getElement()->getAttribute("default");
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
