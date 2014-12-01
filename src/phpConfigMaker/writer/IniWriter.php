<?php

namespace phpConfigMaker\writer;

use phpConfigMaker\SettingWriter;

/**
 * Description of IniWriter
 *
 * @author mario
 */
class IniWriter extends SettingWriter {

    public function write($filename) {
        $out = "";
        foreach ($this->getConfiguration() as $key => $value) {
            if (is_array($value) || is_object($value)) {
                throw new \InvalidArgumentException("IniWriter can not handle arrays or objects");
            }
            if (strpos("=", $key) !== FALSE) {
                throw new \InvalidArgumentException("IniWriter can not handle '=' in configuration keys");
            }
            $out .= "$key=$value" . PHP_EOL;
        }
        return file_put_contents($filename, $out);
    }

}
