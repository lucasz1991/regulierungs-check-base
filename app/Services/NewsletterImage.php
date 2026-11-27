<?php

namespace App\Services;

use App\Models\Post;
use GdImage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\ImageManager;
use RuntimeException;

class NewsletterImage
{
    public function prepare(Post $post, int $focusX, int $focusY): array
    {
        $photo = $post->firstNewsImage();
        $disk = Storage::disk('public');
        $root = realpath($disk->path(''));
        $source = $photo ? realpath($disk->path($photo['path'])) : false;
        if (! $root || ! $source || ! str_starts_with($source, rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR)
            || ! is_file($source) || filesize($source) > 8 * 1024 * 1024) {
            throw new RuntimeException('Das Beitragsbild ist nicht verfügbar.');
        }

        $focusX = max(0, min(100, $focusX));
        $focusY = max(0, min(100, $focusY));
        $digest = hash_file('sha256', $source);
        if (! $digest) {
            throw new RuntimeException('Das Beitragsbild konnte nicht gelesen werden.');
        }
        $version = hash('sha256', "newsletter-2to1-v1:$digest:$focusX:$focusY");
        $prefix = "newsletter/bilder/$version";
        $path = Cache::lock("newsletter-image-$version", 60)->block(12, function () use ($source, $disk, $prefix, $focusX, $focusY) {
            foreach (['jpg', 'png'] as $format) {
                if ($disk->exists("$prefix.$format")) {
                    return "$prefix.$format";
                }
            }
            [$encoded, $format] = $this->crop($source, $focusX, $focusY);
            $path = "$prefix.$format";
            File::ensureDirectoryExists(dirname($disk->path($path)));
            // A reader sees the entire new image, even if several campaigns approve at once.
            File::replace($disk->path($path), $encoded, 0644);

            return $path;
        });
        [$width, $height] = getimagesize($disk->path($path));

        return [
            'source' => $photo['path'],
            'path' => $path,
            'url' => rtrim(config('app.url'), '/').'/storage/'.$path,
            'width' => $width,
            'height' => $height,
            'bytes' => $disk->size($path),
            'alt' => trim((string) $photo['alt']) ?: $post->title,
        ];
    }

    private function crop(string $file, int $focusX, int $focusY): array
    {
        $info = @getimagesize($file);
        if (! $info || ! in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)
            || $info[0] * $info[1] > 16000000 || $info[0] > 16384 || $info[1] > 16384) {
            throw new RuntimeException('Das Beitragsbild hat ein ungeeignetes Format oder zu viele Pixel.');
        }
        // GD decodes only the first GIF frame. The image driver applies EXIF orientation to photographs.
        $source = $info[2] === IMAGETYPE_GIF
            ? @imagecreatefromgif($file)
            : ImageManager::gd()->read($file, FilePathImageDecoder::class)->core()->native();
        if (! $source instanceof GdImage) {
            throw new RuntimeException('Das Beitragsbild konnte nicht geöffnet werden.');
        }
        $width = imagesx($source);
        $height = imagesy($source);
        $cropHeight = min(intdiv($width, 2), $height);
        if ($cropHeight < 1) {
            throw new RuntimeException('Das Bild ist zu schmal für das Newsletter-Format.');
        }
        $cropWidth = 2 * $cropHeight;
        $x = (int) max(0, min($width - $cropWidth, round($focusX * $width / 100 - $cropWidth / 2)));
        $y = (int) max(0, min($height - $cropHeight, round($focusY * $height / 100 - $cropHeight / 2)));
        $sizes = array_unique(array_map(fn ($limit) => min($cropHeight, $limit), [600, 470, 360, 240, 160]));
        foreach ($sizes as $h) {
            $target = imagecreatetruecolor(2 * $h, $h);
            imagealphablending($target, false);
            imagesavealpha($target, true);
            imagecopyresampled($target, $source, 0, 0, $x, $y, 2 * $h, $h, $cropWidth, $cropHeight);
            $png = $info[2] !== IMAGETYPE_JPEG && $this->hasTransparency($target);
            foreach ($png ? [8] : [82, 70] as $quality) {
                ob_start();
                try {
                    $ok = $png ? imagepng($target, null, $quality) : imagejpeg($target, null, $quality);
                    $data = ob_get_contents();
                } finally {
                    ob_end_clean();
                }
                if ($ok && $data && strlen($data) <= 150000) {
                    return [$data, $png ? 'png' : 'jpg'];
                }
            }
            if ($ok && $data && strlen($data) <= 200000) {
                return [$data, $png ? 'png' : 'jpg'];
            }
        }

        throw new RuntimeException('Das Bild lässt sich nicht auf 200 kB komprimieren.');
    }

    private function hasTransparency(GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ((imagecolorat($image, $x, $y) >> 24) & 127) {
                    return true;
                }
            }
        }

        return false;
    }
}
