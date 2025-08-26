<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SitemapGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sitemapUrl;

    public function __construct($sitemapUrl)
    {
        $this->sitemapUrl = $sitemapUrl;
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Sitemap generado correctamente')
                    ->view('mails.sitemap-created');
    }
}
