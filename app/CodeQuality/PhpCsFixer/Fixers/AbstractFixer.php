<?php

declare(strict_types=1);

namespace App\CodeQuality\PhpCsFixer\Fixers;

use PhpCsFixer\AbstractFixer as  BuiltInAbstractFixer;

abstract class AbstractFixer extends BuiltInAbstractFixer
{
    public const VENDOR = 'HorribleFixersUnlimited';

    public function getName(): string
    {
        return self::VENDOR.'/'.parent::getName();
    }
}
