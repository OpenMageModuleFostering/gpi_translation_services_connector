<?php

class WorkStatus {
	private $percentaje;
    private $message;    

    public function __construct($percentaje, $message) {
    	$this->percentaje = $percentaje;
    	$this->message = $message;
    }


    /**
     * Gets the message value for this WorkStatus.
     * 
     * @return message
     */
    public function getMessage() {
        return $message;
    }


    /**
     * Sets the message value for this WorkStatus.
     * 
     * @param message
     */
    public function setMessage($message) {
        $this->message = $message;
    }


    /**
     * Gets the percentaje value for this WorkStatus.
     * 
     * @return percentaje
     */
    public function getPercentaje() {
        return $percentaje;
    }


    /**
     * Sets the percentaje value for this WorkStatus.
     * 
     * @param percentaje
     */
    public function setPercentaje($percentaje) {
        $this->percentaje = $percentaje;
    }
}
