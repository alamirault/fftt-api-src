<?php

use PhpCsFixer\Config;
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
use PhpCsFixerCustomFixers\Fixer\ReadonlyPromotedPropertiesFixer;

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
    CommentSurroundedBySpacesFixer::name() => true,
    ConstructorEmptyBracesFixer::name() => true,
    DeclareAfterOpeningTagFixer::name() => true,
    MultilineCommentOpeningClosingAloneFixer::name() => true,
    MultilinePromotedPropertiesFixer::name() => true,
    MultilinePromotedPropertiesFixer::name() => true,
    PhpdocArrayStyleFixer::name() => true,
    PhpdocNoSuperfluousParamFixer::name() => true,
    PhpdocParamOrderFixer::name() => true,
    PhpdocTypesTrimFixer::name() => true,
    PromotedConstructorPropertyFixer::name() => true,
//    '@PSR2' => true,
//    'array_syntax' => ['syntax' => 'short'],
//    'no_useless_else' => true,
//    'yoda_style' => false,
//    'function_declaration' => [
//        'closure_function_spacing' => 'none' //No space before function
//    ],
//    'braces' => false, //Disable due to it add space before parenthesis of if or foreach
//    'concat_space' => [
//        'spacing' => 'one'
//    ],
//    'phpdoc_align' => [
//        'align' => 'left'
//    ],
//    'single_blank_line_at_eof' => false,
];

$config = new PhpCsFixer\Config();
return $config
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules($rules)
    ->setFinder($finder);