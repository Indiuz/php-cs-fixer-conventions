<?php

declare(strict_types = 1);

namespace Indiuz\PhpCsFixerConventions\Fixer;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Class InlineCommentSpacerFixer.
 */
final class InlineCommentSpacerFixer implements FixerInterface {

  /**
   *
   */
  public function fix(\SplFileInfo $file, Tokens $tokens): void {
    foreach ($tokens as $index => $token) {
      $content = $token->getContent();

      if (!$token->isComment() || mb_strpos($content, '//') !== 0 || mb_strpos($content, '// ') === 0) {
        continue;
      }

      if ($token->getContent() === '//') {
        continue;
      }

      $content = substr_replace($content, ' ', 2, 0);
      $tokens[$index] = new Token([$token->getId(), $content]);
    }
  }

  /**
   *
   */
  public function getDefinition(): FixerDefinitionInterface {
    return new FixerDefinition(
      'Puts a space after every inline comment start.',
      [
        new CodeSample('<?php //Whut' . \PHP_EOL),
      ]
    );
  }

  /**
   *
   */
  public function getName(): string {
    return 'Drupal/inline_comment_spacer';
  }

  /**
   *
   */
  public function getPriority(): int {
    return 30;
  }

  /**
   *
   */
  public function isCandidate(Tokens $tokens): bool {
    return $tokens->isTokenKindFound(\T_COMMENT);
  }

  /**
   *
   */
  public function isRisky(): bool {
    return FALSE;
  }

  /**
   *
   */
  public function supports(\SplFileInfo $file): bool {
    return TRUE;
  }

}
