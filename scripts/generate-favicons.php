<?php

declare(strict_types=1);

if (! extension_loaded('gd')) {
    throw new RuntimeException('The GD extension is required to generate favicon assets.');
}

$projectRoot = dirname(__DIR__);
$sourcePath = $projectRoot.'/public/site-images/logo/logo-icon.png';
$targetDirectory = $projectRoot.'/public/site-images/favicon';

if (! is_file($sourcePath)) {
    throw new RuntimeException("Favicon source not found: {$sourcePath}");
}

if (! is_dir($targetDirectory) && ! mkdir($targetDirectory, 0775, true) && ! is_dir($targetDirectory)) {
    throw new RuntimeException("Unable to create favicon directory: {$targetDirectory}");
}

$source = imagecreatefrompng($sourcePath);

if ($source === false) {
    throw new RuntimeException("Unable to read favicon source: {$sourcePath}");
}

/**
 * Put the existing logo on a transparent square canvas without stretching it.
 */
function squarePng(GdImage $source, int $size): string
{
    $canvas = imagecreatetruecolor($size, $size);

    if ($canvas === false) {
        throw new RuntimeException("Unable to create a {$size}x{$size} favicon canvas.");
    }

    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);
    $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
    imagefilledrectangle($canvas, 0, 0, $size - 1, $size - 1, $transparent);
    imagealphablending($canvas, true);

    $sourceWidth = imagesx($source);
    $sourceHeight = imagesy($source);
    $scale = min($size / $sourceWidth, $size / $sourceHeight);
    $width = max(1, (int) round($sourceWidth * $scale));
    $height = max(1, (int) round($sourceHeight * $scale));
    $x = (int) floor(($size - $width) / 2);
    $y = (int) floor(($size - $height) / 2);

    imagecopyresampled($canvas, $source, $x, $y, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

    ob_start();
    imagepng($canvas, null, 9, PNG_ALL_FILTERS);
    $png = ob_get_clean();
    imagedestroy($canvas);

    if (! is_string($png) || $png === '') {
        throw new RuntimeException("Unable to encode the {$size}x{$size} favicon.");
    }

    return $png;
}

$pngTargets = [
    48 => $targetDirectory.'/favicon-48x48.png',
    96 => $targetDirectory.'/favicon-96x96.png',
    180 => $targetDirectory.'/apple-touch-icon.png',
];

foreach ($pngTargets as $size => $path) {
    if (file_put_contents($path, squarePng($source, $size)) === false) {
        throw new RuntimeException("Unable to write favicon: {$path}");
    }
}

$icoSizes = [16, 32, 48];
$icoImages = [];

foreach ($icoSizes as $size) {
    $icoImages[$size] = squarePng($source, $size);
}

$icoHeader = pack('vvv', 0, 1, count($icoImages));
$icoDirectory = '';
$icoPayload = '';
$offset = 6 + (16 * count($icoImages));

foreach ($icoImages as $size => $png) {
    $icoDirectory .= pack('CCCCvvVV', $size, $size, 0, 0, 1, 32, strlen($png), $offset);
    $icoPayload .= $png;
    $offset += strlen($png);
}

$icoPath = $projectRoot.'/public/favicon.ico';

if (file_put_contents($icoPath, $icoHeader.$icoDirectory.$icoPayload) === false) {
    throw new RuntimeException("Unable to write favicon: {$icoPath}");
}

imagedestroy($source);

foreach ([...array_values($pngTargets), $icoPath] as $path) {
    echo str_replace($projectRoot.'/', '', $path).PHP_EOL;
}
