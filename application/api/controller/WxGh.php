<?php

namespace app\api\controller;

use app\api\service\EventMessageHandler;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use think\App;
use think\Controller;

class WxGh extends Controller
{
    public static $gh = null;
    public static $server = null;
    public static $user = null;
    
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        
        //    先初始化微信
        self::$gh = app('wechat.official_account');
        
        self::$server = self::$gh->server;
        self::$user = self::$gh->user;
    }
    
    public function index()
    {
        self::$server->push(function ($msg) {
//            \Log::write($msg);
//            dump($msg);

//            return "Hi, 欢迎关注 Jeffio!";
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
        });
        
        //        $server->push([self::class,'eventHandler'],Message::EVENT);
        self::$server->push(EventMessageHandler::class, Message::EVENT);
        
        self::$server->serve()->send();
    }
    
}
