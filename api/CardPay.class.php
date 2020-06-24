<?php

/* 
 需求：
1.	支付类可以配置 支付参数。比如：
Class CardPay{
Public string uid;
Public string sid;
Public string key;
.....等等，需要后台配置的参数
//执行支付接口
    public function pay();  

    //退款接口
    public function refund();

    //验证数据
    public function valid();  //验证支付的正确性

    //查询支付订单
    public function query($transaction_id);  //查询订单 验证支付时候要用
}

2.发起支付的小程序页面（如果有需要用户提供是参数）。

 */
//namespace api;

Class CardPay{
protected $uid="170";
protected $sid="158";
protected $key="580ae875a2666e40e9e635bcf7721a12";
protected $linkService="https://sandbox.silkpay.fr";
protected $linkWebPayment = "/silkpay/app/card/webpay";//That method allows the merchant to generate a redirection link in order to redirect the customer to the card payment page. 
protected $linkOrderQuery = "/silkpay/app/card/orderquery";//That method allows the merchant to the updated status for an CardPay transaction. 
protected $linkRefund = "/silkpay/app/card/refund";//That method allows the merchant to generate a refund for specific transaction.
//protected $cid;
//protected $amount;
protected $currency="EUR";
protected $url_ok= "http://localhost:8090/apiPayment/api/url_ok.php";
protected $cancel_url="http://localhost:8090/apiPayment/api/cancel_url.php";
protected $notify_url="http://localhost:8090/apiPayment/api/notify_url.php";
//protected $mail;
//protected $phone;
//protected $ip;
//protected $lastname;
//protected $firstname;
//protected $street;
//protected $postal_code;
//protected $city;
//protected $country;
//protected $transaction_number;
//protected $transactionid;

 //执行支付接口
    public function pay($cid,$amount,$mail,$phone,$ip,$lastname,$firstname,$street,$postal_code,$city,$country){
    //获取参数
    $data=$this->getParameter(); 
    $data['cid']=$cid;
    $data['amount']=$amount;
    $data['currency']=$this->currency;
    $data['url_ok']=$this->url_ok;
    $data['cancel_url']=$this->cancel_url;
    $data['notify_url']=$this->notify_url;
    $data['mail']=$mail;
    $data['phone']=$phone;
    $data['ip']=$ip;
    $data['firstname']=$firstname;   
    $data['lastname']=$lastname; 
    $data['street']=$street; 
    $data['postal_code']=$postal_code;
    $data['city']=$city;    
    $data['country']=$country; 
    $url = $this->linkService . $this->linkWebPayment;
    $data["signature"] = $this->generateSignature($data);
    //前往付款接口
     $pay=$this->executeCall($url, $data);
     $this->redirect($pay->redirect_url);
     //$redirect_url=$pay->redirect_url;
     //header("Location: $redirect_url");
     return $pay;  
    }
    
//验证数据，验证支付的正确性
    public function query($cid,$transaction_number){
    //获取参数
     $data=$this->getParameter();
     $data['cid']=$cid;
     $data['transaction_number']=$transaction_number;
     $url = $this->linkService . $this->linkOrderQuery;
     $data["signature"] = $this->generateSignature($data);
     //调用验证接口
     return $this->executeCall($url, $data);
    }  
       
 //退款接口
    public function refund($cid,$transactionid,$amount){
    //获取参数
     $data=$this->getParameter(); 
     $data['cid']=$cid;
     $data['transactionid']=$transactionid;
     $data['amount']=$amount;
     $url = $this->linkService . $this->linkRefund;
     $data["signature"] = $this->generateSignature($data);
     //调用退款接口
     return $this->executeCall($url, $data);
    }

 
 
/***************************工具函数***************************************************/
//构造函数
    public function __construct()
	{
	
	}

//工产模式获得对象
    public static function getInstance()
	{
	//获得类名
	$modelClassName = get_called_class();
	//新建对象
	return new $modelClassName();
	}
 
//设置uid
    public function setUid($uid){
     $this ->uid = $uid;
     }
//获取uid   
     public function getUid (){
     return $this->uid   ;
    } 
//设置sid
      public function setSid($sid){
     $this ->sid = $sid;
     }
 //获取sid      
     public function getSid (){
     return $this->sid  ;
    } 
//设置key
      public function setKey($key){
     $this ->key = $key;
     }
 //获取key       
     public function getKey (){
     return $this->key;
    } 

//获取signature
       function generateSignature($data)
    {
        return sha1($data["sid"]."|".$data["cid"]."|".$data["timestamp"]."|".$data["key"]);
    }
    
//获取错误信息编号        
    function checkResponseSignature($data)
    {
        return sha1($data["error_code"]."|".$data["cid"]."|".$data["timestamp"]."|".$data["key"]);
    }

    
//传入数据调用接口
    public function executeCall($url, array $data = NULL, array $options = array())
    {
        $ch = curl_init($url);
        $opts = array(
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_HEADER          => 0,
            CURLOPT_FOLLOWLOCATION  => 1,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $data
        ); 

        curl_setopt_array($ch, $opts); 
        $result = curl_exec($ch);

        error_log(curl_errno($ch)." - ".curl_error($ch));
        $decoded_response = json_decode($result);

        if (!$decoded_response) {
            error_log($result);
            return strip_tags($result);
        }

        curl_close($ch); 
        return $decoded_response; 
    }
    
    
    //根据用户传参数获取一个$data数组的数据
    public function getParameter(){
    //webPayment相关数据的获取
    $data['uid']=$this->uid;
    $data['sid']=$this->sid;
    $data['key']=$this->key;
    $data['timestamp']=time(); 
    return $data;
    }
   
  //重定向函数
   public function redirect($url)
{
    header("Location: $url");
    exit();
    }
}





