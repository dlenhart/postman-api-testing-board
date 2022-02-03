<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DeleteUserCommand extends Command
{
    protected $signature = 'app:delete-user';

    protected $description = 'Delete an existing user.';

    public function handle()
    {
        $email = $this->ask('Email address of user to delete');

        if ($this->confirm('Do you wish to continue?')) {

            $user = User::where('email', $email)->first();

            if($user){
                $remove = User::find($user->id);
                $remove->delete();

                $this->info('User removed!');
                $this->newLine();
            }
            
            $this->error('User not found!');
            $this->newLine();
        }else{
            $this->info('Cancelled, exiting..');
            $this->newLine();
        }
    }
}