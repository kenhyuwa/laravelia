<?php

namespace Ken\Laravelia\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class Composer extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:composer
                    {--force : Overwrite existing views composer by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic views composer navigations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createDirectories();

        file_put_contents(
            app_path('Http/ViewComposers/NavigationComposer.php'),
            $this->compileControllerStub()
        );

        $this->info('Views composer scaffolding generated successfully.');
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
    protected function compileControllerStub()
    {
        return str_replace(
            ['{{namespace}}', '{{models}}'],
            [$this->getAppNamespace(), config('laravelia.models.menu')],
            file_get_contents(__DIR__.'/stubs/ViewComposers/NavigationComposer.stub')
        );
    }
}
