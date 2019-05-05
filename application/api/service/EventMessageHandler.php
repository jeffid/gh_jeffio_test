<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2019/5/5
 * Time: 17:48
 */

namespace app\api\service;

use app\api\controller\WxGh;
use \EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Text;

class EventMessageHandler implements EventHandlerInterface
{
    
    /**
     * 事件类消息的处理
     * 可能的事件类型文档(https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141016)
     *
     * @param array $msg
     * @return string
     */
    public function handle($msg = [])
    {
        \Log::write('EventMessageHandler');
        
        switch ($msg['Event']) {
            //注意`CLICK`和`VIEW`是大写
            case 'CLICK':
                switch ($msg['EventKey']) {
                    case 'toutiao':
                        return;
                    
                    case 'reying':
                        return;
                    
                    case 'duanzi':
                        if (!($duanzi = cache('duanzi'))) {
                            //缓存没有时就从api获取新内容
                            $duanzi = $this->getDuanzi();
                            cache('duanzi', $duanzi, 120);
                        }
                        $idx = random_int(0, 9);
//                        \Log::write($duanzi);
                        return new Text($duanzi['data'][$idx]['content']);
                    
                    case 'yanlun':
                        $yanlun = $this->getYanlun();
                        $txt = <<<txt
$yanlun->hitokoto
—— $yanlun->from
txt;
                        return new Text($txt);
                }
            /* 请求参数格式
             <xml><ToUserName><![CDATA[gh_f48a9fbe23d2]]></ToUserName>
<FromUserName><![CDATA[oRrb85w1Bili8CU19J6HZ0gonoLA]]></FromUserName>
<CreateTime>1557066230</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[yanlun]]></EventKey>
</xml>
             * */
//                return 'click';
            
            case 'VIEW':
                return 'view';
            
            case 'scancode_push':
                return 'scancode_push';
            
            case 'scancode_waitmsg':
//                \Log::write($msg);
                return new Text($msg['ScanCodeInfo']['ScanResult']);
            /* 原数据
             <xml><ToUserName><![CDATA[gh_f48a9fbe23d2]]></ToUserName>
    <FromUserName><![CDATA[oRrb85w1Bili8CU19J6HZ0gonoLA]]></FromUserName>
    <CreateTime>1557066324</CreateTime>
    <MsgType><![CDATA[event]]></MsgType>
    <Event><![CDATA[scancode_waitmsg]]></Event>
    <EventKey><![CDATA[scancode]]></EventKey>
    <ScanCodeInfo><ScanType><![CDATA[qrcode]]></ScanType>
    <ScanResult><![CDATA[http://weibo.cn/qr/userinfo?uid=1665356464]]></ScanResult></ScanCodeInfo></xml>
             * */
            
            case 'pic_sysphoto':
                return 'pic_sysphoto';
            
            case 'pic_photo_or_album':
                return 'pic_photo_or_album';
            
            case 'pic_weixin':
                return 'pic_weixin';
            
            case 'location_select':
                return 'location_select';
            
            case 'view_miniprogram':
                return 'view_miniprogram';
            
            case 'subscribe':
                (array)$fromUser = WxGh::$user->get($msg['FromUserName']); //返回的是本来就是数组(文档错了), 调用接口以openid获取昵称
                
                return new Text("Hi, {$fromUser['nickname']}! 你真有眼光~");
            
            default:
                return 'event type no match~';
        }
    }
    
    public function getYanlun()
    {
        $response = WxGh::$guzzleClient->get(
            'https://v1.hitokoto.cn/',
            [
                'query' => [],
                'timeout' => 3.14 //设置请求超时时间
            ]);
        
        return json_decode((string)$response->getBody());
    }
    
    public function getDuanzi()
    {
        $response = WxGh::$guzzleClient->get(
            'https://www.mxnzp.com/api/jokes/list/random',
            [
                'query' => [],
                'timeout' => 3.14 //设置请求超时时间
            ]);
        
        return json_decode((string)$response->getBody(), true);
    }
    
    /*
     $client = new GuzzleHttp\Client();
//普通表单`application/x-www-form-urlencoded`的POST请求
$response = $client->post('http://httpbin.org/post', [
    'form_params' => [        //参数组
        'a' => 'aaa',
        'b' => 'bbb',
        'nested_field' => [		//参数允许嵌套多层
            'A' => 'AAA',
            'B' => 'BBB',
        ]
    ],
]);
      
        $body = $response->getBody(); //获取响应体，对象
        $bodyStr = (string)$body; //对象转字串
        echo $bodyStr;

     * */
    
}