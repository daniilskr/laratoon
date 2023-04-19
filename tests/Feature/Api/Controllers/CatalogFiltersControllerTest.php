<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\ComicTag;
use App\Models\Genre;
use App\Models\PublicationStatus;
use Database\Factories\ComicTagFactory;
use Database\Factories\GenreFactory;
use Database\Factories\PublicationStatusFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CatalogFiltersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_filters_match_the_specification(): void
    {
        Genre::factory(count(GenreFactory::GENRES))->create();
        ComicTag::factory(count(ComicTagFactory::TAGS))->create();
        PublicationStatus::factory(count(PublicationStatusFactory::STATUSES))->create();

        $response = $this->get('/api/catalog-filters');

        $specification = [
            'genres' => [
                0 => [
                    'id' => 'integer',
                    'name' => 'string',
                    'slug' => 'string',
                ],
            ],
            'statuses' => [
                0 => [
                    'id' => 'integer',
                    'name' => 'string',
                    'slug' => 'string',
                ],
            ],
            'tags' => [
                0 => [
                    'id' => 'integer',
                    'name' => 'string',
                    'slug' => 'string',
                ],
            ],
        ];

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                ->whereAllType(Arr::dot($specification))
            );
    }
}
