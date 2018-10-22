<?php

use Phalcon\Mvc\Model;

class SysNotice extends Model
{
    public function initialize()
    {
        $this->useDynamicUpdate(true);
    }

    public function getSource()
    {
        return "sys_notice";
    }
}
