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
    'exclude'   => '/.*config.php/',
    // Regex (/.*.php/ for example) that files should match to have a tests generation
    'include'   => '/.*.php/',
    // Directories and files to generate tests for
    'dirs'      => [],
    // Files to generate tests for
    'files'     => [],
    // Phpdoc annotations to add on the unit tests class header
    'phpdoc'    => []
];