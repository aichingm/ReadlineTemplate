<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "MultiListChoice" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class MultiListChoice extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $items = $this->getSetting()->getElementsByTagName("Item");
        $min = $max = "";
        if ($this->getSetting()->hasAttribute("min")) {
            $min = "minimum amount of values is " . $this->getSetting()->hasAttribute("min");
        }
        if ($this->getSetting()->hasAttribute("max")) {
            $max = " maximum amount of values is " . $this->getSetting()->hasAttribute("max");
        }
        echo "Select multible items seperated with '" . $this->getSetting()->getAttribute("seperator") . "'" . $min . $max . PHP_EOL;
        for ($index = 0; $index < $items->length; $index++) {
            echo str_repeat("", strlen(strval($items->length)) - strlen(strval($index))) . $index . " " . $items->item($index)->attributes->getNamedItem("value")->value . PHP_EOL;
        }
        $answerItems = null;
        $run = true;
        while ($run) {
            $answer = $this->readline($prompt);
            if ($answer == "") {
                continue;
            } $answerItems = array_unique(explode($this->getSetting()->getAttribute("seperator"), $answer));
            $run = FALSE;
            foreach ($answerItems as &$value) {
                if (intval($value) < 0 || intval($value) > $items->length) {
                    $run = TRUE;
                } else {
                    $value = $items->item(intval($value))->attributes->getNamedItem("value")->value;
                }
            }
            if ($this->getSetting()->hasAttribute("min") && count($answerItems) < intval($this->getSetting()->getAttribute("min"))) {
                $run = true;
            }
            if ($this->getSetting()->hasAttribute("max") && count($answerItems) > interface_exists($this->getSetting()->getAttribute("max"))) {
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
        return parent::equalsDependency($dependencyEquals, implode($this->getSetting()->getAttribute("seperator"), $value));
    }

}
