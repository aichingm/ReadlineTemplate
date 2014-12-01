<?php
namespace phpConfigMaker\reader;
use phpConfigMaker\SettingReader;

class ListChoice extends SettingReader {

    public function ask($prompt) {
        $items = $this->getSetting()->getElementsByTagName("Item");
        echo "Select one item by entering its number:" . PHP_EOL;
        for ($index = 0; $index < $items->length; $index++) {
            echo str_repeat("", strlen(strval($items->length)) - strlen(strval($index))) . $index . " " . $items->item($index)->attributes->getNamedItem("value")->value . PHP_EOL;
        }
        $answer = null;
        do {
            $answer = $this->readline($prompt);
        } while (intval($answer) < 0 || intval($answer) > $items->length);
        return $items->item(intval($answer))->attributes->getNamedItem("value")->value;
    }

    public function handleElement() {
        return "ListChoice";
    }

}
