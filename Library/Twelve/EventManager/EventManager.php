<?php
namespace Twelve\EventManager;

class EventManager
{
    protected $_handlers = [];

    public function bindEvent($vendorPackage = null, $vendorObject = null, $vendorCallback = null)
    {
        if(!isset($this->_handlers[$vendorPackage]))
            $this->_handlers = [];

        if (is_callable($vendorObject, $vendorCallback)) {
            $this->_handler[$vendorPackage] =
                [
                    'vendorPackage' => $vendorPackage,
                    'vendorObject' => $vendorObject,
                    'vendorCallback' => $vendorCallback,
                ];
            $this->_handlers = array_merge($this->_handler, $this->_handlers);
        }
    }

    public function unbindEvent($vendorPackage)
    {
        unset($this->_events[$vendorPackage]);
    }

    public function getEvent($vendorPackage)
    {
        return $this->_events[$vendorPackage];
    }

    public function triggerEvent($vendorPackage)
    {
        if (!empty($this->_handlers[$vendorPackage])) {
            $this->vendorCallback = func_get_args();
            array_shift($this->vendorCallback);
            call_user_func_array([
                $this->_handlers[$vendorPackage]['vendorObject'],
                $this->_handlers[$vendorPackage]['vendorCallback']],
                $this->vendorCallback);
        }
    }

    public function getCollection()
    {
        echo "<pre>";
        print_r($this->_handlers);
        echo "</pre>";
    }
}
