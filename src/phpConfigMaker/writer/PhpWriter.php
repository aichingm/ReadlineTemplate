<?php

namespace phpConfigMaker\writer;

use phpConfigMaker\SettingWriter;

/**
 * Description of PhpWriter
 *
 * @author mario
 */
class PhpWriter extends SettingWriter {

    public function write($filename) {
        $out = "<?php".PHP_EOL;
        foreach ($this->getConfiguration() as $key => $value) {
            $out .= '$config[' . var_export($key, true) . '] = ' . var_export($value, true) . ';' . PHP_EOL;
        }
        file_put_contents($filename, $out);
    }

}
