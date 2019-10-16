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

使用方法：

use stMeteor\code\VerifyCode;

$code = new VerifyCode();

//获取验证码

    $code->getCode($tel);

//检验验证码

    $code->checkCode($tel, $code);

可通过 $code->setConfig($name, $value); 来设置配置

目前可选配置有：

length  验证码长度

text    验证码内容 例：'【签名】您的验证码是#code#。如非本人操作，请忽略本短信' 其中的#code#文本会自动替换为随机验证码

apikey  云片网apikey

api     返回结果是否直接返回前端，如为 true 则直接中断程序返回一个json数据到前端