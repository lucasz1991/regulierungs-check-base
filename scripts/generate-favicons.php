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

$lockPath = sys_get_temp_dir().'/regulierungs-check-favicons-'.hash('sha256', $projectRoot).'.lock';
$lockHandle = fopen($lockPath, 'c');

if ($lockHandle === false || ! flock($lockHandle, LOCK_EX)) {
    throw new RuntimeException("Unable to acquire the favicon generation lock: {$lockPath}");
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

$outputs = [];

foreach ($pngTargets as $size => $path) {
    $png = squarePng($source, $size);
    $dimensions = getimagesizefromstring($png);

    if ($dimensions === false || $dimensions[0] !== $size || $dimensions[1] !== $size || $dimensions[2] !== IMAGETYPE_PNG) {
        throw new RuntimeException("Generated favicon failed validation: {$path}");
    }

    $outputs[$path] = $png;
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
$ico = $icoHeader.$icoDirectory.$icoPayload;

if (strlen($ico) <= 54 || unpack('vreserved/vtype/vcount', substr($ico, 0, 6)) !== [
    'reserved' => 0,
    'type' => 1,
    'count' => count($icoImages),
]) {
    throw new RuntimeException('Generated ICO failed validation.');
}

$outputs[$icoPath] = $ico;
imagedestroy($source);

/**
 * Write every validated output to a temporary sibling first, then atomically
 * replace the complete set. Existing files are restored if any commit fails.
 */
$temporaryFiles = [];
$backups = [];
$committedTargets = [];

try {
    foreach ($outputs as $path => $contents) {
        $temporaryPath = $path.'.tmp-'.bin2hex(random_bytes(8));
        $temporaryFiles[$path] = $temporaryPath;
        $written = file_put_contents($temporaryPath, $contents, LOCK_EX);

        if ($written !== strlen($contents)) {
            throw new RuntimeException("Unable to stage favicon: {$path}");
        }
    }

    foreach ($temporaryFiles as $path => $temporaryPath) {
        if (is_file($path)) {
            $backupPath = $path.'.bak-'.bin2hex(random_bytes(8));

            if (! rename($path, $backupPath)) {
                throw new RuntimeException("Unable to stage existing favicon for replacement: {$path}");
            }

            $backups[$path] = $backupPath;
        }

        if (! rename($temporaryPath, $path)) {
            throw new RuntimeException("Unable to commit favicon: {$path}");
        }

        unset($temporaryFiles[$path]);
        $committedTargets[$path] = true;
    }

    $backupCleanupFailures = [];
    foreach ($backups as $backupPath) {
        if (! unlink($backupPath)) {
            $backupCleanupFailures[] = $backupPath;
        }
    }

    if ($backupCleanupFailures !== []) {
        fwrite(STDERR, 'Favicon generation succeeded, but backups could not be removed: '.implode(', ', $backupCleanupFailures).PHP_EOL);
    }
} catch (Throwable $exception) {
    $rollbackFailures = [];

    foreach (array_reverse(array_keys($committedTargets)) as $path) {
        if (is_file($path) && ! unlink($path)) {
            $rollbackFailures[] = "Unable to remove incomplete favicon: {$path}";
        }
    }

    foreach ($backups as $path => $backupPath) {
        if (is_file($path)) {
            $rollbackFailures[] = "Unable to restore favicon while target still exists: {$path}";

            continue;
        }

        if (! is_file($backupPath) || ! rename($backupPath, $path)) {
            $rollbackFailures[] = "Unable to restore favicon backup: {$backupPath}";
        } else {
            unset($backups[$path]);
        }
    }

    foreach ($temporaryFiles as $temporaryPath) {
        if (is_file($temporaryPath) && ! unlink($temporaryPath)) {
            $rollbackFailures[] = "Unable to remove staged favicon: {$temporaryPath}";
        }
    }

    if ($rollbackFailures !== []) {
        throw new RuntimeException(
            $exception->getMessage().' Rollback incomplete: '.implode(' ', $rollbackFailures),
            0,
            $exception,
        );
    }

    throw $exception;
}

flock($lockHandle, LOCK_UN);
fclose($lockHandle);

foreach (array_keys($outputs) as $path) {
    echo str_replace($projectRoot.'/', '', $path).PHP_EOL;
}
