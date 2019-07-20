<?php

namespace app\index\controller;

use think\Controller;

class Download extends Controller{
    public function index(){
        return $this->fetch();
    }
}