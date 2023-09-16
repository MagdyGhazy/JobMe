<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends FileFactoryCommand
{
    protected $signature = 'make:service {classname}';

    protected $description = 'this command for making service';

    public function setStubName():string
    {
        return "services";
    }
    public function setStubPath():string
    {
        return "App\\Services\\";
    }
    public function setSuffix():string
    {
        return "Service";
    }
}
