<?php

declare(strict_types=1);

namespace App\CodeQuality\PhpCsFixer\Fixers;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\AllowedValueSubset;
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
class BlankLineBeforeStatementBlocksFixer extends AbstractFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
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

    /**
     * {@inheritdoc}
     */
    public function configure(array $configuration): void
    {
        parent::configure($configuration);

        $this->fixTokenMap = [];

        foreach ($this->configuration['statement_blocks'] as $key) {
            $this->fixTokenMap[$key] = self::$tokenMap[$key];
        }

        $this->fixTokenMap = array_values($this->fixTokenMap);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'An empty line feed must precede any configured statement block.',
            [
                new CodeSample(
                    '<?php
if ($foo === false) {
    $foo = 0;
} else {
    $bar = 9000;
}
',
                    [
                        'statement_blocks' => ['else'],
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

            if (is_int($indexToAddBlankLineTo = $this->indexToAddBlankLineTo($tokens, $prevNonWhitespace))) {
                $this->insertBlankLine($tokens, $indexToAddBlankLineTo);
            }

            $index = $indexToAddBlankLineTo ?: $prevNonWhitespace;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder('statement_blocks', 'List of statement blocks which must be preceded by an empty line.'))
                ->setAllowedTypes(['array'])
                ->setAllowedValues([new AllowedValueSubset(array_keys(self::$tokenMap))])
                ->setDefault(array_keys(self::$tokenMap))
                ->getOption(),
        ]);
    }

    private function indexToAddBlankLineTo(Tokens $tokens, int $prevNonWhitespace): int|false
    {
        $prevNonWhitespaceToken = $tokens[$prevNonWhitespace];

        if ($prevNonWhitespaceToken->equalsAny([';', '}'])) {
            $beforeBlockEnding = $tokens->getPrevNonWhitespace($prevNonWhitespace);

            if ($tokens[$beforeBlockEnding]->isComment()) {
                return $beforeBlockEnding;
            }

            return $prevNonWhitespace;
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
