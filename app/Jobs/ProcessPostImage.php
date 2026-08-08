<?php

namespace App\Jobs;

use App\Services\ImageProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Optimitza l'original i genera les variants WebP d'una imatge de post.
 * Es processa una sola imatge per job per aïllar el pic de memòria.
 */
class ProcessPostImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    /**
     * @param string $filename  Nom desat, p. ex. "uuid.jpg" (dins storage/images/posts/)
     * @param string $type      "featured" | "gallery"
     */
    public function __construct(
        public string $filename,
        public string $type
    ) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(ImageProcessor $processor): void
    {
        $postsDir = public_path('storage/images/posts/');
        $original = $postsDir . $this->filename;

        if (!is_file($original)) {
            return;
        }

        $processor->optimizeOriginal($original, 1600, 82);

        $base = pathinfo($this->filename, PATHINFO_FILENAME);

        if ($this->type === 'featured') {
            $resizedDir = $postsDir . 'resized/';
            $processor->makeVariant($original, $resizedDir . 'resized_' . $base . '.webp', 512, 320);
            $processor->makeVariant($original, $resizedDir . 'thumb_' . $base . '.webp', 250, 250);
        } else {
            $galleryDir = $postsDir . 'carousel/resized/';
            $processor->makeVariant($original, $galleryDir . 'resized_' . $base . '.webp', 1024, 640);
        }
    }
}
