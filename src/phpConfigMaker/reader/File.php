<?php

namespace phpConfigMaker\reader;

use phpConfigMaker\SettingReader;

/**
 * This {@see SettingReader} can handle "Text" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class File extends SettingReader {

    /**
     * {@inheritdoc}
     */
    public function ask($prompt) {
        $exists = $this->getSetting()->getAttribute("exists");
        $extension = $this->getSetting()->getAttribute("extension");

        while (true) {
            $line = $this->readlineTrimAll($prompt);
            //var_dump($exists == "true" , is_file($line) ,$extension == null , strrpos($line, $extension) + strlen($extension) === strlen($line));
            if($exists == "true" && is_file($line) && ($extension == null || strrpos($line, $extension) + strlen($extension) === strlen($line))) {
                break;
            }
            if($exists == "false" && !is_file($line) && ($extension == null || strrpos($line, $extension) + strlen($extension) === strlen($line))) {
                break;
            }
        }
        return $line;

    }

    /**
     * Returns the type of element which can be handled by this class in this case "Text"
     * @return string Returns "Text"
     */
    public function handleElement() {
        return "File";
    }

}
