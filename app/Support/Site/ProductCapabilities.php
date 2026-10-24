<?php

namespace App\Support\Site;

final class ProductCapabilities
{
    /** Public copy only; never use these labels for authorization. */
    public function publicFeatures(): array
    {
        return config('product-capabilities', []);
    }
}
