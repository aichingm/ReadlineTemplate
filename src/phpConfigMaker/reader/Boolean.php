<?php
namespace phpConfigMaker\reader;
use phpConfigMaker\SettingReader;

class Boolean extends SettingReader {

    public function ask($prompt) {
        return $this->readlineYesNo($prompt, true);
    }
    public function convertDefault($default){
        if($default == "true"){
            return true;
        }
        return false;
    }
    
    public function handleElement() {
        return "Boolean";
    }
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($this->convertDefault($dependencyEquals), $value);
    }

}

