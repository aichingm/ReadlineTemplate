<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Number" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Number extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $flag = true;
        while ($flag) {
            $flag = false;
            $line = $this->readlineTrimAll($prompt);
            if (empty($line) && $this->getElement()->hasAttribute("default")) {
                return null;
            }
            if (preg_match("~^[-+]?[1-9]*[0-9]+((\.|,)[0-9]+)?~", $line) !== 1) {
                $flag = true;
            }
            if ($this->getElement()->hasAttribute("min") && $this->getElement()->getAttribute("min") > doubleval($line)) {
                $flag = true;
            }
            if ($this->getElement()->hasAttribute("max") && $this->getElement()->getAttribute("max") < doubleval($line)) {
                $flag = true;
            }
        }


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
