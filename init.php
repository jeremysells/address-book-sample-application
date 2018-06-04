<?php

declare(strict_types=1);

foreach (glob(__DIR__ . '/includes/*.php') as $file) {
    require_once $file;
}
