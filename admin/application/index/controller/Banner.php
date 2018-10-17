<?php 
namespace app\index\controller;
use think\Request;
use app\common\model\Play;
/*
 * 轮播控制器
 */
class Banner extends Common{
	private $Banner;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Play = new Play();
    }
    /*
    	轮播列表
     */
	public function index(){
        $map = $this->query_time('', input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Play->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('list', $list['data']);
        return $this->fetch();
	}
	/*
	 * 删除轮播
	 * $id
	 */
	public function del(){
		if(request()->isGet()) {
            $url=$this->Play->where('id='.$_GET['id'])->find();
            if($url['type']!=1){
                $this->error('PC端banner不能删除!');
            }
            $result=substr($url['pic'],1);
            if(file_exists($result))
            {
                unlink($result);
            }
			$res= $this->Play->where('id='.$_GET['id'])->delete();
            $da['uid']=session('admin')['id'];
            $da['time']=time();
            $da['desc']='管理员'.session('admin')['name'].'删除了id为'.$_GET['id'].'轮播图';
            $this->admin_log($da);
			if($res){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('操作成功', 'Banner/index');
	        } else {
	            //错误页面的默认跳转页面是返回前一页，通常不需要设置
	            $this->error('操作失败');
	        }
		}else{
			$this->redirect('Banner/index');
	    }
		
	}
	/**
     * 修改信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit()
    {
    	$id=Request::instance()->param('id');
    	if($id){
    		 if(request()->isGet()) {
	            $list = $this->Play->where(array('id' => $_GET['id']))->find();
	            if ($list) {
	                $this->assign('list', $list);
	            } else {
	                $this->redirect('Banner/index');
	            }
	        }else {
	            $this->redirect('Banner/index');
	        }
    	}else{
//    	    return 1;
    		$list=array('id'=>'','title'=>'','sort'=>'','url'=>'','pic'=>'','status'=>'1',);
    		$this->assign('list', $list);
    	}
        return $this->fetch();
    }
    /**
     * 修改轮播
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function modify_play()
    {
        if (request()->isAjax()) {
            $data = $_POST['arr'];
            if (empty($data['title'])) {
                $r = msg_handle('轮播名称不能为空', 0);
            }elseif (empty($data['pic'])) {
                $r = msg_handle('轮播图片不能为空', 0);
            }elseif (empty($data['url'])) {
                $r = msg_handle('轮播url不能为空', 0);
            } else {
                $res = $this->Play->modify_log($data);
                $da['uid']=session('admin')['id'];
                $da['time']=time();
                if($data['id']){
                    $da['desc']='管理员'.session('admin')['name'].'修改了id为'.$data['id'].'轮播图信息';
                }else{
                    $da['desc']='管理员'.session('admin')['name'].'添加了轮播图';
                }
                $this->admin_log($da);
                if ($res) {
                    $r = msg_handle('操作成功', 1);
                } else {
                    $r = msg_handle('操作失败', 0);
                }
            }
        } else {
            $r = msg_handle('错误操作', 0);
        }
        return $r;
    }
    
}




 ?>