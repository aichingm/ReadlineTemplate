<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Integer" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Integer extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $flag = true;
        while ($flag) {
            $flag = false;
            $line = $this->readlineTrimAll($prompt);
            if(empty($line) && $this->getElement()->hasAttribute("default")){
                return null;
            }

            if (preg_match("~^[-+]?[0-9]+$~", $line) !== 1) {
                $flag = true;
            }
            if ($this->getElement()->hasAttribute("min") && $this->getElement()->getAttribute("min") > intval($line)) {
                $flag = true;
            }
            if ($this->getElement()->hasAttribute("max") && $this->getElement()->getAttribute("max") < intval($line)) {
                $flag = true;
            }
        }


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
