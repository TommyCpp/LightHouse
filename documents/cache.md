#Cache的配置与使用
##注意事项
1. 存储在Cache里的Model都遵循懒惰查询原则，其相关联对象并不随其存储在Cache里
2. Cache里面统一使用Collection

##可用的Cache key列表
Cache Key | 含义 | 格式
---- | ---- | ----
committees | 存储委员会信息 | 委员会id=>Committee
delegations | 存储代表团信息 | 代表团id=>Delegation
delegation_seats_count | 统计各个代表团在各个委员会的席位总数 | 代表团id=>\[委员会abbr=>席位数量\]



