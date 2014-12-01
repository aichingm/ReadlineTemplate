<?php

namespace phpConfigMaker;

/**
 * Use this class to ask the user to enter the required settings from a .xml file
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class PhpConfigMaker {

    /**
     * Holds the file name of the .xml file 
     * @var string
     */
    private $xmlFile;

    /**
     * Holds references to the used SettingReaders (aSettingReader questions the user to enter data)
     * @var array
     */
    private $reader = array();

    /**
     * Creates a new PhpConfigMaker object with out any SettingReader loaded
     * @param string $xmlFile the file name of the .xml file which contains the settings structure
     */
    function __construct($xmlFile) {
        $this->xmlFile = $xmlFile;
    }

    /**
     * Adds a {@see \phpConfigMaker\SettingReader} the loaded SettingReaders
     * @param SettingReader $settingReader The SettingReader which should be added
     */
    public function addReader(SettingReader $settingReader) {
        $this->reader[$settingReader->handleElement()] = $settingReader;
    }

    /**
     * By calling this function the process of asking the user to enter data will be started.
     * @return array Returns an array like array("answers"=>array(...), "configuration"=>array(...)) all user enters answers are contained in the array accessable via the "ansers" key all configuration relevant data is stored in the array accessable via the "configuration" key
     * @throws NoReaderFound Throws a exception if no reader for the element in the .xml is found
     */
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
                        if ($answer !== null) {
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
     * Returns the reader which is used to ask the user so enter data for this type of setting
     * @param type $type the type of setting for which a {@see SettingReader} is searched
     * @return \SettingReader Returns a {@see SettingReader} or if none is fund throws a  {@see NoReaderFound} exception
     * @throws NoReaderFound Throws a {@see NoReaderFound} exception if no {@see SettingReader} for the $type of element is added via {@see PhpConfigMaker::addReader()}
     */
    private function getReader($type) {
        if (isset($this->reader[$type])) {
            return $this->reader[$type];
        }
        throw new NoReaderFound();
    }

    /**
     * 
     * @param \DOMElement $setting the element which dependencies should be checked
     * @param array $configuration An array containing the currently enters configuration
     * @return boolean Returns true if the dependencies for the element are satisfied
     */
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
    /**
     * Returns a new {@see PhpConfigMaker} with the basic {@see SettingReader}s loaded
     * @param string $xmlFile The fime name of the .xml file which contains the settings
     * @return \phpConfigMaker\PhpConfigMaker
     */
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
/**
 * NoReaderFound Exception 
 */
class NoReaderFound extends \Exception {
    
}
