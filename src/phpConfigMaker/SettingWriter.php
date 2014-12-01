<?php

namespace phpConfigMaker;

/**
 * Description of SettingWriter+
 *
 * @author mario
 */
abstract class SettingWriter {
    
    private $configuration;
    
    function __construct($configuration) {
        $this->configuration = $configuration;
    }

    
    public function getConfiguration() {
        return $this->configuration;
    }
    
    public abstract function write($filename);
    
}
