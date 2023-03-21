<?php

declare(strict_types=1);

namespace App\CodeQuality\PhpCsFixer\Fixers;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Is just a slightly modified PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer.
 */
class BlankLineBeforeElseBlocksFixer extends AbstractFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
{
    /**
     * @var array<string, int>
     */
    private static array $tokenMap = [
        'else' => T_ELSE,
        'elseif' => T_ELSEIF,
    ];

    /**
     * @var list<int>
     */
    private array $fixTokenMap = [];

    protected bool $shouldFixNonBracketBlocks;

    /**
     * {@inheritdoc}
     */
    public function configure(array $configuration): void
    {
        parent::configure($configuration);

        $this->shouldFixNonBracketBlocks = $this->configuration['fix_non_bracket_blocks'];

        $this->fixTokenMap = array_values(self::$tokenMap);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'An empty line feed must precede else\elseif blocks.',
            [
                new CodeSample(
                    '<?php
if ($foo === false) {
    $foo = 0;
} else {
    $bar = 9000;
}
',
                ),
                new CodeSample(
                    '<?php
if ($foo === false)
    $foo = 0;
else
    $bar = 9000;
',
                    [
                        'fix_non_bracket_blocks' => true,
                    ]
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Must run after NoExtraBlankLinesFixer, NoUselessReturnFixer, ReturnAssignmentFixer.
     */
    public function getPriority(): int
    {
        return -21;
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound($this->fixTokenMap);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index > 0; $index--) {
            $token = $tokens[$index];

            if (! $token->isGivenKind($this->fixTokenMap)) {
                continue;
            }

            $prevNonWhitespace = $tokens->getPrevNonWhitespace($index);

            if (is_int($indexToInsertBlankLineBefore = $this->indexToInsertBlankLineBefore($tokens, $prevNonWhitespace))) {
                $this->insertBlankLine($tokens, $indexToInsertBlankLineBefore);
            }

            $index = $indexToInsertBlankLineBefore ?: $prevNonWhitespace;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder('fix_non_bracket_blocks', 'Should insert blank line before else block without brackets.'))
                ->setAllowedTypes(['bool'])
                ->setDefault(false)
                ->getOption(),
        ]);
    }

    private function indexToInsertBlankLineBefore(Tokens $tokens, int $prevNonWhitespace): int|false
    {
        $toInsertBefore    = $prevNonWhitespace;
        $isNonBracketBlock = true;

        if (
            $this->shouldFixNonBracketBlocks
            && $tokens[$toInsertBefore]->equals(';')
        ) {
            return $tokens->getNextNonWhitespace($toInsertBefore);
        }

        if ($tokens[$toInsertBefore]->equals('}')) {
            $isNonBracketBlock = false;
            $beforeBlockEnding = $tokens->getPrevNonWhitespace($toInsertBefore);

            if ($tokens[$beforeBlockEnding]->isComment()) {
                $toInsertBefore = $beforeBlockEnding;

            } else {
                return $toInsertBefore;
            }
        }

        if ($tokens[$toInsertBefore]->isComment()) {
            while ($tokens[$toInsertBefore]->isComment()) {
                $firstComment   = $toInsertBefore;
                $toInsertBefore = $tokens->getPrevNonWhitespace($toInsertBefore);
            }

            if ($isNonBracketBlock && ! $this->shouldFixNonBracketBlocks) {
                return false;
            }

            return $firstComment;
        }

        return false;
    }

    private function insertBlankLine(Tokens $tokens, int $index): void
    {
        $prevIndex  = $index - 1;
        $prevToken  = $tokens[$prevIndex];
        $lineEnding = $this->whitespacesConfig->getLineEnding();

        if ($prevToken->isWhitespace()) {
            $newlinesCount = substr_count($prevToken->getContent(), "\n");

            if (0 === $newlinesCount) {
                $tokens[$prevIndex] = new Token([T_WHITESPACE, rtrim($prevToken->getContent(), " \t").$lineEnding.$lineEnding]);

            } elseif (1 === $newlinesCount) {
                $tokens[$prevIndex] = new Token([T_WHITESPACE, $lineEnding.$prevToken->getContent()]);
            }

        } else {
            $tokens->insertAt($index, new Token([T_WHITESPACE, $lineEnding.$lineEnding]));
        }
    }
}
