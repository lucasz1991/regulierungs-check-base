<?php

namespace App\Services\Promotion;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

final class PromotionQrCodeService
{
    public function svg(string $payload): string
    {
        $renderer = new ImageRenderer(new RendererStyle(420, 2), new SvgImageBackEnd);

        return (new Writer($renderer))->writeString($payload);
    }
}
