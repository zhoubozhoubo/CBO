<?php

namespace app\index\controller;

use think\Controller;

class PlayerScore extends Controller{
    public function index(){
        return $this->fetch();
    }
    public function details(){
        return $this->fetch();
    }
}