<?php

namespace QingChen\Admin\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Console;

class InstallCommand extends Command
{
    protected $output;

    protected function configure()
    {
        $this->setName('admin:install')
            ->setDescription('Install the admin migrate');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->output = $output;
        $this->publishDatabase();
    }

    protected function publishDatabase()
    {
        $vendorPath = app()->getRootPath().'vendor/';
        $copyPath   = $vendorPath.'qingchen/v-admin-thinkphp51/';

        $this->createFileDir('./database');
        $this->createFileDir('./database/migrations');
        $this->createFileDir('./database/seeds');

        $migratePath = $copyPath.'database/migrations';
        $migrateFile = $this->createFile($migratePath);
        if($migrateFile){
            Console::call('migrate:run');
        }

        $seedPath = $copyPath.'database/seeds';
        $seedFile = $this->createFile($seedPath);
        if($seedFile){
            Console::call('seed:run');
        }

        //判断admin模块是否存在
        $adminModuleName = config('admin.module_name') ? config('admin.module_name') : 'admin';
        $adminModulePath = app()->getAppPath().$adminModuleName;
        if(is_dir($adminModulePath)){
            $this->output->writeln('admin module is already!');
        }else{
            Console::call('build',['--module',$adminModuleName]);

            //创建文件
            if(!file_exists($adminModulePath.'/config/template.php')){
                file_put_contents($adminModulePath.'/config/template.php', '');
                copy($copyPath.'src/Command/stubs/template.stub',$adminModulePath.'/config/template.php');
            }
            if(!file_exists($adminModulePath.'/route.php')){
                file_put_contents($adminModulePath.'/route.php', '');
                copy($copyPath.'src/Command/stubs/route.stub',$adminModulePath.'/route.php');
            }
            if(file_exists($adminModulePath.'/controller/Index.php')){
                copy($copyPath.'src/Command/stubs/IndexController.stub',$adminModulePath.'/controller/Index.php');
            }
        }
    }

    /**
     * 创建文件夹
     *
     * @param $fileDir
     * @return void
     */
    protected function createFileDir($fileDir){
        if(!is_dir($fileDir)){
            mkdir($fileDir,0755,true);
        }
    }

    /**
     * 创建文件
     * basename() 函数返回路径中的文件名部分
     *
     * @param $databasePath
     * @return void
     */
    protected function createFile($databasePath){
        $isCreateFile = false;

        if(is_dir($databasePath)){
            $databaseDir = opendir($databasePath);
            $pathArray   = explode('/',$databasePath);

            if($databaseDir){
                while (($file = readdir($databaseDir)) !== false){
                    if($file !== '.' && $file !== '..'){
                        $pathName = './database/'.$pathArray[count($pathArray) - 1].'/'.$file;

                        if(!file_exists($pathName)){
                            file_put_contents($pathName, '');
                            copy($databasePath.'/'.$file,$pathName);

                            $isCreateFile = true;
                            $this->output->writeln($pathName);
                        }
                    }
                }

                closedir($databaseDir);
            }
        }

        return $isCreateFile;
    }
}