<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "NamedListChoice" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class NamedListChoice extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $items = $this->getElement()->getElementsByTagName("Item");



        if ($this->getElement()->hasAttribute("min")) {
            $min = " the minimum amount of values is " . $this->getElement()->getAttribute("min");
        }
        if ($this->getElement()->hasAttribute("max")) {
            $max = " the maximum amount of values is " . $this->getElement()->getAttribute("max");
        }
        if($this->getElement()->hasAttribute("separator")) {
            echo "Select multiple items by entering the text before the colon separated with '" . $this->getElement()->getAttribute("separator") . "'" . $min . $max . ": " . PHP_EOL;
        } else {

            echo "Select one item by entering the text before the colon:" . PHP_EOL;
        }

        $list = array();
        for ($index = 0; $index < $items->length; $index++) {
            $attributes = $items->item($index)->attributes;
            $key = $attributes->getNamedItem("name")->nodeValue;
            $value = $attributes->getNamedItem("value")->nodeValue;
            $list[$key]["value"] = $value;
            if ($attributes->getNamedItem("text") != null) {
                $list[$key]["text"] = $attributes->getNamedItem("text")->nodeValue;
            } else {
                $list[$key]["text"] = $value;
            }
        }



        foreach ($list as $key => $value) {
            echo $key . ": " . $value["text"] . PHP_EOL;
        }
        $answer = null;
        do {
            $run = false;
            $answer = $this->readline($prompt);
            if ($this->getElement()->hasAttribute("separator")) {
                $answer = array_unique(explode($this->getElement()->getAttribute("separator"), $answer));
                if ($this->getElement()->hasAttribute("min") && count($answer) < intval($this->getElement()->getAttribute("min"))) {
                    $run = true;
                }
                if ($this->getElement()->hasAttribute("max") && count($answer) > intval($this->getElement()->getAttribute("max"))) {
                    $run = true;
                }
                foreach ($answer as $a) {
                    if (!array_key_exists($a, $list)) {
                        $run = true;
                    }
                }
            } else {
                $run = !array_key_exists($answer, $list);
            }
        } while ($run);
        $returnArray = array();
        if (is_array($answer)) {
            foreach ($answer as $a) {
                $returnArray[] = $list[$a]["value"];
            }
        } else {
            $returnArray[] = $list[$answer]["value"];
        }
        return $returnArray;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "ListChoice"
     * @return string Returns "ListChoice"
     */
    public function handleElement() {
        return "NamedListChoice";
    }

}
