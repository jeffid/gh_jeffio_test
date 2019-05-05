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
            
            case 'click':
                return 'click';
            
            case 'view':
                return 'view';
            
            case 'scancode_push':
                return 'scancode_push';
            
            case 'scancode_waitmsg':
                \Log::write($msg);
                return new Text(explode('qrscene_',$msg['EventKey'])[1]);
//                return 'scancode_waitmsg';
            
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
                (array) $fromUser = WxGh::$user->get($msg['FromUserName']); //返回的是本来就是数组(文档错了), 调用接口以openid获取昵称
    
                return new Text("Hi, {$fromUser['nickname']}! 你真有眼光~");
            
            default:
                return 'event type no match~';
        }
    }

}