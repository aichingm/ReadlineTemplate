<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Regex" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Regex extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $flag = true;
        while ($flag) {
            $flag = false;
            $line = $this->readlineTrimAll($prompt);

            if ($this->getElement()->hasAttribute("raw") && $this->getElement()->getAttribute("raw") == "true") {
                $pattern = $this->getElement()->getAttribute("pattern");
            } else {
                $pattern = "~^" . $this->getElement()->getAttribute("pattern") . "$~";
            }
            if (preg_match($pattern, $line) !== 1) {
                $flag = true;
            }
        }
        return $line;
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        return intval($default);
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Regex"
     * @return string Returns "Integer"
     */
    public function handleElement() {
        return "Regex";
    }

}
