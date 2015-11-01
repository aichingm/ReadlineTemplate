<?php

namespace ReadlineTemplate;

/**
 * Base class for all Writer classes
 * @author Mario Aichinger <aichingm@gmail.com>
 */
abstract class DataWriter {

    /**
     * Holds the data which should be stored
     * @var array
     */
    private $data;

    /**
     * Crates a new DataWriter object
     * @param array $data Contains all data which will be stored
     */
    function __construct($data) {
        $this->data = $data;
    }

    /**
     * Returns the configuration
     * @return array Returns the configuration
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Returns true if the configuration was stored or false if not
     * @param array $writeConfiguration The configuration array which contains for example the file destination (checkout the parameter which your writer needs)
     * @return boolean Returns true if the configuration was stored or false if not
     */
    public abstract function write(array $writeConfiguration);
}
