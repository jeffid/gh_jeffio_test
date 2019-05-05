# Jeffio订阅号测试版

> ThinkPHP 5.1（LTS版本）
> ThinkPHP5的运行环境要求PHP5.6以上。

## 安装
```
#克隆
git clone git@github.com:jeffid/gh_jeffio_test.git gh_jeffio_test
cd gh_jeffio_test
cp .env.example .env
chmod -R 777 runtime/
composer install

```

nginx配置
```
location / {
  if (!-e $request_filename) {
    rewrite  ^(.*)$  /index.php?s=/$1  last;
  }
}
```


## 公号开发注意
### 配置
* URL如`http://jeffio-test.gh.waacoo.cc`,http开头,不能有路径
* URL也可以是`http://jeffio-test.gh.waacoo.cc/index.php`,`index.php` 不能改成其它文件名
* TOKEN3至32位字符

## 使用到的第三方API或服务
### 第三方API
* 一言(https://hitokoto.cn/api),获取随机言论
```
#接口
https://v1.hitokoto.cn/
#返回样式
{
  "id": 4125,
  "hitokoto": "追寻只是因为无法抓获，想要触碰，只是因为知道自己触碰不到。",
  "type": "a",
  "from": "FLCL Progressive",
  "creator": "eaea",
  "created_at": "1542436556"
}
```
* github好心人(https://github.com/MZCretin/RollToolsApi),段子来源
```
#随机段子api
https://www.mxnzp.com/api/jokes/list/random
#返回样式
{
  "code": 1,
  "msg": "数据返回成功",
  "data": [
    {
      "content": "妈妈下班回家，走到小军的屋子里，先是惊讶，后是欢呼，说道：“儿子，你太棒了！这玻璃妈妈擦了十几年都擦不干净，你是用肥皂泡吗？”小军说：“不，这是我用锤子锤的”。妈妈：“滚出去！”",
      "updateTime": "2017-11-29 00:52:59"
    },
    {
      "content": "　　老师：是什么让一两个小时的电影变成了三四个小时？(答案：广告)　　小明：是网速！　　老师：滚出去！",
      "updateTime": "2018-08-06 08:04:22"
    },
    
  ...每次10条,这里就不列出了
  ]
}
```

### 页面服务
* 坐车网(http://zuoche.com/touch/metromap.jspx?cityname=%E4%B8%9C%E8%8E%9E)
* 中国天气网微信版(http://wx.weather.com.cn/mweather/101281601.shtml#1)


## 核心composer依赖
* [naixiaoxin/think-wechat](https://www.easywechat.com/docs/4.0/official-account/tutorial)
[文档](https://www.easywechat.com/docs/4.0/official-account/tutorial)



## 框架
ThinkPHP5.1
+ [完全开发手册](https://www.kancloud.cn/manual/thinkphp5_1/content)
+ [升级指导](https://www.kancloud.cn/manual/thinkphp5_1/354155) 
> 可以使用php自带webserver快速测试
> 切换到根目录后，启动命令：php think run
