<?php

namespace app\api\controller;

use app\api\service\AllMessageHandler;
use app\api\service\EventMessageHandler;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use GuzzleHttp\Client;
use think\App;
use think\Controller;

class WxGh extends Controller
{
    public static $gh = null;
    public static $server = null;
    public static $user = null;
    public static $msg = [];
    public static $guzzleClient = null;
    
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        
        //    先初始化微信
        self::$gh = app('wechat.official_account');
        
        self::$server = self::$gh->server;
        self::$user = self::$gh->user;
        self::$msg = self::$server->getMessage();
        
        self::$guzzleClient = new Client();
    }
    
    public function index()
    {
        \Log::write(self::$msg);
        self::$server->push(AllMessageHandler::class,Message::ALL);
        self::$server->push(EventMessageHandler::class, Message::EVENT);
        //        $server->push([self::class,'eventHandler'],Message::EVENT);
        
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
