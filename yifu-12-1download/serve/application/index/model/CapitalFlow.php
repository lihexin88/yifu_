<?php



namespace app\index\model;



use think\Model;



class CapitalFlow extends Model

{

    protected $table = 'sn_capital_flow';



    public function add_log($id, $number, $balance, $type, $data)

    {

        $map['uid'] = $id;

        $map['number'] = $number;

        $map['balance'] = $balance;

        $map['type'] = $type;

        $map['agent'] = $data['agent'];

        $map['staff'] = $data['staff'];

        $map['time'] = time();

        return $this->insert($map);

    }





    public function type_name($type)

    {

        switch ($type) {

            case 0:

                $name = '充值';

                break;

            case 1:

                $name = '提现';

                break;

            case 2:

                $name = '开仓冻结保证金';

                break;

            case 3:

                $name = '开仓冻结手续费';

                break;

            case 4:

                $name = '撤销解冻保证金';

                break;

            case 5:

                $name = '撤销解冻手续费';

                break;

            case 6:

                $name = '结算盈亏';

                break;

            case 7:

                $name = '平仓解冻保证金';

                break;

            default:

                $name = '';

                break;

        }

        return $name;

    }

}



