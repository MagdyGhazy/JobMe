<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateInterfaceClass extends FileFactoryCommand
{
    protected $signature = 'make:interface {classname}';

    protected $description = 'this command for making interface';

    public function setStubName():string
    {
        return "interface";
    }
    public function setStubPath():string
    {
        return "App\\Interfaces\\";
    }
    public function setSuffix():string
    {
        return "Interface";
    }
}
