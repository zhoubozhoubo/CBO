<?php

namespace app\index\controller;

use think\Controller;

class News extends Controller{
    public function index(){
        return $this->fetch();
    }
}