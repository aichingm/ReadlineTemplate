<?php

namespace ReadlineTemplate;

/**
 * Base class for all Writer classes
 * @author Mario Aichinger <aichingm@gmail.com>
 */
abstract class DataWriter {
    /**
     * Holds the data which should be written to a file
     * @var array
     */
    private $configuration;
    /**
     * Crates a new DataWriter object
     * @param array $configuration Contains all data which was should be written to a file
     */
    function __construct($configuration) {
        $this->configuration = $configuration;
    }
    /**
     * Returns the configuration
     * @return array Returns the configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }
    /**
     * Returns true if the configuration was witten to a file or false if not
     * @param string $filename The file name of the new configuration file
     * @return boolean Returns true if the configuration was witten to a file or false if not
     */
    public abstract function write($filename);
}
