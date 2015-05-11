<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "NamedListChoice" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class NamedListChoice extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $items = $this->getSetting()->getElementsByTagName("Item");
        echo "Select one item by entering the text before the colon:" . PHP_EOL;

        $list = array();
        for ($index = 0; $index < $items->length; $index++) {
            $attributes = $items->item($index)->attributes;
            $key = $attributes->getNamedItem("name")->nodeValue;
            $value = $attributes->getNamedItem("value")->nodeValue;
            $list[$key]["value"] = $value;
            if($attributes->getNamedItem("text") != null) {
                $list[$key]["text"] = $attributes->getNamedItem("text")->nodeValue;
            } else {
                $list[$key]["text"] = $value;
            }
        }
        foreach($list as $key => $value){
            echo $key . ": " . $value["text"] . PHP_EOL;
        }
        $answer = null;
        do {
            $answer = $this->readline($prompt);
        } while (!array_key_exists($answer, $list));
        return $list[$answer]["value"];
    }

    /**
     * Returns the type of element which can be handled by this class in this case "ListChoice"
     * @return string Returns "ListChoice"
     */
    public function handleElement() {
        return "NamedListChoice";
    }

}
