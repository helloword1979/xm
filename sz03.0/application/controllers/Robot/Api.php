<?php/** * Created by PhpStorm. * User: root * Date: 2018/11/2 * Time: 13:37 */class Robot_ApiController extends Robot_SafeController{    public function init()    {        $this->Robot_ApiLogic = new Robot_ApiLogicController();    }    //委托下单    public function orderAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','amount','currency','method','price','tradeType','sign','reqTime'];        $optional = ['acctType'];        $valid_res = self::valid_sign($input,$mandatory,$optional);        if(!$valid_res['code']) self::ax($valid_res);        $type = $input['tradeType'] == 0?'out':'in';        $currencys = explode('_',$input['currency']);        $param = ['price'=>$input['price'],'number'=>$input['amount'],'type'=>$type,'coin_from'=>strtolower($currencys[0]), 'coin_to'=>strtolower($currencys[1])];        $user = UserModel::getInstance()->where(['uid'=>$valid_res['uid']])->fRow();        $res = $this->Robot_ApiLogic->setTrust($param,$user);        if($res['code']) self::ax($res);    }    //取消委托    public function cancelOrderAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','id','method','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $currencys = explode('_',$input['currency']);        $param = ['id'=>$input['id'],'coin_from'=>strtolower($currencys[0]),'coin_to'=>strtolower($currencys[1])];        $user = UserModel::getInstance()->where(['uid'=>$valid_res['uid']])->fRow();        $res = $this->Robot_ApiLogic->trustcancel($param,$user);        if($res['code']) self::ax($res);    }    // 获取委托买单或卖单    public function getOrderAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','id','method','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $param = ['id'=>$input['id'],'coin_from'=>$input['currency'],'coin_to'=>'cnyx'];        $res = $this->Robot_ApiLogic->getOrder($param);        if($res['code']) echo self::ax($res['data']);    }    //获取多个委托买单或卖单，每次请求返回10条记录    public function getOrdersAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','method','pageIndex','tradeType','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $param = ['coin_from'=>$input['currency'],'coin_to'=>'cnyx','tradeType'=>$input['tradeType'],'pageIndex'=>$input['pageIndex']];        $res = $this->Robot_ApiLogic->getOrders($param);        if($res['code']) self::ax($res);    }    //(新)获取多个委托买单或卖单，每次请求返回pageSize<100条记录    public function getOrdersNewAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','method','pageIndex','pageSize','tradeType','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $res = $this->Robot_ApiLogic->getOrdersNew($input);        if($res['code']) echo json($res['data']);        else echo json($res);    }    //与getOrdersNew的区别是取消tradeType字段过滤，可同时获取买单和卖单，每次请求返回pageSize10条记录    public function getOrdersIgnoreTradeType(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','method','pageIndex','pageSize','sign','reqTime'];        $valid_res = SafeController::valid_sign($input,$mandatory);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::getOrdersIgnoreTradeType($input);        if($res['code']) echo json($res['data']);        else echo json($res);    }    //获取未成交或部份成交的买单和卖单，每次请求返回pageSize<=10条记录    public function getUnfinishedOrdersIgnoreTradeTypeAction(){        $input = $_REQUEST;        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','currency','method','pageIndex','pageSize','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $res = $this->Robot_ApiLogic->getUnfinishedOrdersIgnoreTradeType($input,$valid_res['uid']);        if(!isset($res['code']) || !$res['code']) echo self::ax($res);        self::ax($res['data']);    }    //获取用户信息    public function getAccountInfoAction(){        $input = $_REQUEST;//        待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','sign','reqTime'];        $valid_res = self::valid_sign($input,$mandatory);        if(!$valid_res['code']) self::ax($valid_res);        $res = $this->Robot_ApiLogic->getAccountInfo($valid_res['uid']);        self::ax($res['data']);    }    //获取用户充值地址    public function getUserAddress(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','currency','sign','reqTime'];        $valid_res = SafeController::valid_sign($input,$mandatory);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::getUserAddress($input);        echo json($res);    }    //获取用户认证的提现地址    public function getWithdrawAddress(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','currency','sign','reqTime'];        $valid_res = SafeController::valid_sign($input,$mandatory);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::getWithdrawAddress($input);        echo json($res);    }    // 获取数字资产提现记录    public function getWithdrawRecord(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','currency','pageIndex','pageSize','sign','reqTime'];        $valid_res = SafeController::valid_sign($input,$mandatory);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::getWithdrawRecord($input);        echo json($res);    }    //获取数字资产充值记录    public function getChargeRecord(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','currency','pageIndex','pageSize','sign','reqTime'];        $valid_res = SafeController::valid_sign($input,$mandatory);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::getChargeRecord($input);        echo json($res);    }    //提现    public function withdraw(){        $input = I('');        //待验证参数 $mandatory必选 $optional可选        $mandatory = ['accesskey','method','amount','currency','fees','itransfer','receiveAddr','safePwd','sign','reqTime'];        $optional = ['wcg_key'];        $valid_res = SafeController::valid_sign($input,$mandatory,$optional);        if(!$valid_res['code']){            echo json($valid_res);die;        }        $res = ApiLogicController::withdraw($input);        if($res['code']){            $id = M('myzc')->where(['userid'=>userid(),'coinname'=>$input['currency']])->order('id desc')->getField('id');            echo json(['1000'=>1000,'message'=>'success','id'=>$id]);        } else{            echo json($res);        }    }}