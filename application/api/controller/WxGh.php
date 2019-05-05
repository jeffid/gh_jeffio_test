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
    public static $msg = [];
    
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        
        //    先初始化微信
        self::$gh = app('wechat.official_account');
        
        self::$server = self::$gh->server;
        self::$user = self::$gh->user;
        self::$msg = self::$server->getMessage();
    }
    
    public function index()
    {
        self::$server->push(function ($msg) {
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
        });
        
        //        $server->push([self::class,'eventHandler'],Message::EVENT);
        self::$server->push(EventMessageHandler::class, Message::EVENT);
        
        self::$server->serve()->send();
    }
    
    /**
     * 创建菜单文档(https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013)
     */
    public function setMenu()
    {
        $buttons = [
            [
                'name' => '消遣',
                'sub_button' => [
                    [
                        'type' => 'click',
                        'name' => '头条',
                        'key' => 'toutiao'
                    ],
                    [
                        'type' => 'click',
                        'name' => '热映',
                        'key' => 'reying'
                    ],
                    [
                        'type' => 'click',
                        'name' => '段子',
                        'key' => 'duanzi'
                    ],
                    [
                        'type' => 'click',
                        'name' => '言论',
                        'key' => 'yanlun'
                    ],
                ],
            ],
            [
                'name' => '实用',
                'sub_button' => [
                    [
                        'type' => 'view',
                        'name' => '天气',
                        'url' => 'http://wx.weather.com.cn/mweather/101281601.shtml#1'
                    ],
                    [
                        'type' => 'view',
                        'name' => '地铁图',
                        'url' => 'http://zuoche.com/touch/metromap.jspx?cityname=%E4%B8%9C%E8%8E%9E'
                    ],
                    [
                        'type' => 'scancode_waitmsg',
                        'name' => '瞄一瞄',
                        'key' => 'scancode'
                    ],
                ],
            ],
        ];
        
        return json((array)self::$gh->menu->create($buttons));
    }
    
    public function getMenu()
    {
        return json((array)self::$gh->menu->current());
    }
}
