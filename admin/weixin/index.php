<?php
/**
 * User: Xuehai
 * Date: 15-03-11
 */

class WxPost{
    public function index($weixinData){
        $postObj = simplexml_load_string($weixinData, 'SimpleXMLElement', LIBXML_NOCDATA);
		//文本模板
		$textTpl = <<<textTpl
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>   
textTpl;
		$createTime = time();
		$textMessage = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, $createTime, date('Y-m-d H:i:s'));
        file_put_contents('/tmp/zxh.txt', date('r') . '--' . var_export($textMessage, true) . "\n");
        	echo $textMessage;
		return;
	}

	/**
	 *验证消息是否来自微信服务器
	 */
	public function valid()
    {
        //valid signature , option
        if($this->checkSignature()){
	file_put_contents("/tmp/zxh.txt", date('r')."\n", FILE_APPEND);
			if(!empty($_GET['echostr'])){
				echo $_GET['echostr'];exit;
			}
            $this->responseMsg();
        	exit;
        }
        else
		{
			
		}
		
    }
	/**
	 *响应用户的消息
	 */
	private function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
			$this->index($postStr);
        }
    }
	/**
     *校验签名是否一致
	 */
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = "Xueersiweixin";
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr,SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
	file_put_contents("/tmp/zxh.txt", date('r').$tmpStr."\n", FILE_APPEND);
	file_put_contents("/tmp/zxh.txt", date('r').$signature."\n", FILE_APPEND);
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

}

$obj = new WxPost();
$obj->valid();
