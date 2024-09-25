<?php

namespace BenjaminHansen\LaravelMakeView;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\View;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class MakeView extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:view {viewname} {--v|volt} {--e|extends=} {--u|uses=} {--E|empty} {--r|resourceful} {--s|suffix=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Blade View with configurable options.';

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
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'viewname' => fn() => text(label: 'What is the name of the view you want to create?', placeholder: 'view.name'),
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $viewname = $this->argument('viewname');
        $viewname = str_replace(['/'], '.', $viewname);

        $volt = $this->option('volt');

        if(confirm(label: 'Should this view extend a parent view?', default: false, yes: 'Yes', no: 'No')) {
            $extends = $this->option('extends') ?? env('BASE_VIEW');
            $extends = str_replace(['/'], '.', $extends);

            if(!$extends) {
                $extends = text(label: 'What view should this new view extend?', placeholder: 'layouts.app');
            }
        } else {
            $extends = null;
        }

        if(!$volt) {
            $uses = $this->option('uses') ?? select(label: 'Use a premade base for this view?', options: [
                'bootstrap5' => 'Bootstrap v5',
                'tailwind' => 'Tailwind CSS',
                'raw' => 'Blank Template'
            ]);
            $empty = $this->option('empty') ?? confirm(label: 'Create an empty view?', default: false, yes: 'Yes', no: 'No');
            $resourceful = $this->option('resourceful') ?? confirm(label: 'Create a resourceful set of child views?', default: false, yes: 'Yes', no: 'No');
        }

        $suffix = $this->option('suffix') ?? text(label: 'Provide a view suffix', placeholder: 'blade.php', default: 'blade.php');

        if($volt) {
            $view_uri = 'resources/views/livewire';
        } else {
            $view_uri = 'resources/views';
        }
        $view_path = base_path($view_uri);

        $resource_files = ["index.{$suffix}", "create.{$suffix}", "show.{$suffix}", "edit.{$suffix}"];

        if($extends && !View::exists($extends)) {
            $this->error("Base view [{$extends}] was not found!");
            return;
        }

        // handle the actual file creation for the given blade view
        if(str_contains($viewname, '.')) {
            $parts = explode('.', $viewname);

            if($resourceful) {
                // we should create a view folder and resourceful view files inside (index, create, show, edit)
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
                // get the last element of the array, which is our blade view file
                $blade_template = strtolower(end($parts));
                $blade_file = "{$blade_template}.{$suffix}";

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
                $blade_file = "{$viewname}.{$suffix}";
                $full_view_path = "{$view_path}/{$blade_file}";
                if(!file_exists($full_view_path)) {
                    touch($full_view_path);
                } else {
                    $this->error("View [$viewname] already exists!");
                    return;
                }
            }
        }

        if($volt) {
            $contents = file_get_contents(__DIR__."/shells/livewire-volt.txt");
            file_put_contents($full_view_path, $contents);

            $this->info("New Livewire Volt component [$viewname] created");

            return;
        }

        if($empty || !$extends) {
            // if we are creating an empty view file, bail out here
            $this->info("Empty view [$viewname] created");
            return;
        }

        // handle any extends or uses logic
        if($uses) {
            // we are creating a layout/masterpage, get the requested template and then bail out
            $html = match($uses) {
                "tailwind" => file_get_contents(__DIR__."/shells/tailwind.txt"),
                "bootstrap5" => file_get_contents(__DIR__."/shells/bootstrap5.txt"),
                default => file_get_contents(__DIR__."/shells/raw.txt")
            };

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
