<?php

declare(strict_types = 1);

namespace indiuz\PhpCsFixerConventions\Config;

use indiuz\PhpCsFixerConventions\Fixer\BlankLineBeforeEndOfClass;
use indiuz\PhpCsFixerConventions\Fixer\ControlStructureCurlyBracketsElseFixer;
use indiuz\PhpCsFixerConventions\Fixer\InlineCommentSpacerFixer;
use indiuz\PhpCsFixerConventions\Fixer\TryCatchBlock;
use PhpCsFixer\Config;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConfig.
 */
class Drupal9 extends Config {

  /**
   * Class Drupal9.
   */
  public function __construct() {
    parent::__construct('indiuz/php-cs-fixer-conventions/drupal9');

    // $parent = $this->withRulesFromYaml(dirname(__DIR__, 2) . '../../config/drupal9/php-cs-fixer.rules.yml');
    $parent = $this->withRulesFromYaml(\dirname(__DIR__, 2) . '/config/drupal9/php-cs-fixer.rules.yml');

    $this->setRules($parent->getRules());

    $this->setIndent('  ');

    $this->setLineEnding($parent->getLineEnding());

    $this->registerCustomFixers([
      new BlankLineBeforeEndOfClass($this->getIndent(), $this->getLineEnding()),
      new ControlStructureCurlyBracketsElseFixer($this->getIndent(), $this->getLineEnding()),
      new InlineCommentSpacerFixer(),
      new TryCatchBlock($this->getIndent(), $this->getLineEnding()),
      // Work in progress
      // new NewlineAfterLastCommaInArrayFixer($this->getIndent(), $this->getLineEnding()),
    ]);

    $this->setRiskyAllowed(TRUE);

    $this->setFinder(
      $this->getFinder()
        ->name('*.inc')
        ->name('*.install')
        ->name('*.module')
        ->name('*.profile')
        ->name('*.php')
        ->name('*.theme')
        ->in($_SERVER['PWD'])
    );
  }

  /**
   *
   */
  public function getFinder(): iterable {
    return parent::getFinder()
      ->in(getcwd())
      ->files()
      ->name(['*.php', '.php_cs', '.php_cs.dist'])
      ->ignoreDotFiles(TRUE)
      ->ignoreVCS(TRUE)
      ->exclude([
        '.github',
        '.idea',
        'resource',
        'build',
        'benchmarks',
        'libraries',
        'node_modules',
        'var',
        'vendor',
        'tools',
      ]);
  }

  /**
   *
   */
  public function withRulesFromYaml(...$filenames) {
    $rules = array_merge(
      $this->getRules(),
      $this->getRulesFromFiles(...$filenames)
    );

    ksort($rules);

    $clone = clone $this;

    $clone->setRules($rules);

    return $clone;
  }

  /**
   * @param mixed ...$filenames
   */
  protected function getRulesFromFiles(...$filenames): array {
    $rules = [];

    foreach ($filenames as $filename) {
      $filename = realpath($filename);

      if ($filename === FALSE) {
        continue;
      }

      $parsed = (array) Yaml::parseFile($filename);
      $parsed['parameters'] = (array) $parsed['parameters'] + ['rules' => []];

      $rules = array_merge($rules, $parsed['parameters']['rules']);
    }

    return $rules;
  }

}
