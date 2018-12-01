<?php



namespace app\index\model;



use think\Model;



class ConditionSet extends Model

{

    protected $table = 'sn_condition_set';

    public function status_name($status)

    {

        switch ($status) {

            case 1:

                $name = '未触发';

                break;

            case 2:

                $name = '成功';

                break;

            case 3:

                $name = '失败';

                break;

            case 4:

                $name = '删除';

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

            default:

                $name = '错单';

                break;

        }

        return $name;

    }

    public function continuous_name($status)

    {

        switch ($status) {

            case 1:

                $name = '大于等于';

                break;

            case 2:

                $name = '等于';

                break;

            case 3:

                $name = '小于等于';

                break;

            default:

                $name = '错单';

                break;

        }

        return $name;

    }

    public function choice_name($data)

    {

        if($data["continuous_choice"] == 1){
        	$type=$this->type_name($data["condition_type"]);
        	$continuous_eq=$this->continuous_name($data["continuous_eq"]);
        	$trigger=$type."连续".$data["continuous_num"]."次".$continuous_eq.$data["continuous_price"];
        }else if($data["continuous_choice"] == 2){
        	$time=date("Y-m-d H:i:s",$data["continuous_time"]);
        	$trigger="时间达到".$time;
        }else{
        	$trigger="预埋,手动发出";
        }

        return $trigger;

    }

}