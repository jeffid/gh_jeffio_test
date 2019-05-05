<?php

namespace app\api\controller;

use think\Controller;

class WxGh extends Controller
{
    public function index()
    {
        //    先初始化微信
        $app = app('wechat.official_account');
/*        $app->server->push(function ($message) {
            return 'hello,world';
        });
        $app->server->serve()->send();*/
    
        $server = $app->server;
        $user = $app->user;
    
        $server->push(function($message) use ($user) {
            $fromUser = $user->get($message['FromUserName']);
            return "Hi, {$fromUser->nickname}! 欢迎关注 Jeffio!";
            
//            return "Hi, 欢迎关注 Jeffio!";
        });
    
        $server->serve()->send();
    }
    
}
