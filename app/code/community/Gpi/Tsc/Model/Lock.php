<?php

/**
 * Lock manager to ensure our cron doesn't run twice at the same time.
 *
 * Inspired by the lock mechanism in Mage_Index_Model_Process
 *
 * Usage:
 * 
 * $lock = Mage::getModel('gpi_tsc/cron_lock');
 *
 * if (!$lock->isLocked()) {
 *      $lock->lock();
 *      // Do your stuff
 *      $lock->unlock();
 * }
 */
class Gpi_Tsc_Model_Lock
{
    /**
     * Process lock properties
     */
    protected $_isLocked = null;
    protected $_lockFile = null;
	
	/**
     * Singleton instance
     *
     * @var Gpi_Tsc_Model_Lock
     */
    protected static $_instance;
	
	
	/**
     * Constructor
     */
    protected function __construct()
    {
    }
	
	/**
     * Get lock singleton instance
     *
     * @return Gpi_Tsc_Model_Lock
     */
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	

    /**
     * Get lock file resource
     *
     * @return resource
     */
    protected function _getLockFile()
    {
        if ($this->_lockFile === null) {
            $varDir = Mage::getConfig()->getVarDir('locks');
            $file = $varDir . DS . 'gpi_tsc_cron.lock';
            if (is_file($file)) {
                $this->_lockFile = fopen($file, 'w');
            } else {
                $this->_lockFile = fopen($file, 'x');
            }
            fwrite($this->_lockFile, date('r'));
        }
        return $this->_lockFile;
    }

    /**
     * Lock process without blocking.
     * This method allow protect multiple process runing and fast lock validation.
     *
     * @return Mage_Index_Model_Process
     */
    public function lock()
    {
        $this->_isLocked = true;
        flock($this->_getLockFile(), LOCK_EX | LOCK_NB);
        return $this;
    }

    /**
     * Lock and block process.
     * If new instance of the process will try validate locking state
     * script will wait until process will be unlocked
     *
     * @return Mage_Index_Model_Process
     */
    public function lockAndBlock()
    {
        $this->_isLocked = true;
        flock($this->_getLockFile(), LOCK_EX);
        return $this;
    }

    /**
     * Unlock process
     *
     * @return Mage_Index_Model_Process
     */
    public function unlock()
    {
        $this->_isLocked = false;
        flock($this->_getLockFile(), LOCK_UN);
        return $this;
    }

    /**
     * Check if process is locked
     *
     * @return bool
     */
    public function isLocked()
    {
        if ($this->_isLocked !== null) {
            return $this->_isLocked;
        } else {
            $fp = $this->_getLockFile();
            if (flock($fp, LOCK_EX | LOCK_NB)) {
                flock($fp, LOCK_UN);
                return false;
            }
            return true;
        }
    }

    /**
     * Close file resource if it was opened
     */
    public function __destruct()
    {
        if ($this->_lockFile) {
            fclose($this->_lockFile);
        }
    }
}