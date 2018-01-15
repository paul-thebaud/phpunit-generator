<?php

return [
    // Erase old target files with new one
    'overwrite' => false,
    // If you want to generate tests for Interface too
    'interface' => false,
    // Automatically generate tests for getter / setter method
    'auto'      => true,
    // Ignore errors that can be ignored
    'ignore'    => true,
    // Regex (/.*config.php/ for example) that files must not match to have a tests generation
    'exclude'   => '/.*config\.php$/',
    // Regex (/.*.php/ for example) that files should match to have a tests generation
    'include'   => '/.*\.php$/',
    // Directories to generate tests for
    'dirs'      => [
        'source/dir/to/parse' => 'target/dir/to/put/generated/files'
    ],
    // Files to generate tests for
    'files'     => [
        'source/file/to/parse' => 'target/file/to/put/generated/file'
    ],
    // Phpdoc annotations to add on the unit tests class header
    'phpdoc'    => [
        'author'    => 'John Doe <john.doe@example.com>.',
        'copyright' => '2017-2018 John Doe <john.doe@example.com>.',
        'license'   => 'https://opensource.org/licenses/MIT The MIT license.',
        'link'      => 'https://github.com/john-doe/my-awesome-project',
        'since'     => 'File available since Release 1.0.0'
    ]
];