<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class alipay 
{    
    Public $uid="170";
Public $sid="158";
Public $key="580ae875a2666e40e9e635bcf7721a12";
    var $linkService                = "https://sandbox.silkpay.fr";
    var $linkGenerate               = "/silkpay/app/alipayonline";
    var $linkVerification           = "/silkpay/app/alipayonline/verification";
    var $linkScanOffline            = "/silkpay/app/alipay/scanclientweb";
    var $linkVerificationOffline    = "/silkpay/app/transactionsweb";
    var $linkCreateCodeOffline      = "/silkpay/app/alipay/createcodedynamicweb";
    var $linkCancelTransaction      = "/silkpay/app/alipay/cancelweb";
    var $linkRefundTransaction      = "/silkpay/app/alipay/refundweb";
    
    function generateSignature($data)
    {
        return sha1($data["sid"]."|".$data["cid"]."|".$data["timestamp"]."|".$data["key_id"]);
    }
            
    function checkResponseSignature($data)
    {
        return sha1($data["error_code"]."|".$data["cid"]."|".$data["timestamp"]."|".$data["key_id"]);
    }
            
    function generateQrCode($data)
    {
        $url = $this->linkService . $this->linkGenerate;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }
    
    function paymentVerification($data)
    {
        $url = $this->linkService . $this->linkVerification;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }
    
    function scanClientOffline($data)
    {
        $url = $this->linkService . $this->linkScanOffline;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }
    
    function verificationTransactionOffline($data)
    {
        $url = $this->linkService . $this->linkVerificationOffline;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }
    
    function createCodeOffline($data)
    {
        $url = $this->linkService . $this->linkCreateCodeOffline;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }
    
    function cancelOffline($data)
    {
        $url = $this->linkService . $this->linkCancelTransaction;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }

    function refundOffline($data)
    {
        $url = $this->linkService . $this->linkRefundTransaction;
        $data["signature"] = $this->generateSignature($data);
        return $this->executeCall($url, $data);
    }  
    
    function executeCall($url, array $data = NULL, array $options = array())
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

        error_log(curl_errno($ch) . " - " . curl_error($ch));
        $decoded_response = json_decode($result);

        if (!$decoded_response) {
            error_log($result);
            return strip_tags($result);
        }

        curl_close($ch); 
        return $decoded_response; 
    }
}
 