//获取数据
    if(!empty($_POST['cid'])) $cid=$_POST['cid'];
    if(!empty($_POST['amount'])) $amount=$_POST['amount'];
    if(!empty($_POST['currency'])) $currency=$_POST['currency'];    
    if(!empty($_POST['url_ok'])) $url_ok=$_POST['url_ok'];
    if(!empty($_POST['cancel_url'])) $cancel_url=$_POST['cancel_url'];
    if(!empty($_POST['notify_url'])) $notify_url=$_POST['notify_url'];
    if(!empty($_POST['mail'])) $mail=$_POST['mail'];
    if(!empty($_POST['phone'])) $phone=$_POST['phone'];
    if(!empty($_POST['ip'])) $ip=$_POST['ip'];
    if(!empty($_POST['firstname'])) $firstname=$_POST['firstname'];   
    if(!empty($_POST['lastname'])) $lastname=$_POST['lastname']; 
    if(!empty($_POST['street'])) $street=$_POST['street']; 
    if(!empty($_POST['postal_code'])) $postal_code=$_POST['postal_code'];
    if(!empty($_POST['city'])) $city=$_POST['city'];    
    if(!empty($_POST['country'])) $country=$_POST['country']; 
    //orderQuery相关数据的获取 
    if(!empty($_POST['transaction_number'])) $transaction_number=$_POST['transaction_number']; 
    //refund相关数据的获取
     if(!empty($_POST['transactionid'])) $transactionid=$_POST['transactionid'];    
     if(!empty($_POST['amount'])) $amount=$_POST['amount'];  
     
     
//测试pay接口
/*结果
 * object(stdClass)#2 (10) { ["cid"]=> string(2) "16" 
 * ["error_code"]=> string(5) "00000" 
 * ["timestamp"]=> int(1592919367) 
 * ["redirect_url"]=> string(89) "https://sandbox.silkpay.fr/silkpay/app/card/redirect?ref=rPeICwsjFMiQOnlY3BuoXU8k2RgLdEG7" 
 * ["Reason"]=> string(22) "Transaction en attente" 
 * ["Code"]=> int(120) 
 * ["Transaction"]=> string(24) "202006231336061852509832" 
 * ["Response"]=> string(8) "REDIRECT"
 * ["redirect_type"]=> string(3) "URL" 
 * ["signature"]=> string(40) "a7a9e837f810f41cfdbbdb6d7fe9cecf7e01cac0" } 
 */



$pay = CardPay::getInstance()->pay($cid,$amount,$mail,$phone,$ip,$lastname,$firstname,$street,$postal_code,$city,$country,$currency);
var_dump($pay->redirect_url);
//echo ("<!DOCTYPE html><html><head><title>refund test</title><meta charset= 'UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'></head><body>");
//echo $pay;
//echo ("</body></html>");

//测试query接口
/*结果
 * object(stdClass)#2 (9) 
 * { ["error_code"]=> string(5) "00000" 
 * ["response_code"]=> string(2) "OK" 
 * ["timestamp"]=> int(1593003119) 
 * ["message"]=> string(23) "The transaction is paid" 
 * ["signature"]=> string(40) "bdfab019ba6e0b9c95a12105e7a3581960e66545" 
 * ["transactionid"]=> string(24) "202006241233142135558481" 
 * ["paid_time"]=> string(19) "2020-06-24 14:34:00" 
 * ["cid"]=> string(2) "30" 
 * ["status"]=> string(4) "PAID" } 
 */ 
//$query = CardPay::getInstance()->query($cid,$transaction_number);
//var_dump($query);


//测试refund接口
//return 10083 Transaction not found
/*结果
 * object(stdClass)#2 (6) 
 * { ["error_code"]=> string(5) "00000" 
 * ["response_code"]=> string(2) "OK" 
 * ["message"]=> string(15) "request success" 
 * ["timestamp"]=> int(1593004659) 
 * ["signature"]=> string(40) "1b7924e0fc16bdc4ef4f448ec716f90c3c383da8" 
 * ["cid"]=> string(2) "31" } 
 */
 //$refund = CardPay::getInstance()->refund($cid,$transactionid,$amount);
 //var_dump($refund);



