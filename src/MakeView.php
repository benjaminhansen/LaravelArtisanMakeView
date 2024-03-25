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
        $extends = $this->option('extends');
        $bootstrap = $this->option('bootstrap');
        $empty = $this->option('empty');

        if(is_null($extends)) {
            $extends = env('BASE_VIEW');
        }

        if($extends == "" || is_null($extends)) {
            $this->error("You have not configured or supplied a view to extend!\nYou must either configure BASE_VIEW in your .env file or use the \"--extends=base.view\" argument when creating a view!");
            return false;
        }

        $dir = resource_path('views');

        if(!is_null($empty) && $empty != "") {
            if(strpos($viewname, '.') !== false) {
                $parts = explode(".", $viewname);
                $count = count($parts);

                $viewfile = end($parts).".blade.php";

                for($i = 0; $i < $count-1; $i++) {
                    $folder = $parts[$i];
                    $dir .= "/".$folder;

                    if(!file_exists($dir)) {
                        mkdir($dir);
                    }
                }

                if(!file_exists($dir."/".$viewfile)) {
                    touch($dir."/".$viewfile);
                    $this->info("View [$viewname] created successfully!");
                } else {
                    $this->error("View [$viewname] already exists!");
                }
            } else {
                $viewfile = $viewname.".blade.php";
                if(!file_exists($dir."/".$viewfile)) {
                    touch($dir."/".$viewfile);
                    $this->info("View [$viewname] created successfully!");
                } else {
                    $this->error("View [$viewname] already exists!");
                }
            }
        } else {
            if($viewname == $extends) {
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

                if(strpos($viewname, '.') !== false) {
                    $parts = explode(".", $viewname);
                    $count = count($parts);

                    $viewfile = end($parts).".blade.php";

                    for($i = 0; $i < $count-1; $i++) {
                        $folder = $parts[$i];
                        $dir .= "/".$folder;

                        if(!file_exists($dir)) {
                            mkdir($dir);
                        }
                    }

                    if(!file_exists($dir."/".$viewfile)) {
                        touch($dir."/".$viewfile);
                        file_put_contents($dir."/".$viewfile, $html);
                        $this->info("View [$viewname] created successfully!");
                    } else {
                        $this->error("View [$viewname] already exists!");
                    }
                } else {
                    $viewfile = $viewname.".blade.php";
                    if(!file_exists($dir."/".$viewfile)) {
                        touch($dir."/".$viewfile);
                        file_put_contents($dir."/".$viewfile, $html);
                        $this->info("View [$viewname] created successfully!");
                    } else {
                        $this->error("View [$viewname] already exists!");
                    }
                }
            } else {
                if(strpos($viewname, '.') !== false) {
                    $parts = explode(".", $viewname);
                    $count = count($parts);

                    $viewfile = end($parts).".blade.php";

                    for($i = 0; $i < $count-1; $i++) {
                        $folder = $parts[$i];
                        $dir .= "/".$folder;

                        if(!file_exists($dir)) {
                            mkdir($dir);
                        }
                    }

                    if(!file_exists($dir."/".$viewfile)) {
                        touch($dir."/".$viewfile);

                        $content = file_get_contents(__DIR__."/shells/extends.txt");
                        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

                        file_put_contents($dir."/".$viewfile, $content);
                        $this->info("View [$viewname] created successfully!");
                    } else {
                        $this->error("View [$viewname] already exists!");
                    }
                } else {
                    $viewfile = $viewname.".blade.php";
                    if(!file_exists($dir."/".$viewfile)) {
                        touch($dir."/".$viewfile);

                        $content = file_get_contents(__DIR__."/shells/extends.txt");
                        $content = str_replace("{{BASE_VIEW}}", $extends, $content);

                        file_put_contents($dir."/".$viewfile, $content);
                        $this->info("View [$viewname] created successfully!");
                    } else {
                        $this->error("View [$viewname] already exists!");
                    }
                }
            }
        }
    }
}
