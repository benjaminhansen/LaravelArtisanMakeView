<?php

namespace BenjaminHansen\LaravelMakeView;

use Illuminate\Console\Command;

class MakeView extends Command
{
    protected $deprecated_bootstrap_versions = ['v3'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:view {viewname} {--e|extends=} {--bs|bootstrap=} {--E|empty} {--r|resourceful}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Blade View with configurable options';

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
        $viewname = $this->argument('viewname');
        $extends = $this->option('extends') ?? env('BASE_VIEW');
        $bootstrap = $this->option('bootstrap');
        $empty = $this->option('empty');
        $resourceful = $this->option('resourceful');

        $view_path = base_path('resources/views');

        // handle the actual file creation for the given blade view
        if(str_contains($viewname, '.')) {
            if($resourceful) {
                // we should create a view folder and resourceful view files inside (index, create, show, edit)
                $resource_files = ['index.blade.php', 'create.blade.php', 'show.blade.php', 'edit.blade.php'];

                $parts = explode(".", $viewname);

                foreach($parts as $folder) {
                    $folder = strtolower($folder); // lowercase all folder names
                    $folder = str_slug($folder); // slugify all folder names to make sure they are clean

                    $view_path .= "/{$folder}";

                    if(!file_exists($view_path)) {
                        mkdir($view_path);
                    }
                }

                foreach($resource_files as $file) {
                    $file_view_path = "{$view_path}/{$file}";
                    if(!file_exists($file_view_path)) {
                        touch($file_view_path);
                    } else {
                        $this->error("View file [$file_view_path] already exists!");
                        return;
                    }

                    if($extends) {
                        $content = file_get_contents(__DIR__."/shells/extends.txt");
                        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

                        file_put_contents($file_view_path, $content);
                    }
                }

                if($extends) {
                    $this->info("Resourceful child views created at [$viewname]");
                } else {
                    $this->info("Resourceful views created at [$viewname]");
                }

                return;
            } else {
                // we are dealing with at least one folder (the string includes a ".")
                $parts = explode(".", $viewname);

                // get the last element of the array, which is our blade view file
                $blade_template = strtolower(end($parts));
                $blade_file = "{$blade_template}.blade.php";

                // remove the last element from the array since it is our filename
                array_pop($parts);

                // loop over the entire array, except for the last element (which is the actual file)
                // and create the necessary directories
                foreach($parts as $folder) {
                    $folder = strtolower($folder); // lowercase all folder names
                    $folder = str_slug($folder); // slugify all folder names to make sure they are clean

                    $view_path .= "/{$folder}";

                    if(!file_exists($view_path)) {
                        mkdir($view_path);
                    }
                }

                $full_view_path = "{$view_path}/{$blade_file}";
                if(!file_exists($full_view_path)) {
                    touch($full_view_path);
                } else {
                    $this->error("View [$viewname] already exists!");
                    return;
                }
            }
        } else {
            if($resourceful) {
                // we should create a view folder and resourceful view files inside (index, create, show, edit)
                $resource_files = ['index.blade.php', 'create.blade.php', 'show.blade.php', 'edit.blade.php'];

                $view_path .= "/{$viewname}";

                if(!file_exists($view_path)) {
                    mkdir($view_path);
                }

                foreach($resource_files as $file) {
                    $file_view_path = "{$view_path}/{$file}";
                    if(!file_exists($file_view_path)) {
                        touch($file_view_path);
                    } else {
                        $this->error("View file [$file_view_path] already exists!");
                        return;
                    }

                    if($extends) {
                        $content = file_get_contents(__DIR__."/shells/extends.txt");
                        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

                        file_put_contents($file_view_path, $content);
                    }
                }

                if($extends) {
                    $this->info("Resourceful child views created at [$viewname]");
                } else {
                    $this->info("Resourceful views created at [$viewname]");
                }

                return;
            } else {
                // we are dealing with a single/top-level blade file
                $blade_file = "{$viewname}.blade.php";
                $full_view_path = "{$view_path}/{$blade_file}";
                if(!file_exists($full_view_path)) {
                    touch($full_view_path);
                } else {
                    $this->error("View [$viewname] already exists!");
                    return;
                }
            }
        }

        if($empty || !$extends) {
            // if we are creating an empty view file, bail out here
            $this->info("Empty view [$viewname] created");
            return;
        }

        // handle any extends or bootstrap logic
        if($viewname == $extends) {
            // we are creating a layout/masterpage, get the requested template and then bail out
            $html = match($bootstrap) {
                "v3" => file_get_contents(__DIR__."/shells/bootstrap3.txt"),
                "v4" => file_get_contents(__DIR__."/shells/bootstrap4.txt"),
                "v5" => file_get_contents(__DIR__."/shells/bootstrap5.txt"),
                default => file_get_contents(__DIR__."/shells/raw.txt")
            };

            if($bootstrap && in_array($bootstrap, $this->deprecated_bootstrap_versions)) {
                $this->warn("Bootstrap {$bootstrap} is deprecated, and will be removed soon. Please switch to a newer version as soon as possible.");
            }

            file_put_contents($full_view_path, $html);

            $this->info("Layout view [$viewname] created");

            return;
        }

        // get the extends template and put the content in the file
        $content = file_get_contents(__DIR__."/shells/extends.txt");
        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

        file_put_contents($full_view_path, $content);

        $this->info("Child view [$viewname] created");
    }
}
