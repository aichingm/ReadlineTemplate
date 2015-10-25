<?php

namespace ReadlineTemplate\writer;

use ReadlineTemplate\SettingWriter;

/**
 * Description of PhpWriter
 *
 * @author mario
 */
class PhpWriter extends SettingWriter {
    private $arrayName = "config";
    function getArrayName() {
        return $this->arrayName;
    }

    function setArrayName($arrayName) {
        $this->arrayName = $arrayName;
    }

        public function write($filename) {
        $out = "<?php".PHP_EOL;
        foreach ($this->getConfiguration() as $key => $value) {
            $out .= '$'.$this->getArrayName().'[' . var_export($key, true) . '] = ' . var_export($value, true) . ';' . PHP_EOL;
        }
        file_put_contents($filename, $out);
    }

}
