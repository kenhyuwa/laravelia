<?php

namespace Ken\Laravelia\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class NavigationComposer extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:composer-navigation
                    {--force : Overwrite existing navigations views composer by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a navigations view composer.';

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
     * @return void
     */
    public function handle()
    {
        $this->comment("\n");
        $this->warn('----------------------------------------------');
        $this->warn('=================== WARNING ==================');
        $this->warn('----------------------------------------------');

        $force = ($this->option('force')) ? true : false;
        $existing = (file_exists($file = app_path('Http/ViewComposers/NavigationComposer.php')) && !$force) ? true : false;
        $confirm = ($existing) ? $this->confirm("File already exists. Do you want to replace it?") : $this->confirm("Are you SURE you wish to continue?");
        if ($force || ($confirm)) {
            $this->createDirectories();

            file_put_contents(
                $file,
                $this->compileNavigationsStub()
            );

            $this->comment("\n");
            $this->info('Navigations composer successfully published.');
        }
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir($directory = app_path('Http/ViewComposers'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Compiles the NavigationComposer stub.
     *
     * @return string
     */
    protected function compileNavigationsStub()
    {
        $model = explode("\\", config('laravelia.models.menu'));
        return str_replace(
            ['{{namespace}}', '{{modelNamespace}}', '{{modelName}}'],
            [$this->getAppNamespace(), config('laravelia.models.menu'), end($model)],
            file_get_contents(__DIR__.'/stubs/ViewComposers/NavigationComposer.stub')
        );
    }
}
