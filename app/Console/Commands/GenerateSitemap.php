<?php

namespace App\Console\Commands;

use App\Mail\SitemapGeneratedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\Sitemap\SitemapGenerator;

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
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated successfully.');
        $sitemapUrl = url('/sitemap.xml');
        Mail::to('juanmi0802@gmail.com')->send(new SitemapGeneratedMail($sitemapUrl));
        $this->info('Notification email sent successfully.');
    }
}
