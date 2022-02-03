<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\UniqueSeeder;

class CreateUserCommand extends Command
{
    protected $signature = 'app:create-user';

    protected $description = 'Add a new user via command line.';

    public function handle(UniqueSeeder $seeder)
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Type your password');

        $seeder->run($name, $email, $password);
    }
}