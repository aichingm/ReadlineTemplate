<?php
namespace phpConfigMaker;
/**
 * 
 */
abstract class SettingReader {

    private $setting;

    public function setUp(\DOMElement $setting) {
        $this->setting = $setting;
    }

    protected function isYes($str, $withEmpty = false) {
        if ($withEmpty) {
            return in_array($str, array("y", "Y", "yes", "Yes", ""));
        } else {
            return in_array($str, array("y", "Y", "yes", "Yes"));
        }
    }

    protected function isNo($str, $withEmpty = false) {
        if ($withEmpty) {
            return in_array($str, array("n", "N", "no", "No", ""));
        } else {
            return in_array($str, array("n", "N", "no", "No"));
        }
    }

    protected function readline($prompt) {
        if (!function_exists("readline")) {
            echo $prompt;
            return rtrim(fgets(STDIN), "\n");
        } else {
            return readline($prompt);
        }
    }

    protected function readlineTrimAll($prompt) {
        return trim(readline($prompt));
    }

    protected function readlineOrDefault($prompt, $default) {
        $line = readlineTrimAll($prompt);
        if ($line == "") {
            return $default;
        } else {
            return $line;
        }
    }

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
     * 
     * @return \DOMElement
     */
    protected function getSetting() {
        return $this->setting;
    }

    public abstract function ask($prompt);

    public abstract function handleElement();

    public function convertDefault($default) {
        return $default;
    }

    public function equalsDependency($dependencyEquals, $value) {
        return $dependencyEquals == $value;
    }

}
