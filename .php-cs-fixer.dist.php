<?php

use PhpCsFixerCustomFixers\Fixer\CommentSurroundedBySpacesFixer;
use PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer;
use PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer;
use PhpCsFixerCustomFixers\Fixer\MultilineCommentOpeningClosingAloneFixer;
use PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocNoSuperfluousParamFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamOrderFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer;
use PhpCsFixerCustomFixers\Fixer\PromotedConstructorPropertyFixer;

$excludes = [
    'vendor',
];

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->exclude($excludes);

$rules = [
    '@Symfony' => true,
    'phpdoc_to_comment' => false,
    'trailing_comma_in_multiline' => ['elements' => ['arrays', 'parameters']],
    'phpdoc_array_type' => true,
    'phpdoc_param_order' => true,
    CommentSurroundedBySpacesFixer::name() => true,
    ConstructorEmptyBracesFixer::name() => true,
    DeclareAfterOpeningTagFixer::name() => true,
    MultilineCommentOpeningClosingAloneFixer::name() => true,
    MultilinePromotedPropertiesFixer::name() => true,
    PhpdocNoSuperfluousParamFixer::name() => true,
    PhpdocTypesTrimFixer::name() => true,
    PromotedConstructorPropertyFixer::name() => true,
    MultilinePromotedPropertiesFixer::name() => true,
];

$config = new PhpCsFixer\Config();
return $config
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules($rules)
    ->setFinder($finder);