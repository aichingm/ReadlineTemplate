<?php

namespace ReadlineTemplate\reader;

use ReadlineTemplate\DataReader;

/**
 * This {@see DataReader} can handle "Boolean" elements
 * @author Mario Aichinger <aichingm@gmail.com>
 */
class Boolean extends DataReader {

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
     * {@inheritdoc}
     */
    public function ask($prompt) {
        return $this->readlineYesNo($prompt, true);
    }

    /**
     * {@inheritdoc}
     */
    public function convertDefault($default) {
        if ($default == "true") {
            return true;
        }
        return false;
    }

    /**
     * Returns the type of element which can be handled by this class in this case "Boolean"
     * @return string Returns "Boolean"
     */
    public function handleElement() {
        return "Boolean";
    }

    /**
     * {@inheritdoc}
     */
    public function equalsDependency($dependencyEquals, $value) {
        return parent::equalsDependency($this->convertDefault($dependencyEquals), $value);
    }

}
