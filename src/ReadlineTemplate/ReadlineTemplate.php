<?php

namespace ReadlineTemplate;

/**
 * Use this class to ask the user to enter the required settings from a .xml file
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class ReadlineTemplate {

    /**
     * Holds the template
     * @var string
     */
    private $template;

    /**
     * Holds references to the used DataReaders (a DataReader questions the user to enter data)
     * @var array
     */
    private $reader = array();

    /**
     * Creates a new PhpConfigMaker object with out any DataReader loaded
     * @param string $template the template in xml format
     */
    function __construct($template) {
        $this->template = $template;
    }

    /**
     * Adds a {@see \ReadlineTemplate\DataReader} the loaded DataReader
     * @param DataReader $settingReader The DataReader which should be added
     */
    public function addReader(DataReader $settingReader) {
        $this->reader[$settingReader->handleElement()] = $settingReader;
    }

    /**
     * By calling this function the process of asking the user to enter data will be started.
     * @return array Returns an array like array("answers"=>array(...), "configuration"=>array(...)) all user enters answers are contained in the array accessable via the "ansers" key all configuration relevant data is stored in the array accessable via the "configuration" key
     * @throws NoReaderFound Throws a exception if no reader for the element in the .xml is found
     */
    public function run() {
        $dom = new \DOMDocument();
        $dom->loadXML($this->template);
        $answers = array();
        $configuration = array();
        $requestedReader = array();
        $settings = $dom->getElementsByTagName("Settings")->item(0);
        foreach ($settings->childNodes as $setting) {
            if ($setting instanceof \DOMElement) {
                if ($this->checkDepends($requestedReader, $setting, $answers)) {
                    $requestedReader[$setting->getAttribute("key")] = $setting->tagName;
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
     * @param string $type the type of setting for which a {@see DataReader} is searched
     * @return \DataReader Returns a {@see SetDataReaderr if none is fund throws a  {@see NoReaderFound} exception
     * @throws NoReaderFound Throws a {@see NoReaderFound} exception if no {@see DataReader} for the $type of element is added via {@see PhpConfigMaker::addReader()}
     */
    private function &getReader($type) {
        if (isset($this->reader[$type])) {
            return $this->reader[$type];
        }
        throw new NoReaderFound();
    }

    /**
     * 
     * @param array $readers An array which contains the dependency key and the name of the used {@see DataReader}
     * @param \DOMElement $setting the element which dependencies should be checked
     * @param array $configuration An array containing the currently enters configuration
     * @return boolean Returns true if the dependencies for the element are satisfied
     */
    private function checkDepends(array $readers, \DOMElement $setting, array $configuration) {
        if ($setting->hasAttribute("depends")) {
            $depKey = $setting->getAttribute("depends");
            if (isset($configuration[$depKey]) && isset($readers[$depKey])) {
                if ($setting->hasAttribute("depends-equals")) {
                    return $this->getReader($readers[$depKey])->equalsDependency($setting->getAttribute("depends-equals"), $configuration[$setting->getAttribute("depends")]);
                } else {
                    return !in_array($configuration[$depKey], array(false, null, ""));
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function loadDefaultReader() {
        $this->addReader(new reader\Boolean());
        $this->addReader(new reader\Integer());
        $this->addReader(new reader\ListChoice());
        $this->addReader(new reader\NamedListChoice());
        $this->addReader(new reader\MultiListChoice());
        $this->addReader(new reader\Number());
        $this->addReader(new reader\Text());
        $this->addReader(new reader\Hidden());
        $this->addReader(new reader\File());
    }

    public function isValidTemplate() {
        $dom = new \DOMDocument();
        $dom->loadXML($this->template);
        libxml_use_internal_errors(false);
        return $dom->schemaValidate(__DIR__ . DIRECTORY_SEPARATOR . "Settings.xsd");
    }

}

/**
 * NoReaderFound Exception 
 */
class NoReaderFound extends \Exception {
    
}
