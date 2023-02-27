<?php

namespace Tests\Feature\Misc;

use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Tests\Feature\Concerns\FindsClassesInFiles;
use Tests\TestCase;

class AllEventsAreFinalTest extends TestCase
{
    use FindsClassesInFiles;

    /**
     * A basic feature test example.
     */
    public function test_all_event_classes_are_final(): void
    {
        $finder = (new Finder)->files()->in($this->app->path('Events'));

        collect($finder)
            ->tap(
                fn ($c) => $this->assertNotEquals(0, $c->count()),
            )
            ->map(fn (SplFileInfo $fileInfo) => $this->classFromFile($fileInfo))
            ->map(fn (string $class) => new ReflectionClass($class))
            ->each(
                fn (ReflectionClass $r) => $this->assertTrue(
                    $r->isFinal(),
                    "{$r->getName()} is not a final class. All events must be final",
                ),
            );
    }
}
