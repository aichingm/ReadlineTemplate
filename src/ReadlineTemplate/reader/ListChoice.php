<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "ListChoice" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class ListChoice extends DataReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $items = $this->getElement()->getElementsByTagName("Item");
        echo "Select one item by entering its number:" . PHP_EOL;
        for ($index = 1; $index - 1 < $items->length; $index++) {
            echo str_repeat("", strlen(strval($items->length)) - strlen(strval($index))) . $index . " " . $items->item($index - 1)->attributes->getNamedItem("value")->value . PHP_EOL;
        }
        $answer = null;
        do {
            $answer = $this->readline($prompt);
        } while (preg_match("~[1-9][0-9]*~", $answer) !== 1 || intval($answer) < 1 || intval($answer) > $items->length);
        return $items->item(intval($answer) - 1)->attributes->getNamedItem("value")->value;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "ListChoice"
     * @return string Returns "ListChoice"
     */
    public function handleElement() {
        return "ListChoice";
    }

}
