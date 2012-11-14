#!/usr/bin/env php
<?php

require_once __DIR__ . '/../Markdown/Text.php';

use Markdown\Text;

if (@$_SERVER['argv'][1]) {
    if (is_readable($_SERVER['argv'][1]) && is_file($_SERVER['argv'][1])) {
        $md = file_get_contents($_SERVER['argv'][1]);
    }
    else {
        fwrite(STDERR, sprintf('Could not read file "%s" or not a regular file.' . PHP_EOL, $_SERVER['argv'][1]));
        exit(2);
    }
}
else {
    $md = stream_get_contents(STDIN);
}

echo new Text($md);
