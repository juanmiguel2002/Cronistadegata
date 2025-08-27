<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // modify this to your own needs
        $sitemap = Sitemap::create()
            ->add(config('app.url'))
            ->add(route('destacats'))
            ->add(route('posts.filter'))
            ->add(route('search'));

        foreach (\App\Models\Post::all() as $post) {
            $sitemap->add(route('post', $post->slug), $post->updated_at, 0.8);
        }
        foreach (\App\Models\Category::all() as $category) {
            $sitemap->add(route('category', $category->slug), $category->updated_at, 0.7);
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
