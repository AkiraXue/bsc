<?php

namespace Service;

class BaseService
{
    protected $error;
    protected $msg;
    protected $code;

    /**
     * BaseService constructor.
     * @throws \Exception
     */
    public function __construct()
    {;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function getCode()
    {
        empty($this->code) && $this->code = 3001;
        return $this->code;
    }

    /**
     * @return mixed
     */
    public static function getIns()
    {
        static $instances = [];

        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, $instances)) {
            $instances[$calledClass] = new $calledClass;
        }

        return $instances[$calledClass];
    }
}
