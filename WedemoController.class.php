<?php
namespace Home\Controller;
use Think\Controller;
use Think\WechatPay;

class WedemoController extends Controller
{
    //微信固定参数
    const APPID         =  'wx23062c6be91741d3';
    const APPSECRET     =  'daf069242a144526f9f6c485e891ea9a';
    const MCH_ID        =  '1290950801';
    const KEY           = '2XOtrLBqNpBCMM0p8zq5ZRHy2uqOx7r1';
    const Notify_url    = 'http://demo.jdhn.top/home/wedemo/notify';
    const TreadeType    = 'JSAPI';
    const PAYURL        = "https://api.mch.weixin.qq.com/pay/unifiedorder";

    public function indexs()
    {
        //header("Content-type:text/html;charset=utf-8");
        $wxPay = new WechatPay();
        //设置支付参数
        $wxPay->SetBody('微信支付工具类！');
        $wxPay->SetAttach('DEMO');
        $wxPay->SetGoods_tag('测试');
        $wxPay->SetOpenid('ociOHwKh6sqkMTv2wGE-InYiZtPo');

        //微信-商户订单号
        $tradeNum = self::MCH_ID.uniqid().mt_rand(1000,9999);
        $wxPay->SetOut_trade_no($tradeNum);

        $wxPay->SetNotify_url(self::Notify_url);
        $wxPay->SetTime_expire(date("YmdHis",time()+600));
        $wxPay->SetTime_start(date("YmdHis"));
        $wxPay->SetTotal_fee(1);
        $wxPay->SetTrade_type(self::TreadeType);

        //设置公众号相关参数
        $wxPay->SetMch_id(self::MCH_ID);//设置商户号
        $wxPay->SetAppid(self::APPID);
        $wxPay->SetSpbill_create_ip($_SERVER['REMOTE_ADDR']);//设置APP和网页支付提交用户端ip
        //随即字符串
        $wxPay->SetNonce_str($wxPay->getNonceStr());

        //设置签名
        $wxPay->SetSign(self::KEY);
        $xml = $wxPay->ToXml();
        $startTimeStamp = WechatPay::getMillisecond();//请求开始时间
        $response = WechatPay::postXmlCurl($xml, self::PAYURL, false, 6);
        $result = WechatPay::Init($response,self::KEY);
        //根据微信返回数据，组装微信客户端所需参数
        $jsapi = new WechatPay();
        $jsapi->SetAppid(self::APPID);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WechatPay::getNonceStr());
        $jsapi->SetPackage("prepay_id=" .$result['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        echo '<pre>';
        var_dump($jsapi->values);
        exit();

    }

    public function index()
    {
        $wxPay = new WechatPay();

        $wxPay->getParam();
    }
}