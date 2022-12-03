<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Provider\en_US\Person;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a fresh random user in database';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $user = User::query()->create([
            'username' => Person::firstNameMale(),
            'password' => '12345678'
        ]);
        echo ('username: ' . $user->username . '--------');
        echo ('password: 12345678' . '--------');
        echo ('token: ' . $user->createToken('userToken')->plainTextToken);
    }
}
