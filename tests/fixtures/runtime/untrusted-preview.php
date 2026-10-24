<?php

// Benign fixture only: the regression copies this into a unique temporary directory.
file_put_contents(__DIR__.'/executed.txt', 'customer code executed');
echo 'UNTRUSTED_PHP_FIXTURE_OUTPUT';
