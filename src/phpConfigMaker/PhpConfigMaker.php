<?php

namespace phpConfigMaker;

/**
 * Description of PhpConfigMaker
 *
 * @author mario
 */
class PhpConfigMaker {

    private $xmlFile;
    private $reader = array();

    function __construct($xmlFile) {
        $this->xmlFile = $xmlFile;
    }

    public function addReader(SettingReader $settingReader) {
        $this->reader[$settingReader->handleElement()] = $settingReader;
    }

    public function run() {
        $dom = new \DOMDocument();
        $dom->load($this->xmlFile);
        $answers = array();
        $configuration = array();
        $settings = $dom->getElementsByTagName("Settings")->item(0);
        foreach ($settings->childNodes as $setting) {
            if ($setting instanceof \DOMElement) {
                if ($this->checkDepends($setting, $answers)) {
                    $reader = $this->getReader($setting->tagName);
                    $reader->setUp($setting);
                    $answer = $reader->ask($setting->getAttribute("prompt"));
                    if (!$setting->hasAttribute("exclude") && !$setting->getAttribute("exclude") == "from-config") {
                        if($answer !== null) {
                            $configuration[$setting->getAttribute("key")] = $answer;
                        } else {
                            $configuration[$setting->getAttribute("key")] = $reader->convertDefault($setting->getAttribute("default"));
                        }
                    }
                    $answers[$setting->getAttribute("key")] = $answer;
                }
            }
        }
        return array("configuration" => $configuration, "answers" => $answers);
    }

    /**
     * 
     * @param type $class
     * @return \SettingReader
     * @throws NoSettingClass
     */
    private function getReader($class) {
        if (isset($this->reader[$class])) {
            return $this->reader[$class];
        }
        throw new NoSettingClass();
    }

    private function checkDepends(\DOMElement $setting, array $configuration) {
        if ($setting->hasAttribute("depends")) {
            if (isset($configuration[$setting->getAttribute("depends")])) {
                if ($setting->hasAttribute("depends-equals")) {
                    return $configuration[$setting->getAttribute("depends")] == $setting->getAttribute("depends-equals");
                } else {
                    return !in_array($configuration[$setting->getAttribute("depends")], array(false, null, ""));
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function init($xmlFile) {
        $p = new PhpConfigMaker($xmlFile);
        $p->addReader(new reader\Boolean());
        $p->addReader(new reader\Integer());
        $p->addReader(new reader\ListChoice());
        $p->addReader(new reader\MultiListChoice());
        $p->addReader(new reader\Number());
        $p->addReader(new reader\Text());
        return $p;
    }

}

class NoSettingClass extends \Exception {
    
}
