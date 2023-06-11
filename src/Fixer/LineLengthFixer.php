<?php

declare(strict_types = 1);

namespace indiuz\PhpCsFixerConventions\Fixer;

use Symplify\TokenRunner\Transformer\FixerTransformer\LineLengthTransformer;
use Symplify\TokenRunner\Analyzer\FixerAnalyzer\TokenSkipper;
use Symplify\TokenRunner\Analyzer\FixerAnalyzer\BlockFinder;
use Symplify\TokenRunner\Analyzer\FixerAnalyzer\IndentDetector;
use PhpCsFixer\WhitespacesFixerConfig;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Class LineLengthFixer.
 */
final class LineLengthFixer implements ConfigurableFixerInterface {
  /**
   * @var \Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer
   */
  private $lineLengthFixer;

  /**
   * LineLengthFixer constructor.
   *
   * @param $indent
   * @param $lineEnding
   */
  public function __construct($indent, $lineEnding) {
    $whitespacesFixerConfig = new WhitespacesFixerConfig($indent, $lineEnding);

    $indentDetector = new IndentDetector(
      $whitespacesFixerConfig
    );

    $blockFinder = new BlockFinder();

    $tokenSkipper = new TokenSkipper(
      $blockFinder
    );

    $lineLengthTransformer = new LineLengthTransformer(
      $indentDetector,
      $tokenSkipper,
      $whitespacesFixerConfig
    );

    $this->lineLengthFixer = new \Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer(
      $lineLengthTransformer,
      $blockFinder
    );
  }

  /**
   *
   */
  public function configure(?array $configuration = NULL): void {
    $this->lineLengthFixer->configure((array) $configuration);
  }

  /**
   *
   */
  public function fix(\SplFileInfo $file, Tokens $tokens): void {
    $this->lineLengthFixer->fix($file, $tokens);
  }

  /**
   *
   */
  public function getConfigurationDefinition(): FixerConfigurationResolverInterface {
  }

  /**
   *
   */
  public function getDefinition(): FixerDefinitionInterface {
    return $this->lineLengthFixer->getDefinition();
  }

  /**
   *
   */
  public function getName(): string {
    return 'Drupal/line_length';
  }

  /**
   *
   */
  public function getPriority(): int {
    return $this->lineLengthFixer->getPriority();
  }

  /**
   *
   */
  public function isCandidate(Tokens $tokens): bool {
    return $this->lineLengthFixer->isCandidate($tokens);
  }

  /**
   *
   */
  public function isRisky(): bool {
    return $this->lineLengthFixer->isRisky();
  }

  /**
   *
   */
  public function supports(\SplFileInfo $file): bool {
    return $this->lineLengthFixer->supports($file);
  }

}
