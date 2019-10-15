# verify-code
云片网验证码发送与检验

使用前先配置application/command.php文件

return [

    'stMeteor\code\CreateTable'
    
];

然后进入命令行，输入 php think create-table {数据库前缀} 生成所需数据表

如 php think create-table think_ 会生成一个"think_verify_code"表

默认前缀为think_

为了避免同名表存在导致数据丢失，没有先进行表的删除，所以请先检查是否存在需要添加的表