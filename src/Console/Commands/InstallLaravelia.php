<?php

namespace Ken\Laravelia\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class InstallLaravelia extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravelia application installer.';

    protected $models = [
        'User' => 'users',
        'Menu' => 'menu',
        'Role' => 'roles',
        'Permission' => 'permissions',
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
        $this->comment("\n");
        $this->info('----------------------------------------------');
        $this->info('=========== WELCOME TO LARAVELIA =============');
        $this->info('----------------------------------------------');
        $this->comment("\n");
        $this->info(' developer ken<wahyu.dhiraashandy8@gmail.com> ');
        $this->comment("\n");
        $this->info('----------------------------------------------');
        $this->comment("\n");

        $this->publish();

        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', [
            '--class=LaraveliaTableSeeder'
        ]);

        $users = config('laravelia.models.users')::all();

        $bar = $this->output->createProgressBar(count($users));

        $bar->start();

        foreach ($users as $user) {
            $bar->advance();
        }

        $bar->finish();

        $this->comment("\n");

        $headers = ['NAME', 'EMAIL'];
        
        $users = config('laravelia.models.users')::all(['name', 'email'])->toArray();
        
        $this->table($headers, $users);

        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__.'/stubs/routes.stub'),
            FILE_APPEND
        );

        file_put_contents(
            app_path('Http/Controllers/Auth/RegisterController.php'),
            str_replace(
                "App\User", config('laravelia.models.users'),
                file_get_contents(app_path('Http/Controllers/Auth/RegisterController.php'))
            )
        );

        file_put_contents(
            config_path('auth.php'),
            str_replace(
                "App\User", config('laravelia.models.users'),
                file_get_contents(config_path('auth.php'))
            )
        );
    }

    /**
     * Create the directories for the files.
     *
     * @return void resource_path('v1/layouts/partials')
     */
    protected function createDirectories($dir)
    {
        if (! is_dir($directory = $dir)) {
            mkdir($directory, 0755, true);
        }
    }

    protected function publish()
    {
        // $force = ($this->option('force')) ? true : false;
        $this->call('vendor:publish', [
            '--tag' => 'laravelia', '--force' => true
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'config-laravelia', '--force' => true
        ]);

        $this->call('config:clear');
        $this->call('cache:clear');

        file_put_contents(
            app_path('Http/Controllers/Controller.php'),
            str_replace(
                '{{namespace}}',
                $this->getAppNamespace(),
                file_get_contents(__DIR__.'/stubs/controllers/Controller.stub')
            )
        );

        file_put_contents(
            database_path('seeds/DatabaseSeeder.php'),
            file_get_contents(__DIR__.'/stubs/seeds/DatabaseSeeder.stub')
        );

        file_put_contents(
            database_path('seeds/UsersTableSeeder.php'),
            str_replace(
                ['{{model}}', '{{modelName}}'],
                [config('laravelia.models.users'), self::getModelName('users')],
                file_get_contents(__DIR__.'/stubs/seeds/UsersTableSeeder.stub')
            )
        );

        file_put_contents(
            database_path('seeds/MenuTableSeeder.php'),
            str_replace(
                ['{{model}}', '{{modelName}}'],
                [config('laravelia.models.menu'), self::getModelName('menu')],
                file_get_contents(__DIR__.'/stubs/seeds/MenuTableSeeder.stub')
            )
        );

        $this->exportModels();
    }

    protected function exportModels()
    {
        // $force = ($this->option('force')) ? true : false;
        foreach ($this->models as $key => $value) {
            $explode = str_replace(['App\\', '\\'], ['app/', '/'], config('laravelia.models.'.$value));
            $this->createDirectories(str_replace($key, '', $explode));
            if (file_exists($model = base_path($explode.'.php'))) {
                if (! $this->confirm("The [{$explode}] model already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            file_put_contents(
                $model,
                 str_replace(
                    '{{namespace}}',
                    $this->getAppNamespace().str_replace(['App\\', '\\'.$key], ['', ''], config('laravelia.models.'.$value)),
                    file_get_contents(__DIR__.'/stubs/Models/'.$key.'.stub')
                )
            );
        }
        if (file_exists($file = app_path('User.php'))) {
            $model_user = config('laravelia.models.users');
            $this->info("Move {$file} to {$model_user}");
            \File::delete($file);
        }
    }

    private function getModelName($value)
    {
        $explode = explode('\\', config("laravelia.models.{$value}"));
        return end($explode);
    }
}
