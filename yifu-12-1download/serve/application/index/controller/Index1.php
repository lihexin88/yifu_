<?php

namespace app\index\controller;

use think\Db;
use think\Request;
use think\Controller;

class Index1 extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    public function aa()
    {
        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var%20t1nf_IC0=/InnerFuturesNewService.getMinLine?symbol=IC0
        //  https://stock.finance.sina.com.cn/futures/api/jsonp.php/var%20kke_future_nf_IC1810=/InterfaceInfoService.getMarket?category=nf&symbol=IC
    }

    public function cff_ex1()
    {
        $list = do_get('https://hq.sinajs.cn/?_=0.44639441210177244&list=nf_IC1903');
        $list = str_replace(array("var hq_str_", "\""), "", $list);
        $list = str_replace(array("="), ",", $list);
        $list = explode(',', trim($list));
        $list['num'] = $list[4] - $list[15];
        $list['ratio'] = round(($list['num'] / $list[15]) * 100, 2);
        $list['high'] = $list[15] * (1.1);
        $list['low'] = $list[15] * (0.9);
    }

    //主页
    public function index()
    {
        $list = do_get('https://hq.sinajs.cn/?_=0.44639441210177244&list=nf_AG0');
        $list = str_replace(array("var hq_str_", "\""), "", $list);
        $list = str_replace(array("="), ",", $list);
        $list = explode(',', trim($list));
        print_r($list);
        //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=ZC18Z0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery17203355522154108621_1540207764158&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1540207764268
//      //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=GC00Y0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery172013425676010385068_1539305723358&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1539305823345
//      //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=GC18V0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery17204176402881150558_1539305900001&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1539305960030
//      //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=GC18X0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery1720883017173250153_1539306165661&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1539306185755
//       //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=GC18Z0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery172045251408528055315_1539306217163&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1539306217242
//      //http://nufm.dfcfw.com/EM_Finance2014NumericApplication/JS.aspx?type=CT&cmd=GC19G0&sty=FDPBPFBTA&st=z&sr=&p=&ps=&cb=jQuery17201883643827573731_1539306775663&js=([[(x)]])&token=7bc05d0d4c3c22ef9fca8c2a912d779c&_=1539306815782

        exit();
//        $list = Db::name('contract')->where(array('status' => 1))->select();
//        $codes = array();
//        foreach ($list as $key => $value) {
//            if (!in_array($value['code'], $codes)) {
//                array_push($codes, $value['code']);
//            }
//        }
//        $codes = implode(',', $codes);
//        $codes = $this->cff_ex($codes);
//        foreach ($list as $key => $value) {
//            $list[$key]['price'] = $this->data_close($codes, $value['code']);
//        }
//        $symbol = 'IC1810';
//        $minute = '30';
        //$list = do_get('https://stock2.finance.sina.com.cn/futures/api/jsonp.php/' . $symbol . '=/InnerFuturesNewService.getFewMinLine?symbol=' . $symbol . '&type=' . $minute);

        //print_r($list);
        //5日
        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/IC1810=/InnerFuturesNewService.getFourDaysLine?symbol=IC1810

        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/IC1810=/InnerFuturesNewService.getDailyKLine?symbol=IC1810&_=2018_9_30

        //30分线
        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var%20_IC0_30_1538275385534=/InnerFuturesNewService.getFewMinLine?symbol=IC0&type=30
        //60分线
        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var%20_IC0_60_1538275386197=/InnerFuturesNewService.getFewMinLine?symbol=IC0&type=60
        // https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var%20t1nf_IC0=/InnerFuturesNewService.getMinLine?symbol=IC0 分时
        //https://stock2.finance.sina.com.cn/futures/api/jsonp.php/var%20_IC02018_9_30=/InnerFuturesNewService.getDailyKLine?symbol=IC0&_=2018_9_30 年
        //https://hq.sinajs.cn/?_=0.3262616165499699&list= nf_IC0
        //https://hq.sinajs.cn/?_=0.723964681479516&list=hf_CL
        $this->assign('list', array());
        return $this->fetch();
    }

    /**
     * 股票数据处理
     * @param $data array 股票价位
     * @param $short string 股票代码
     * @return int
     */
    private function data_close($data, $short)
    {
        $price = 0;
        foreach ($data as $key => $value) {
            if ($value[0] == $short) {
                $price = $value;
                break;
            }
        }
        return $price;
    }

    public function cff_ex($codes)
    {
        $contents = do_get('https://hq.sinajs.cn/?list=' . $codes);
        $list = explode(';', $contents);
        $arr = array();
        foreach ($list as $key => $value) {
            if (trim($value)) {
                $value = str_replace(array("var hq_str_", "\""), "", $value);
                $value = str_replace(array("="), ",", $value);
                $arr[$key] = explode(',', trim($value));
            }
        }
        foreach ($arr as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k < 2) {
                    $value[$k] = trim(iconv('GBK', 'UTF-8', $v));
                } else if ($k < 9) {
                    $value[$k] = sprintf("%01.2f", round($v, 2));
                }
            }
            $arr[$key] = $value;
        }
        return $arr;
    }

}

