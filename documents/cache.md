#Cache的配置与使用
##注意事项
1. 存储在Cache里的Model都遵循懒惰查询原则，其相关联对象并不随其存储在Cache里
2. Cache里面统一使用Collection

##可用的Cache key列表
<table>
    <tr>
        <td>committees</td>
        <td>存储委员会信息</td>
        <td>委员会编号=>Committee Model</td>
     </tr>
     <br/>
     <tr>
        <td>delegations</td>
        <td>存储代表团信息</td>
        <td>代表团id=>Delegation Model</td>
    </tr>
    <tr>
        <td>delegation_seats_count</td>
        <td>统计各个代表团在各个委员会的席位总数</td>
        <td>代表团id=>[委员会id=>席位数量]</td>
</table>
