<?php

namespace Lkn\BBPix\Helpers;

use stdClass;

final class Logger
{
    public static function log(string $result, array|string|object $request, string|bool|array|stdClass $response = []): void
    {
        if (Config::setting('enable_logs')) {
            $log = ['request' => $request];

            if (!empty($response)) {
                $log['response'] = $response;
            }

            logTransaction(
                'lknbbpix',
                json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $result
            );
        }
    }
}
