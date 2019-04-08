<?php

namespace Ken\Laravelia\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class Install extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:install
        {--f|force : Install and rollback all tables.}
        {--s|seed : Install and rollback all tables.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravelia installer.';

    protected $models = [
        'Menu.stub' => 'Menu.php',
        'Permission.stub' => 'Permission.php',
        'Role.stub' => 'Role.php',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('');
        $this->info('----------------------------------------------');
        $this->info('=========== WELCOME TO LARAVELIA ============');
        $this->info('----------------------------------------------');
        $this->info('                                              ');
        $this->info(' developer ken<wahyu.dhiraashandy8@gmail.com> ');
        $this->info('                                              ');
        $this->info('----------------------------------------------');
        $this->line('');
        $this->line('Hello..., Please waiting for setup Application');
        $this->line('');
        if($this->option('force')){
            $this->call('migrate:fresh', [
                '--force' => true,
            ]);
            $this->call('db:seed');
        }else if($this->option('seed')){
            $this->call('db:seed');
        }else{
            $this->call('migrate');
            $this->call('db:seed');
        }

        $this->publish();

        $users = config('laravelia.models.users')::all();

        $bar = $this->output->progressStart(count($users));

        foreach ($users as $user) {
            $this->output->progressAdvance();
            sleep(3);
        }

        $this->output->progressFinish();

        $this->line('');
        $this->info('----------------------------------------------');
        $this->info('================= Create User ================');
        $this->info('----------------------------------------------');
        $this->line('');

        $headers = ['Name', 'Email'];
        
        $users = config('laravelia.models.users')::all(['name', 'email'])->toArray();
        
        $this->table($headers, $users);
    }

    protected function exportModels()
    {
        foreach ($this->models as $key => $value) {
            if (file_exists($model = app_path($value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            file_put_contents(
                app_path("{$model}/{$value}"),
                 str_replace(
                    '{{namespace}}',
                    $this->getAppNamespace(),
                    file_get_contents(__DIR__.'/stubs/Models/'.$key)
                )
            );
        }
    }

    protected function publish()
    {
        $this->call('vendor:publish --tag=laravelia');
        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__.'/stubs/routes.stub'),
            FILE_APPEND
        );
        file_put_contents(
            app_path('Http/Controllers/Controller.php'),
            str_replace(
                '{{namespace}}',
                $this->getAppNamespace(),
                file_get_contents(__DIR__.'/stubs/controllers/Controller.stub')
            )
        );
    }
}
