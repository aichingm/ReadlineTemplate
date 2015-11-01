<?php

namespace ReadlineTemplate;

/**
 * abstract class DataReader
 * This is the base class for all DataReaders. 
 * @author Mario Aichinger <aichingm@gmail.com>
 */
abstract class DataReader {

    /**
     * Holds the current element is should be asked the usre to ener data for. The 'current' means the the element changes from time to time
     * @var \DOMElement
     */
    private $element;

    /**
     * Prepare for DataReader::ask($p)
     * @param \DOMElement $element The element which should be asked next
     */
    public function setUp(\DOMElement $element) {
        $this->element = $element;
    }

    /**
     * Returns true if the parameter $str contains any kind of "yes" ("y", "Y", "yes", "Yes") if the $withEmpty parameter is true an empty $str will lead also to true
     * @param string $str The string which should be tested
     * @param boolean $withEmpty Pass true to tell the function "" (empty string) is also a valid form of "yes"
     * @return boolean Returns true if the string contains a form of "yes" or false if not 
     */
    protected function isYes($str, $withEmpty = false) {
        if ($withEmpty) {
            return in_array($str, array("y", "Y", "yes", "Yes", ""));
        } else {
            return in_array($str, array("y", "Y", "yes", "Yes"));
        }
    }

    /**
     * Returns true if the parameter $str contains any kind of "no" ("n", "N", "no", "No") if the $withEmpty parameter is true an empty $str will lead also to true
     * @param string $str The string which should be tested
     * @param boolean $withEmpty Pass true to tell the function "" (empty string) is also a valid form of "no"
     * @return boolean Returns true if the string contains a form of "no" or false if not 
     */
    protected function isNo($str, $withEmpty = false) {
        if ($withEmpty) {
            return in_array($str, array("n", "N", "no", "No", ""));
        } else {
            return in_array($str, array("n", "N", "no", "No"));
        }
    }
    /**
     * Reads aline from the command line. If readline is not installed it will try it best to read a line anyways
     * @param string $prompt The prompt which indecates what data the user should enter
     * @return string Returns a line ret from the command line
     */
    protected function readline($prompt) {
        if (!function_exists("readline")) {
            echo $prompt;
            return rtrim(fgets(STDIN), "\r\n");
        } else {
            return readline($prompt);
        }
    }
    /**
     * Reads a line from the command line and trims all whitespace from the beginn and the end. Uses {@see DataReader::readLine($prompt)} to read data from the command line.
     * @param string $prompt The prompt which indecates what data the user should enter
     * @return string Returns the trimed string
     */
    protected function readlineTrimAll($prompt) {
        return trim($this->readline($prompt));
    }
    /**
     * Reads a boolean from the coomand line interface. 
     * @param string $prompt The prompt which indecates what data the user should enter. Note that $prompt will be prepended to "[Y/n]: " or "[y/N]: " depending on the value of $defaultYes.
     * @param string $defaultYes If set to true the function will accept an empty string from the user as a "yes" or to false if an empty string should represent false
     * @return boolean Returns true or false whether the user entered "yes" or "no". Note that {@see DataReader::isYes($str)} function is uses to determine what the user entered 
     */
    protected function readlineYesNo($prompt, $defaultYes = true) {
        if ($defaultYes) {
            $d = " [Y/n]";
        } else {
            $d = " [y/N]";
        }
        do {
            $line = $this->readlineTrimAll($prompt . $d . ": ");
        } while (!$this->isNo($line, !$defaultYes) && !$this->isYes($line, $defaultYes));
        return $this->isYes($line, $defaultYes);
    }

    /**
     * Returns the element for the currently asked element
     * @return \DOMElement Returns the element for the currently asked element
     */
    protected function getElement() {
        return $this->element;
    }
    /**
     * Asks the user to enter appropriate data for the element
     * @param string $prompt The prompt which indecates what data the user should enter
     * @return null|string Returns the data which was enterd by the user or null to indecate that the default value should be used
     */
    public abstract function ask($prompt);
    /**
     * Returns the type of element which can be handled by this class
     * @return string Returns the type of element which can be handled by this class
     */
    public abstract function handleElement();
    /**
     * Returns the default value for this type of data.
     * @param string $default The default value which should be used if the user does not enter data for the element.
     * @return mixed Returns the conveted form of the default value
     */
    public function convertDefault($default) {
        return $default;
    }
    /**
     * Tests if the "dependency-equals" value from the .xml equals the value which was enterd by the user
     * @param string $dependencyEquals The value which is set as default in the element element 
     * @param  mixed $value The value which was entered by the user 
     * @return boolean
     */
    public function equalsDependency($dependencyEquals, $value) {
        return $dependencyEquals == $value;
    }

}
