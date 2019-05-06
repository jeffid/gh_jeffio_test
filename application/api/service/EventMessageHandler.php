<?php

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
//        \Log::write('EventMessageHandler');
        
        switch ($msg['Event']) {
            //注意`CLICK`和`VIEW`是大写
            case 'CLICK':
                switch ($msg['EventKey']) {
                    case 'toutiao':
                        return;
                    
                    case 'kaiyan':
                        $ky = $this->getKaiyan();
//                        \Log::write($ky);
                        $txt = '';
                        $count = 0;
                        foreach ($ky['itemList'] as $item) {
                            if ($item['type'] == 'video' && $count < 5) {
                                $count++;
                                $txt .= <<<txt
{$count}.
 【{$item['data']['title']}】
—— {$item['data']['slogan']}
（{$item['data']['webUrl']['raw']}）

txt;
                            }
                        }
                        $txt .= '——by开眼';
                        return new Text($txt);
                    
                    case 'reying':
                        $ry = $this->getReying();
//                        \Log::write($ry['ms']);
                        
                        $txt = '';
                        foreach ($ry['ms'] as $idx => $item) {
                            //只要前5条
                            if ($idx >= 5) break;
                            
                            $index = $idx + 1;
                            $txt .= <<<txt
{$index}.
片名: 【{$item['tCn']}】
导演: {$item['dN']}
演员: {$item['actors']}
评分: {$item['r']}
看点: {$item['commonSpecial']}

txt;
                        }
                        $txt .= '——by时光网';
//                        \Log::write($txt);
                        
                        return new Text($txt);
                    
                    case 'duanzi':
                        $duanzi = $this->getDuanzi();
                        $idx = random_int(0, 9);
//                        \Log::write($duanzi);
                        return new Text($duanzi['data'][$idx]['content'] ?? '哈哈哈哈~冷笑话');
                    
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
    
    /**
     * 一言
     * @return mixed
     */
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
    
    /**
     * 段子
     * @return mixed
     */
    public function getDuanzi()
    {
        if (!($duanzi = cache('duanzi'))) {
            //缓存没有时就从api获取新内容
            $response = WxGh::$guzzleClient->get(
                'https://www.mxnzp.com/api/jokes/list/random',
                [
                    'query' => [],
                    'timeout' => 4 //设置请求超时时间
                ]);
            
            $duanzi = json_decode((string)$response->getBody(), true);
            cache('duanzi', $duanzi, 120);
        }
        
        return $duanzi;
    }
    
    /**
     * 时光网热映电影
     * @return mixed
     */
    public function getReying()
    {
        if (!($reying = cache('reying'))) {
            //缓存没有时就从api获取新内容
            $response = WxGh::$guzzleClient->get(
                'https://api-m.mtime.cn/Showtime/LocationMovies.api',
                [
                    'query' => [
                        'locationId' => 290
                    ],
                    'timeout' => 4
                ]);
            $reying = json_decode((string)$response->getBody(), true);
            cache('reying', $reying, 43200); //12*3600,缓存有效时间12小时
        }
        
        return $reying;
    }
    
    /**
     * 开眼api数据
     * @return mixed
     */
    public function getKaiyan()
    {
        if (!($kaiyan = cache('kaiyan'))) {
            //缓存没有时就从api获取新内容
            $response = WxGh::$guzzleClient->get(
                'http://baobab.kaiyanapp.com/api/v4/tabs/selected',
                [
                    'query' => [],
                    'timeout' => 4
                ]);
            
            $kaiyan = json_decode((string)$response->getBody(), true);
            cache('kaiyan', $kaiyan, 43200);
        }
        
        return $kaiyan;
    }
    
}