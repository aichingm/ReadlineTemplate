<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Text" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Text extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $line = $this->readlineTrimAll($prompt);
        return $line != "" ? $line : null;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Text"
     * @return string Returns "Text"
     */
    public function handleElement() {
        return "Text";
    }

}
