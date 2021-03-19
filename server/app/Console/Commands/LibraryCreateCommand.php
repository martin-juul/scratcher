<?php

namespace App\Console\Commands;

use App\Models\Library;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LibraryCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new library';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('name');
        $path = $this->ask('path');

        $v = validator([
            'name' => $name,
            'path' => $path,
        ], [
            'name' => 'required|string',
            'path' => 'required|string',
        ]);

        $data = $v->validated();

        if (!File::isDirectory($path)) {
            $this->error("{$path} is not a valid directory");
            return 1;
        }

        $library = Library::create($data);

        $this->alert("Created library {$library->name}");

        return 0;
    }
}
