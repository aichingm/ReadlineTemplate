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
        $items = $this->getElement()->getElementsByTagName("Item");
        $min = $max = "";
        if ($this->getElement()->hasAttribute("min")) {
            $min = " the minimum amount of values is " . $this->getElement()->getAttribute("min");
        }
        if ($this->getElement()->hasAttribute("max")) {
            $max = " the maximum amount of values is " . $this->getElement()->getAttribute("max");
        }
        echo "Select multiple items separated with '" . $this->getElement()->getAttribute("separatseparator") . "'" . $min . $max . ": " . PHP_EOL;
        for ($index = 1; $index-1 < $items->length; $index++) {
            echo str_repeat("", strlen(strval($items->length)) - strlen(strval($index))) . $index . " " . $items->item($index-1)->attributes->getNamedItem("value")->value . PHP_EOL;
        }
        $answerItems = null;
        $run = true;
        while ($run) {
            $run = FALSE;
            $answer = $this->readline($prompt);
            
            $answerItems = array_unique(explode($this->getElement()->getAttribute("separator"), $answer));
            foreach ($answerItems as &$value) {
                if (intval($value) < 1 || intval($value) > $items->length+1) {
                    $run = TRUE;
                }
            }
            if ($this->getElement()->hasAttribute("min") && count($answerItems) < intval($this->getElement()->getAttribute("min"))) {
                $run = true;
            }
            if ($this->getElement()->hasAttribute("max") && count($answerItems) > intval($this->getElement()->getAttribute("max"))) {
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
        return explode($this->getElement()->getAttribute("separator"), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($dependencyEquals, implode($this->getElement()->getAttribute("separator"), $value));
    }

}
