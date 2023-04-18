<?php
require __DIR__ . '/../vendor/autoload.php';
if (file_exists(__DIR__ . "/../.env")) {
    $dotenv = Dotenv\Dotenv::create(__DIR__ . "/../");
    $dotenv->load();
}

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    $jsonBody = json_decode($request->getBody(), true);
    $actionUrl = "";
    $message = "";
    $signatureKey = getenv("SIGNATURE_KEY") ?? "";
    echo date("Y-m-d H:i:s") . " ";
    if (isset($jsonBody['actionUrl'])) {
        $actionUrl = $jsonBody['actionUrl'];
        $status = true;
        $connector = new React\Socket\Connector(array(
            'tls' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        ));
        $client = new React\Http\Browser($connector);
        $client->post($actionUrl, [
            'Content-type' => 'application/json',
            'X-Token' => sha1($signatureKey . $request->getBody())
        ], $request->getBody())->then(function ($response) {
            echo $response->getBody() . PHP_EOL;
        }, function ($exception) {
            echo $exception->getMessage() . PHP_EOL;
        });
    } else {
        $status = false;
        $message = "Missing action url";
    }
    return React\Http\Message\Response::json([
        'status' => $status,
        'actionUrl' => $actionUrl,
        'message' => $message,
    ]);
});
$port = getenv("PORT_TASK_RUNNER") ?? 3000;
$socket = new React\Socket\SocketServer('127.0.0.1:' . $port);
$http->listen($socket);

echo "Server running at http://127.0.0.1:{$port}" . PHP_EOL;
