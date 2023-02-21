<?php

namespace QingChen\Admin\Command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Console;

class ControllerCommand extends Command
{
    protected $output;

    protected function configure()
    {
        $this->setName('admin:controller')
            ->addArgument('name', Argument::OPTIONAL, "controller name")
            ->setDescription('create the admin controller');
    }

    protected function execute(Input $input, Output $output)
    {
        $name = trim($input->getArgument('name'));

        $this->output = $output;
        $this->publishDatabase($name);
    }

    protected function publishDatabase($name)
    {
        if($name){
            if(stripos($name,'.php') === false){
                $name = ucfirst($name.'.php');
            }
            $nameArr = explode('.',$name);

            $appPath   = app()->getAppPath();
            $adminDir  = config('admin.directory');
            $adminPath = $appPath.$adminDir;

            if(!is_dir($adminPath)){
                mkdir($adminPath,0755,true);
            }
            if(!is_dir($adminPath.'/controller')){
                mkdir($adminPath.'/controller',0755,true);
            }
            if(!file_exists($adminPath.'/controller/'.$name)){
                Console::call('make:controller',[$adminDir.'/'.$nameArr[0]]);
                $this->output->writeln($adminPath.'/controller/'.$name);
            }
        }
    }
}