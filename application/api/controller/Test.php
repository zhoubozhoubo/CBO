<?php

namespace app\api\controller;

use app\util\BaseController;

class Test extends BaseController {
    public function index() {
        $this->debug([
            'TpVersion' => THINK_VERSION
        ]);

        return $this->buildSuccess([
            'Product'    => config('apiAdmin.APP_NAME'),
            'Version'    => config('apiAdmin.APP_VERSION'),
            'Company'    => config('apiAdmin.COMPANY_NAME'),
            'ToYou'      => "I'm glad to meet you（终于等到你！）"
        ]);
    }
}
