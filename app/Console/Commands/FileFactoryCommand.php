<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

abstract class FileFactoryCommand extends Command
{

    protected $file;

    abstract function setStubName():string;
    abstract function setStubPath():string;
    abstract function setSuffix():string;
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    public function singleClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }
    public function makeDir($path)
    {
        $this->file->makeDirectory($path,0777,true,true);
        return $path;
    }

    public function stubPath()
    {
        $name = $this->setStubName();
        return __DIR__."/../../../stubs/{$name}.stub";
    }
    public function stubVariables()
    {
        return [
            'NAME' => $this->singleClassName($this->argument('classname')),
        ];
    }
    public function stubContent($stubPath,$stubVariables)
    {
        $content = file_get_contents($stubPath);
        foreach ($stubVariables as $key => $replace)
        {
            $newContent = str_replace('$'.$key ,$replace,$content);
        }
        return $newContent;
    }
    protected function makePath()
    {
        $path = $this->setStubPath();
        $suffix = $this->setSuffix();
        return base_path($path).$this->singleClassName($this->argument('classname'))."{$suffix}.php";
    }

    public function handle()
    {
        $path = $this->makePath();
        $this->makeDir(dirname($path));
        if ($this->file->exists($path))
        {
            return $this->info('this file is already exist');
        }
        $stubPath =$this->stubPath();
        $stubVariables =$this->stubVariables();
        $content = $this->stubContent($stubPath,$stubVariables);
        $this->file->put($path,$content);
        $this->info('this file is created successfully');

    }

}
