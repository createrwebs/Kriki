<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Lab\Builder\TemplateCatalog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class WebsiteBuilderController extends Controller
{
    public function __invoke(TemplateCatalog $catalog): Response
    {
        return response()->view('lab.builder', [
            'bootstrap' => [
                'catalog' => $catalog->all(),
                'endpoints' => [
                    'catalog' => '/lab/builder/catalog',
                    'drafts' => '/lab/builder/drafts',
                ],
                'storage_ready' => Schema::hasTable('website_drafts'),
            ],
        ])->header('Cache-Control', 'private, no-store');
    }
}
