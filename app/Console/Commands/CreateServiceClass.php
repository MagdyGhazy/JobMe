<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends Command
{
    protected $signature = 'make:service {classname}';

    protected $description = 'this command for making service';

    protected $file;

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
        return __DIR__."/../../../stubs/services.stub";
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
        return base_path("App\\Services\\").$this->singleClassName($this->argument('classname'))."Service.php";
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
