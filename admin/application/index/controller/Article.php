<?php

namespace app\index\controller;

use think\Request;
use app\common\model\Articles;
use app\common\model\Articletype;
use app\common\model\Level;
use app\common\model\Protocol;
use think\Controller;
use think\Db;

class Article extends Common
{
    private $Articles;
    private $Articletype;
    private $Level;
    private $Protocol;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Articles = new Articles();
        $this->Articletype = new Articletype();
        $this->Level = new Level();
        $this->Protocol = new Protocol();
    }

    /*
    * 文章
    *
    */
    public function index()
    {
        $map = '';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Articles->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /*
    *防骗公告展示
    */
    public function notice()
    {
        $list = Db::table('sn_notice')->select();
        $this->assign("list", $list);
        return $this->fetch();
    }

    /*
    *防骗公告修改页面
    */
    public function notice_edit()
    {
        $id = Request::instance()->param('id');
        $list = Db::table('sn_notice')->where('id=' . $id)->find();
        if ($list) {
            $this->assign('list', $list);
        } else {
            $this->redirect('Article/notice');
        }
        return $this->fetch();
    }

    /*
    *防骗公告修改
     */
    public function notice_add_edit()
    {
        if (request()->isAjax()) {

            $data = $_POST['arr'];
            $map['time'] = time();
            if (empty($data['title'])) {
                $r = msg_handle('请输入文章标题', 0);
            } else {
                $map['title'] = $data['title'];
                $map['type'] = $data['type'];
                $map['img'] = $data['img'];
                $map['status'] = $data['status'];
                $map['desc'] = $data['desc'];
                $map['content'] = $data['content'];
                $list = Db::table('sn_notice')->where('id=' . $data['id'])->update($map);
            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/notice');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 添加/修改功能
     * @return mixed
     */
    public function article_edit()
    {
        $id = Request::instance()->param('id');
        $article = $this->Articletype->where('pid=0')->select();
        if ($id) {
            $list = $this->Articles->where('id=' . $id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Article/index');
            }
        } else {
            $list = array('id' => '', 'title' => '', 'sort' => '', 'type_id' => '', 'source' => '', 'img' => '', 'status' => '1', 'abs' => '', 'content' => '');
            $this->assign('list', $list);
        }
        $this->assign('art', $article);
        return $this->fetch();
    }

    public function article_add_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['title'])) {
                $r = msg_handle('请输入文章标题', 0);
            } else {
                if (empty($data['id'])) {
                    $list = $this->Articles->insert($data);
                } else {

                    $map['title'] = $data['title'];
                    $map['sort'] = $data['sort'];
                    $map['type'] = $data['type'];

                    $map['source'] = $data['source'];
                    $map['img'] = $data['img'];
                    $map['status'] = $data['status'];

                    $map['abs'] = $data['abs'];
                    $map['content'] = $data['content'];
                    $list = Db::table('sn_new')->where('id=' . $data['id'])->update($map);
                }

            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 删除
     * @return mixed
     */
    public function del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->Articles->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /*
     * 文章分类
     * 
     */
    public function articletype()
    {
        $map = '';
        $name = input('get.name');
        if ($name) {
            $map['name'] = $name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Articletype->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 添加/修改功能
     * @return mixed
     */
    public function articletype_edit()
    {
        $id = Request::instance()->param('id');
        $map['pid'] = 0;
        $article = $this->Articletype->where($map)->select();
        if ($id) {
            $list = $this->Articletype->where('id=' . $id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Article/articletype');
            }

        } else {
            $list = array('id' => '', 'name' => '', 'sort' => '', 'source' => '', 'img' => '', 'status' => '1', 'desc' => '');
            $this->assign('list', $list);
        }
        $this->assign('art', $article);
        return $this->fetch();
    }

    public function articletype_add_edit()
    {
        if (request()->isAjax()) {
            // return 1;
            $data = $_POST['arr'];
            $data['time'] = time();
            if (empty($data['name'])) {
                $r = msg_handle('请输入类别名称', 0);
            } else {
                if (empty($data['id'])) {
                    $list = $this->Articletype->insert($data);
                } else {
                    $map['name'] = $data['name'];
                    $map['sort'] = $data['sort'];
                    $map['source'] = $data['source'];
                    $map['img'] = $data['img'];
                    $map['desc'] = $data['desc'];
                    $map['time'] = time();
                    $list = $this->Articletype->where('id=' . $data['id'])->update($map);
                }
            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/articletype');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 删除
     * @return mixed
     */
    public function articletype_del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->Articletype->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/articletype');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 各种协议
     * @return mixed
     */
    public function agree()
    {
        $map = '';
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Protocol->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
    }

    /**
     * 添加/修改功能
     * @return mixed
     */
    public function agree_edit()
    {
        $id = Request::instance()->param('id');
        if ($id) {
            $list = $this->Protocol->where('id=' . $id)->find();
            if ($list) {
                $this->assign('list', $list);
            } else {
                $this->redirect('Article/agree');
            }
        } else {
            $list = array('id' => '', 'title' => '', 'desc' => '', 'content' => '');
            $this->assign('list', $list);
        }
        return $this->fetch();
    }

    /**
     * 修改协议信息
     * @return array
     */
    public function agree_add_edit()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            $data['time'] = time();
            // return $data['name'];
            if (empty($data['title'])) {
                $r = msg_handle('请输入协议标题', 0);
                return $r;
            } elseif (empty($data['desc'])) {
                $r = msg_handle('请输入描述信息', 0);
                return $r;
            } elseif (empty($data['content'])) {
                $r = msg_handle('请输入协议内容', 0);
                return $r;
            } else {
                if (empty($data['id'])) {
                    $list = $this->Protocol->insert($data);
                } else {
                    $map['title'] = $data['title'];
                    $map['desc'] = $data['desc'];
                    $map['content'] = $data['content'];
                    $list = $this->Protocol->where('id=' . $data['id'])->update($map);
                }
            }
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/agree');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败,未改动数据!');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }

    /**
     * 删除
     * @return mixed
     */
    public function agree_del()
    {
        if (request()->isAjax()) {
            $id = $_POST['id'];
            $list = $this->Agree->where('id=' . $id)->delete();
            if ($list) {
                //设置成功后跳转页面的地址
                $this->success('操作成功', 'Article/agree');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
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



















