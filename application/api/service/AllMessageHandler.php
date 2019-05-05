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

class AllMessageHandler implements EventHandlerInterface
{
    
    public function handle($msg = [])
    {
        //            \Log::write($msg);
//            dump($msg);

//            return 'Hi, 欢迎关注 Jeffio!';
        switch ($msg['MsgType']) {
//                case 'event':
//                    return '收到事件消息';
//                    break;
            case 'text':
                return new Text($msg['Content']);
                break;
            
            case 'image':
                return new Image($msg['MediaId']);
                break;
            
            case 'voice':
                return new Voice($msg['MediaId']);
                break;
            
            case 'video':
                return new Video($msg['MediaId'], [
                    'title' => '',
                    'description' => '...',
                ]);
                break;
            
            case 'location':
                $location = <<<default
纬度: $msg->Location_X
经度: $msg->Location_Y
位置信息: $msg->Label
地图缩放大小: $msg->Scale
default;
                \Log::write($location);
                
                return new Text($location);
                break;
            
            case 'link':
                $link = <<<default
消息标题: $msg->Title
消息描述: $msg->Description
消息链接: $msg->Url
default;
                return new Text($link);
                break;
            
            case 'file':
                return '文件消息已收到~';
            
            // ... 其它消息
            default:
                return '消息已收到~';
                break;
        }
    }
    
}