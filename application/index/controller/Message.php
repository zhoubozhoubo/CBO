<?php

namespace app\index\controller;

use think\Controller;

class Message extends Controller{
    public function index(){
        return $this->fetch();
    }
}