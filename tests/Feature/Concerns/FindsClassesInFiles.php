<?php

namespace Tests\Feature\Concerns;

use Illuminate\Support\Str;
use SplFileInfo;

trait FindsClassesInFiles
{
    /**
     * Extract the class name from the given file path.
     *
     * @param  \SplFileInfo  $file
     * @return class-string
     */
    protected static function classFromFile(SplFileInfo $file): string
    {
        $class = trim(Str::replaceFirst(base_path(), '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        /** @var class-string */
        $class = str_replace(
            [DIRECTORY_SEPARATOR, ucfirst(basename(app()->path())).'\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $class;
    }
}
