<?php 
namespace app\index\controller;

use think\Request;
use app\common\model\Custs;
class Cust extends Common{
	private $Custs;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->Custs = new Custs();
    }
    /*
    	列表
     */
    public function index()
    {
        $map="";
        $name=input('get.name');
        if($name){
            $map['name']=$name;
        }
        $map = $this->query_time($map, input('get.start_query'), input('get.end_query'));
        $current_page = page_judge(input('get.page'));
        $list = $this->Custs->query_log($map, $current_page, $this->num);
        $page = page_handling($list['num'], $current_page, $this->show, $list['total']);
        $this->assign('arr', $this->arr_info(input('get.')));
        $this->assign('empty', $this->null_html(12));
        $this->assign('page', $page);
        $this->assign('data', $list['data']);
        return $this->fetch();
    }
	/*
	 * 删除
	 * $id 
	 */
	public function del(){
		if(request()->isGet()) {
            $id = $_GET['id'];
            $res= $this->Custs->where('id='.$_GET['id'])->delete();
            if($res){
            //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
            $this->success('操作成功', 'Cust/index');
            } else {
                //错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('操作失败');
            }

        }else{
            $this->redirect('Cust/index');
        }
			
		
	}
    /**
     * 添加信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add()
    {
        
        return $this->fetch();
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
        $id = $_GET['id'];
        $data=$this->Custs->where('id='.$id)->find();

        $this->assign('list', $data);
        return $this->fetch();
    }
     /*
        执行添加/修改
     */
    public function add_log()
    {
        $data = $_POST;
        $list = $this->Custs->add_save($data);
        if($list){
            //设置成功后跳转页面的地址
            $this->success('操作成功');
        }else{
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败');
        }
    }

     /*
        执行添加/修改
     */
    public function add_do()
    {
        $data = $_POST;
        $list = $this->Custs->doadd($data);
        if($list){
            //设置成功后跳转页面的地址
            $this->success('操作成功');
        }else{
            //错误页面的默认跳转页面是返回前一页，通常不需要设置
            $this->error('操作失败');
        }
    }
    
}


 ?>