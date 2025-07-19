<?php

namespace App\classes;

class FrontHelper
{
    public function simpleLoading(string $message, callable $callback): void
    {
        echo $message . ' ';
        $spinner = ['|', '/', '-', '\\'];
        $i = 0;

        ob_implicit_flush(true);
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
        $done = false;

        $callbackThread = function () use (&$done, $callback) {
            $callback();
            $done = true;
        };

        $start = microtime(true);
        $callbackThread();

        while (!$done || (microtime(true) - $start) < 1) {
            echo "\033[1D" . $spinner[$i++ % 4];
            flush();
            usleep(100000);
        }

        echo '<xmp>✔️ Done</xmp>';
    }
}
