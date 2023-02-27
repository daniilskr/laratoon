<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class ModelStrictBehaviourEnforcedTest extends TestCase
{
    public function test_prevents_accessing_missing_attributes_by_default(): void
    {
        $this->assertTrue(Model::preventsAccessingMissingAttributes());
    }

    public function test_prevents_silently_discarding_attributes_by_default(): void
    {
        $this->assertTrue(Model::preventsSilentlyDiscardingAttributes());
    }

    public function test_does_not_prevent_lazy_loading_by_default(): void
    {
        $this->assertFalse(Model::preventsLazyLoading());
    }
}
