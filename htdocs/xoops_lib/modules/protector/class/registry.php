<?php
//  Author: Trabis
//  URL: http://www.xuups.com
//  E-Mail: lusopoemas@gmail.com

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

class ProtectorRegistry
{
    var $_entries;
    var $_locks;
    
    function ProtectorRegistry()
    {
        $this->_entries = array();
        $this->_locks = array();
    }

    function &getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new ProtectorRegistry();
        }
        return $instance;
    }

    function setEntry($key, $item)
    {
        if ($this->isLocked($key) == true) {
            trigger_error('Unable to set entry `' . $key . '`. Entry is locked.', E_USER_WARNING);
            return false;
		}

        $this->_entries[$key] = $item;
        return true;
    }
    
    function unsetEntry($key)
    {
		unset($this->_entries[$key]);
	}
	
    function getEntry($key)
    {
        if (isset($this->_entries[$key]) == false) {
			return null;
		}
    
        return $this->_entries[$key];
    }
    
    function isEntry($key)
    {
        return ($this->getEntry($key) !== null);
    }
    
    function lockEntry($key)
    {
        $this->_locks[$key] = true;
        return true;
    }

    function unlockEntry($key)
    {
        unset($this->_locks[$key]);
    }
    
    function isLocked($key)
    {
        return (isset($this->_locks[$key]) == true);
    }
    
    function unsetAll()
    {
        $this->_entries = array();
        $this->_locks = array();
    }
}
?>
