<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "MultiListChoice" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class MultiListChoice extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $items = $this->getSetting()->getElementsByTagName("Item");
        $min = $max = "";
        if ($this->getSetting()->hasAttribute("min")) {
            $min = " the minimum amount of values is " . $this->getSetting()->getAttribute("min");
        }
        if ($this->getSetting()->hasAttribute("max")) {
            $max = " the maximum amount of values is " . $this->getSetting()->getAttribute("max");
        }
        echo "Select multiple items seperated with '" . $this->getSetting()->getAttribute("seperator") . "'" . $min . $max . ": " . PHP_EOL;
        for ($index = 1; $index-1 < $items->length; $index++) {
            echo str_repeat("", strlen(strval($items->length)) - strlen(strval($index))) . $index . " " . $items->item($index-1)->attributes->getNamedItem("value")->value . PHP_EOL;
        }
        $answerItems = null;
        $run = true;
        while ($run) {
            $run = FALSE;
            $answer = $this->readline($prompt);
            
            $answerItems = array_unique(explode($this->getSetting()->getAttribute("seperator"), $answer));
            foreach ($answerItems as &$value) {
                if (intval($value) < 1 || intval($value) > $items->length+1) {
                    $run = TRUE;
                }
            }
            if ($this->getSetting()->hasAttribute("min") && count($answerItems) < intval($this->getSetting()->getAttribute("min"))) {
                $run = true;
            }
            if ($this->getSetting()->hasAttribute("max") && count($answerItems) > intval($this->getSetting()->getAttribute("max"))) {
                $run = true;
            }
        }
        return $answerItems;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "MultiListChoice"
     * @return string Returns "MultiListChoice"
     */
    public function handleElement() {
        return "MultiListChoice";
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        return explode($this->getSetting()->getAttribute("seperator"), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($dependencyEquals, implode($this->getSetting()->getAttribute("separator"), $value));
    }

}
