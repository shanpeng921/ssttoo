<?php
/**
 * 
 *
 */
class Runtime
{
    var $_startTime = 0;

    var $_stopTime = 0;
 
    function get_microtime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
 
    function start()
    {
        $this->_startTime = $this->get_microtime();
    }
 
    function stop()
    {
        $this->_stopTime = $this->get_microtime();
    }
 
    function spent()
    {
        return round(($this->_stopTime - $this->_startTime) * 1000, 1);
    }

}
