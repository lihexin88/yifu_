<?php

namespace app\index\controller;

use think\Request;

class Upload extends Common
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /**
     * 上传图片
     */
    public function upload()
    {
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                echo $info->getSaveName();
            } else {
                echo $file->getError();
            }
        }
    }
}