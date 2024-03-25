<?php

namespace LaravelMakeView;
use Illuminate\Console\Command;

class MakeView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:view {viewname} {--extends=} {--bootstrap=} {--empty}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Blade View';

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

        if($extends == "" || is_null($extends)) {
            $this->error("You have not configured or supplied a view to extend!\nYou must either configure BASE_VIEW in your .env file or use the \"--extends=base.view\" argument when creating a view!");
            return false;
        }

        $view_path = resource_path('views');
        $viewname = str_replace('..', '.', $viewname);

        // handle the actual file creation for the given blade view
        if(str_contains($viewname, '.')) {
            // we are dealing with at least one folder (the string includes a ".")
            $parts = explode(".", $viewname);
            $count = count($parts);

            // get the last element of the array, which is our blade view file
            $blade_el = strtolower(end($parts));
            $blade_file = "{$blade_el}.blade.php";

            // loop over the entire array, except for the last element (which is the actual file)
            // and create the necessary directories
            for($i = 0; $i < $count-1; $i++) {
                $folder = $parts[$i];
                $view_path .= "/{$folder}";

                if(!file_exists($folder)) {
                    mkdir($folder);
                }
            }

            $full_view_path = "{$folder}/{$blade_file}";
            if(!file_exists($full_view_path)) {
                touch($full_view_path);
                $this->info("View [$viewname] created successfully!");
            } else {
                $this->error("View [$viewname] already exists!");
            }
        } else {
            // we are dealing with a single/top-level blade file
            $blade_file = "{$viewname}.blade.php";
            $full_view_path = "{$view_path}/{$blade_file}";
            if(!file_exists($full_view_path)) {
                touch($full_view_path);
                $this->info("Empty view [$viewname] created successfully!");
            } else {
                $this->error("View [$viewname] already exists!");
            }
        }

        if($empty) {
            // if we are creating an empty view file, bail out here
            return;
        }

        // handle any extends or bootstrap logic
        if($viewname == $extends) {
            // we are creating a layout/masterpage, get the requested template and then bail out
            switch($bootstrap) {
                case "v3":
                    $html = file_get_contents(__DIR__."/shells/bootstrap3.txt");
                    break;
                case "v4":
                    $html = file_get_contents(__DIR__."/shells/bootstrap4.txt");
                    break;
                case "v5":
                    $html = file_get_contents(__DIR__."/shells/bootstrap5.txt");
                    break;
                default:
                    $html = file_get_contents(__DIR__."/shells/raw.txt");
            }

            file_put_contents($full_view_path, $html);

            $this->info("Layout view [$viewname] created successfully!");

            return;
        }

        // get the extends template and put the content in the file
        $content = file_get_contents(__DIR__."/shells/extends.txt");
        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

        file_put_contents($full_view_path, $content);

        $this->info("Child view [$viewname] created successfully!");
    }
}
