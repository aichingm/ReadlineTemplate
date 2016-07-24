<?php

namespace ReadlineTemplate;

/**
 * Use this class to load the template and ask the user to enter the required elements 
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
     * Creates a new ReadlineTemplate object with out any DataReader loaded
     * @param string $template the template in xml format
     */
    function __construct($template) {
        $this->template = $template;
    }

    /**
     * Adds a {@see \ReadlineTemplate\DataReader} the loaded DataReader
     * @param DataReader $dataReader The DataReader which should be added
     */
    public function addReader(DataReader $dataReader) {
        $this->reader[$dataReader->handleElement()] = $dataReader;
    }

    /**
     * By calling this function the process of asking the user to enter data will be started.
     * @return array Returns an array like array(0 => array(...), array(...), "extra"=>array(...), "data"=>array(...))
     * @throws NoReaderFound Throws a exception if no reader for the element in the .xml is found
     */
    public function run() {
        $dom = new \DOMDocument();
        $dom->loadXML($this->template);
        $extra = array();
        $data = array();
        $readerKeyMapping = array();
        $template = $dom->getElementsByTagName("Template")->item(0);
        foreach ($template->childNodes as $element) {
            if ($element instanceof \DOMElement) {
                if ($this->checkDepends($readerKeyMapping, $element, array_merge($extra, $data))) {
                    $readerKeyMapping[$element->getAttribute("key")] = $element->tagName;
                    $reader = $this->getReader($element->tagName);
                    $reader->setUp($element);
                    $answer = $reader->ask($element->getAttribute("prompt"));
                    if (!$element->hasAttribute("exclude") && !$element->getAttribute("exclude") == "from-data") {
                        if ($answer !== null) {
                            $data[$element->getAttribute("key")] = $answer;
                        } elseif ($element->hasAttribute("default")) {
                            $data[$element->getAttribute("key")] = $reader->convertDefault($element->getAttribute("default"));
                        }
                    } else {
                        $extra[$element->getAttribute("key")] = $answer;
                    }
                }
            }
        }
        return array(&$data, &$extra, "data" => &$data, "extra" => &$extra);
    }

    /**
     * Returns the reader which is used to ask the user to enter data for this type of element
     * @param string $type the type of element for which a {@see DataReader} is searched
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
     * Cecks if all dependencies for the element are satisfied
     * @param array $readerKeyMapping An array which contains the dependency key and the name of the used {@see DataReader}
     * @param \DOMElement $element the element which dependencies should be checked
     * @param array $data An array containing the currently enters configuration
     * @return boolean Returns true if the dependencies for the element are satisfied
     */
    private function checkDepends(array $readerKeyMapping, \DOMElement $element, array $data) {
        if ($element->hasAttribute("depends")) {
            $depKey = $element->getAttribute("depends");
            if (isset($data[$depKey]) && isset($readerKeyMapping[$depKey])) {
                if ($element->hasAttribute("depends-equals")) {
                    return $this->getReader($readerKeyMapping[$depKey])->equalsDependency($element->getAttribute("depends-equals"), $data[$element->getAttribute("depends")]);
                } else {
                    return !in_array($data[$depKey], array(false, null, ""));
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Loads all reader which are implmented in /src/ReadlineTemplate/readers/
     */
    public function loadDefaultReader() {
        $this->addReader(new reader\Boolean());
        $this->addReader(new reader\Integer());
        $this->addReader(new reader\ListChoice());
        $this->addReader(new reader\NamedListChoice());
        $this->addReader(new reader\MultiListChoice());
        $this->addReader(new reader\Number());
        $this->addReader(new reader\Text());
        $this->addReader(new reader\Regex());
        $this->addReader(new reader\Hidden());
        $this->addReader(new reader\File());
    }

    /**
     * Tests if the loaded template complies with the rules defined in a .xsd-schema.
     * @param string $schema Path to an alternative schema
     * @return boolean
     */
    public function isValidTemplate($schema = null) {
        if ($schema == null) {
            $schema = __DIR__ . DIRECTORY_SEPARATOR . "Template.xsd";
        }
        $dom = new \DOMDocument();
        $dom->loadXML($this->template);
        libxml_use_internal_errors(false);
        return $dom->schemaValidate($schema);
    }

}

/**
 * NoReaderFound Exception 
 */
class NoReaderFound extends \Exception {
    
}
