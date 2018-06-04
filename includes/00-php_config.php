<?php
declare(strict_types=1);

// Report all errors
error_reporting(-1);

//---ON ERROR-------------------------------------------------------------------

//Errors are exceptions
set_error_handler(function (int $severity, string $message, string $filename, int $lineNo) {
    throw new ErrorException($message, 0, $severity, $filename, $lineNo);
});

//---MULTI BYTE-----------------------------------------------------------------

//Use UTF8
if (function_exists("mb_internal_encoding")) {
    mb_internal_encoding("UTF-8");
}
