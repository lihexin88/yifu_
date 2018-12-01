<?php



namespace app\index\model;



use think\Model;



class UserAccount extends Model

{

    protected $table = 'sn_user_account';



    public function user()

    {

        return $this->belongsTo('User', 'uid', 'id');

    }



    /**

     * 开仓委托

     * @param $list array 账户信息

     * @param $bond float 保证金

     * @param $fee float 手续费

     * @return mixed

     */

    public function entrust_buy($list, $bond, $fee)

    {

        $map['uid'] = $list['uid'];

        $map['account'] = $list['account'] - $bond - $fee;

        $map['bond'] = $list['bond'] + $bond;

        $map['time'] = time();

        return $this->update($map);

    }


    /**

     * 计算盈亏

     * @param $list array 账户信息

     * @param $price float 收益

     * @return mixed

     */

    public function account_profit($list, $price, $bond)

    {

        $map['uid'] = $list['uid'];

        $map['account'] = $list['account'] + $price +$bond;

        $map['bond'] = $list['bond'] - $bond;

        $map['time'] = time();

        return $this->update($map);

    }


    /**

     * 取消委托

     * @param $list

     * @param $bond

     * @param $fee

     * @return mixed

     */

    public function cancel_entrust($list, $bond, $fee)

    {

        $map['uid'] = $list['uid'];

        $map['account'] = $list['account'] + $bond + $fee;

        $map['bond'] = $list['bond'] - $bond;

        $map['time'] = time();

        return $this->where(array("uid"=>$list["uid"]))->update($map);

    }







    /**

     * 添加自选股票

     * @param $id  int 用户id

     * @param $code string 股票代码

     * @return $this|int

     */

    public function join_optional($id, $code)

    {

        $list = $this->where(array('uid' => $id))->find();

        if ($list) {

            if ($list['concern']) {

                $arr = jd($list['concern']);

                if (!in_array($code, $arr)) {

                    $arr[count($arr)] = $code;

                }

            } else {

                $arr = array($code);

            }

            $map['uid'] = $list['uid'];

            $map['concern'] = je($arr);

            $map['time'] = time();

            $r = $this->update($map);

        } else {

            $r = 0;

        }

        return $r;

    }



    /**

     * 取消自选股票

     * @param $id  int 用户id

     * @param $code string 股票代码

     * @return $this|int

     */

    public function delete_optional($id, $code)

    {

        $value = $this->where(array('uid' => $id))->value('concern');

        $list = jd($value);

        if ($list) {

            $map['uid'] = $id;

            $codes = explode(',', $code);

            foreach ($codes as $key => $value) {

                if (in_array($value, $list)) {

                    $list = array_merge(array_diff($list, array($value)));

                }

            }

            $map['concern'] = je($list);

            $map['time'] = time();

            $r = $this->update($map);

        } else {

            $r = 0;

        }

        return $r;

    }



}