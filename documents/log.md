#日志
##日志的组成
基本的日志信息包含
-   日志记录的时间
-   日志记录的事件
-   日志所记录事件的相关信息
    -   operator 操作人
    -   其他相关信息（Model类使用toArray()方法呈现数组）
    
    
##现有的日志信息
事件名称 | 日志中记录的事件 | 日志级别 
------- | ------------- | --------
用户登录 | User Login | INFO 
用户登出 | User Logout | INFO
代表团席位交换申请 | Seat Exchange Request has been filed | INFO
代表团席位交换完成 | Seat Exchanged has been finished | NOTICE
创建新委员会 | New committee has been created | INFO
删除委员会 | Committee has been deleted | NOTICE
委员会信息改变 | Committee has been changed | INFO
