<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Name');
        $email = Str::lower($this->ask('Email'));
        $password = $this->secret('Password');
        $role = $this->askWithCompletion('Role', ['admin', 'user']);

        $v = validator([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => $role,
        ], [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:3|max:260',
            'role'     => 'required|string',
        ]);

        $data = $v->validated();

        $user = User::create($data);

        $this->alert("Created user {$user->name}");

        return 0;
    }
}
