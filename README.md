# ThinkPHP5.1服务端微信公众号开发

## 功能
- 消息被动回复（用户发送的文本、图片、语言等以同样内容返回）
- 自定义菜单
  - 内部调用第三方API返回内容(部份更新周期较长的内容采用了文件短期缓存)
  - 点击进入页面（天气、城市地铁图）
  - 扫码原内容返回(即二维码或条形码的内容)
  
## 安装
```
#克隆
git clone git@github.com:jeffid/gh_jeffio_test.git gh_jeffio_test
cd gh_jeffio_test
#在.env文件中配置公众号信息
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
### 微信开发配置
* URL如`http://gh.domain.com`,http开头,不能有路径
* URL也可以是`http://gh.domain.com/index.php`,`index.php` 不能改成其它文件名
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
* 时光网
```
#北京热映影片
https://api-m.mtime.cn/Showtime/LocationMovies.api?locationId=290
#返回样式
{
  "bImg": "http://img5.mtime.cn/mg/2018/09/04/124630.14485487.jpg",
  "date": "2019-05-06",
  "hasPromo": false,
  "lid": 290,
  "ms": [
    {
      "NearestCinemaCount": 110,
      "NearestDay": 1557129600,
      "NearestShowtimeCount": 2173,
      "aN1": "小罗伯特·唐尼",
      "aN2": "克里斯·埃文斯",
      "actors": "小罗伯特·唐尼 / 克里斯·埃文斯 / 克里斯·海姆斯沃斯 / 乔什·布洛林",
      "cC": 111,
      "commonSpecial": "超级英雄们共谋大计战灭霸",
      "d": "181",
      "dN": "安东尼·罗素",
      "def": 0,
      "id": 218090,
      "img": "http://img5.mtime.cn/mt/2019/03/29/095608.66203322_1280X720X2.jpg",
      "is3D": true,
      "isDMAX": true,
      "isFilter": false,
      "isHasTrailer": true,
      "isHot": true,
      "isIMAX": false,
      "isIMAX3D": true,
      "isNew": false,
      "isTicket": true,
      "m": "",
      "movieId": 218090,
      "movieType": "动作 / 冒险 / 奇幻",
      "p": [
        "动作冒险奇幻"
      ],
      "preferentialFlag": false,
      "r": 8.4,
      "rc": 0,
      "rd": "20190424",
      "rsC": 0,
      "sC": 4818,
      "t": "复仇者联盟4：终局之战",
      "tCn": "复仇者联盟4：终局之战",
      "tEn": "Avengers: Endgame",
      "ua": -1,
      "versions": [
        {
          "enum": 2,
          "version": "3D"
        },
        {
          "enum": 4,
          "version": "IMAX3D"
        },
        {
          "enum": 6,
          "version": "中国巨幕"
        }
      ],
      "wantedCount": 13260,
      "year": "2019"
    },
    
    ....还有二十多项省略
  ],
  "newActivitiesTime": 0,
  "promo": {
    
  },
  "totalComingMovie": 52,
  "voucherMsg": ""
}
```
* 开眼,有意思的视频,一天一更
```
#api
http://baobab.kaiyanapp.com/api/v4/tabs/selected
#返回样式
{
  "itemList": [
    {
      "type": "video",
      "data": {
        "dataType": "VideoBeanForClient",
        "id": 159240,
        "title": "2045 年的樱花、春天和爱情，美好的不像广告",
        "description": "JACCS 的信用卡品牌广告「2045 年 の SAKURA」由高山沙織饰演女主角（机器人），她曾在东京电玩展上扮演机器人，一度让观众误以为她是真的 AI 机器人而大大引发话题。这次的作品无论是设定还是风格都再次带我们看到日本策划的脑洞有多深。情节致敬欧亨利的「麦琪的礼物」，美好的不像广告～",
        "library": "DAILY",
        "tags": [ ...省略 ],
        "consumption": {
          "collectionCount": 197,
          "shareCount": 101,
          "replyCount": 7
        },
        "resourceType": "video",
        "slogan": "致敬欧亨利的「麦琪的礼物」",
        "provider": {
          "name": "YouTube",
          "alias": "youtube",
          "icon": "http://img.kaiyanapp.com/fa20228bc5b921e837156923a58713f6.png"
        },
        "category": "广告",
        "author": {
          "id": 2162,
          "icon": "http://img.kaiyanapp.com/98beab66d3885a139b54f21e91817c4f.jpeg",
          "name": "开眼广告精选",
          "description": "为广告人的精彩创意点赞",
          "link": "",
          "latestReleaseTime": 1557104400000,
          "videoNum": 1137,
          "adTrack": null,
          "follow": {
            "itemType": "author",
            "itemId": 2162,
            "followed": false
          },
          "shield": {
            "itemType": "author",
            "itemId": 2162,
            "shielded": false
          },
          "approvedNotReadyVideoCount": 0,
          "ifPgc": true,
          "recSort": 0,
          "expert": false
        },
        "cover": {
          "feed": "http://img.kaiyanapp.com/18833ed8d39c28a71ffdc50768d155a0.jpeg?imageMogr2/quality/60/format/jpg",
          "detail": "http://img.kaiyanapp.com/18833ed8d39c28a71ffdc50768d155a0.jpeg?imageMogr2/quality/60/format/jpg",
          "blurred": "http://img.kaiyanapp.com/c52e68dcbbb1ffc2f4a1b14028ada8dc.jpeg?imageMogr2/quality/60/format/jpg",
          "sharing": null,
          "homepage": "http://img.kaiyanapp.com/18833ed8d39c28a71ffdc50768d155a0.jpeg?imageView2/1/w/720/h/560/format/jpg/q/75|watermark/1/image/aHR0cDovL2ltZy5rYWl5YW5hcHAuY29tL2JsYWNrXzMwLnBuZw==/dissolve/100/gravity/Center/dx/0/dy/0|imageslim"
        },
        "playUrl": "http://baobab.kaiyanapp.com/api/v1/playUrl?vid=159240&resourceType=video&editionType=default&source=aliyun&playUrlType=url_oss",
        "thumbPlayUrl": null,
        "duration": 238,
        "webUrl": {
          "raw": "http://www.eyepetizer.net/detail.html?vid=159240",
          "forWeibo": "http://www.eyepetizer.net/detail.html?vid=159240&resourceType=video&utm_campaign=routine&utm_medium=share&utm_source=weibo&uid=0"
        },
        "releaseTime": 1557104400000,
        "playInfo": [ ...省略 ],
        "campaign": null,
        "waterMarks": null,
        "ad": false,
        "adTrack": null,
        "type": "NORMAL",
        "titlePgc": null,
        "descriptionPgc": null,
        "remark": "",
        "ifLimitVideo": false,
        "searchWeight": 0,
        "idx": 0,
        "shareAdTrack": null,
        "favoriteAdTrack": null,
        "webAdTrack": null,
        "date": 1557104400000,
        "promotion": null,
        "label": null,
        "labelList": [
          
        ],
        "descriptionEditor": "JACCS 的信用卡品牌广告「2045 年 の SAKURA」由高山沙織饰演女主角（机器人），她曾在东京电玩展上扮演机器人，一度让观众误以为她是真的 AI 机器人而大大引发话题。这次的作品无论是设定还是风格都再次带我们看到日本策划的脑洞有多深。情节致敬欧亨利的「麦琪的礼物」，美好的不像广告～",
        "collected": false,
        "played": false,
        "subtitles": [
          
        ],
        "lastViewTime": null,
        "playlists": null,
        "src": null
      },
      "tag": "0",
      "id": 0,
      "adIndex": -1
    },
    ...省略多项
  ],
  "count": 14,
  "total": 0,
  "nextPageUrl": "http://baobab.kaiyanapp.com/api/v4/tabs/selected?date=1556931600000&num=2&page=2",
  "adExist": false,
  "date": 1557104400000,
  "nextPublishTime": 1557190800000,
  "dialog": null,
  "topIssue": null,
  "refreshCount": 0,
  "lastStartId": 0
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
