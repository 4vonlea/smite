<?php
if (!function_exists("run_job")) {
    function run_job($controller, $function, $params = null, $isNonBlocking = true)
    {
        $cmd = "";
        $formatParams = "";
        if (is_array($params)) {
            $formatParams = '"' . implode('" "', $params) . '"';
        } elseif (is_string($params)) {
            $formatParams = $params;
        }
        $cmd = "php index.php $controller $function " . $formatParams;
        if ($isNonBlocking) {
            if (substr(php_uname(), 0, 7) == "Windows") {
                pclose(popen("start /B " . $cmd, "r"));
            } else {
                $filelog = $controller.$function.".html";
                exec($cmd . " > ./application/logs/$filelog &");
            }
            return true;
        } else {
            exec($cmd,$output);
            return $output;
        }
    }
}
if (!function_exists("async_request")) {
    function async_request(string $url, array $params = [], string $method = "post", bool $jsonBody = true): void
    {
        // url check
        $parts = parse_url($url);
        if ($parts === false)
            throw new Exception('Unable to parse URL');
        $host = $parts['host'] ?? null;
        $port = $parts['port'] ?? 80;
        $path = $parts['path'] ?? '/';
        $query = $parts['query'] ?? '';
        parse_str($query, $queryParts);

        if ($host === null)
            throw new Exception('Unknown host');
        $connection = fsockopen($host, $port, $errno, $errstr, 30);
        if ($connection === false)
            throw new Exception('Unable to connect to ' . $host);
        $method = strtoupper($method);

        if (!in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $queryParts = $params + $queryParts;
            $params = [];
        }

        // Build request
        $request  = $method . ' ' . $path;
        if ($queryParts) {
            $request .= '?' . http_build_query($queryParts);
        }
        $request .= ' HTTP/1.1' . "\r\n";
        $request .= 'Host: ' . $host . "\r\n";

        if ($jsonBody) {
            $body = json_encode($params);
            $request .= 'Content-Type: application/json' . "\r\n";
            $request .= 'Content-Length: ' . strlen($body) . "\r\n";
        } else {
            $body = http_build_query($params);
            if ($body) {
                $request .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
                $request .= 'Content-Length: ' . strlen($body) . "\r\n";
            }
        }
        $request .= 'Connection: Close' . "\r\n\r\n";
        $request .= $body;

        // Send request to server
        stream_set_blocking($connection, false);
        fwrite($connection, $request);
        fclose($connection);
    }
}
