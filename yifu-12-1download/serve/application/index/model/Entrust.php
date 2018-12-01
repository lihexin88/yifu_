<?php



namespace app\index\model;



use think\Model;



class Entrust extends Model

{

    protected $table = 'sn_entrust';



    public function add_log($id, $contract, $number, $price, $fee, $bond, $mold, $type, $direction, $pattern, $stock, $data, $order_id=0)

    {

        $map['uid'] = $id;

        $map['order'] = rand_order($id);

        $map['short'] = $contract['short'];

        $map['code'] = $contract['code'];

        $map['name'] = $contract['name'];

        $map['number'] = $number;

        $map['price'] = $price;

        $map['average'] = $price;

        $map['cost'] = $price;

        $map['fee'] = $fee;

        $map['frozen_fee'] = $fee;

        $map['bond'] = $bond;

        $map['frozen_bond'] = $bond;

        $map['total'] = $number * $price;

        $map['mold'] = $mold;

        $map['type'] = $type;

        $map['direction'] = $direction;

        $map['pattern'] = $pattern;

        $map['time'] = time();

        $map['agent'] = $data['agent'];

        $map['staff'] = $data['staff'];

        $map['fee_ratio'] = $stock['fee'];

        $map['bond_ratio'] = $stock['bond'];

        $map['close'] = $stock['close'];

        $map['wave_ratio'] = $stock['money'] / $stock['price'];

        $map['depot_id'] = $order_id;

        return $this->insert($map);

    }



    /**

     * 开仓取消委托

     * @param $data

     * @return int|string

     */

    public function cancel_entrust($data)

    {

        $map['id'] = $data['id'];

        $map['cancel'] = $data['number']-$data["finish"]-$data["cancel"];

        $map['surplus'] = 0;

        $map['status'] = 3;

        // if ($data['surplus'] == $data['number']) {

        //     $map['status'] = 3;

        // } else {

        //     $map['status'] = 4;

        // }

        $map['finish_time'] = time();

        return $this->where(array("id"=>$data["id"]))->update($map);

    }



    public function query_log($map)

    {

        $list = $this->where($map)->order(array('time desc'))->select();

        $data = array();

        foreach ($list as $key => $value) {
            $data[$key]['id'] = $value['id'];

            $data[$key]['contract'] = $value['name'];

            $data[$key]['price'] = $value['price'];

            $data[$key]['cancel'] = $value['cancel'];

            $data[$key]['direction'] = $value['direction'] == 0 ? '买' : '卖';

            $data[$key]['mold'] = $value['mold'] == 0 ? '开仓' : '平仓';

            $data[$key]['status'] = $this->status_name($value['status']);

            $data[$key]['number'] = $value['number'];

            $data[$key]['finish'] = $value['finish'];

            $data[$key]['surplus'] = $value['surplus'];

            $data[$key]['frozen_fee'] = $value['frozen_fee'];

            $data[$key]['frozen_bond'] = $value['frozen_bond'];

            $data[$key]['pattern'] = $value['pattern'] == 0 ? '投机' : ($value['pattern'] == 1 ? '套利' : '套保');

            $data[$key]['info'] = '信息';

            $data[$key]['time'] = date('H:i:s', $value['time']);

            $data[$key]['order'] = $value['order'];

            $data[$key]['name'] = $value['name'];

        }

        return $data;

    }
    /**

     * 平仓委托撤销

     * @param $data

     * @return int|string

     */

    public function sell_revocation($data){


        $map['cancel'] = $data['number']-$data["finish"];

        $map['surplus'] = 0;

        $map['status'] = 3;

        // if ($data['number']-$data["finish"] == $data['cancel']) {

        //     $map['status'] = 3;

        // } else {

        //     $map['status'] = 4;

        // }

        $map['finish_time'] = time();

        return $this->where(array("id"=>$data["id"]))->update($map);
    }



    public function status_name($status)

    {

        switch ($status) {

            case 0:

                $name = '未完成';

                break;

            case 1:

                $name = '部成';

                break;

            case 2:

                $name = '已完成';

                break;

            case 3:

                $name = '已撤销';

                break;

            case 4:

                $name = '部撤';

                break;

            default:

                $name = '错单';

                break;

        }

        return $name;

    }


     public function type_name($status)

    {

        switch ($status) {

            case 0:

                $name = '限价';

                break;

            case 1:

                $name = '最新价';

                break;

            case 2:

                $name = '对手价';

                break;

            case 3:

                $name = '挂单价';

                break;

            case 4:

                $name = '快速价';

                break;

            case 5:

                $name = '止盈价';

                break;

            case 6:

                $name = '止损价';

                break;

        }

        return $name;

    }

    public function mold_name($status)

    {

        switch ($status) {

            case 0:

                $name = '开仓';

                break;

            case 1:

                $name = '平仓';

                break;

        }

        return $name;

    }





}