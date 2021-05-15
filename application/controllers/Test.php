<?php
/**
 * Test.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/15/21 11:59 AM
 */

/**
 * Class Test
 */
class Test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->_success('test');
    }
}