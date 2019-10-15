<?php

namespace stMeteor\code;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Db;

class CreateTable extends Command
{
    protected function configure()
    {
        $this->setName('create-table')
            ->addArgument('prefix', Argument::OPTIONAL, "think_", 'think_')
            ->setDescription('创建验证码需要的数据库"verify_code"');
    }

    protected function execute(Input $input, Output $output)
    {
        $prefix = $input->getArgument('prefix');
        try{
            Db::execute("CREATE TABLE `" . $prefix . "verify_code` (
            `id` int(11) NOT NULL AUTO_INCREMENT,`tel` varchar(11) NOT NULL,`code` int(4) NOT NULL,
            `send_time` varchar(12) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $output->writeln($prefix . 'verify_code表已生成');
        }catch (\Exception $e){
            $output->writeln($e->getMessage() . $e->getLine());
        }
    }
}