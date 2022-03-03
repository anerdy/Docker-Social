<?php
// start from project root
// php www/app/commands/ws.php start
ini_set('display_errors', 1);
require_once 'www/config.php';
require_once 'www/vendor/autoload.php';
require_once 'www/app/core/database.php';
require_once 'www/app/services/RabbitMQService.php';

use Workerman\Worker;
use App\Services\RabbitMQService;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$rabbitMQService = new RabbitMQService();
$ws_worker = new Worker('websocket://127.0.0.1:61523');
// 4 processes
$ws_worker->count = 4;

// Emitted when new connection come
$ws_worker->onConnect = function ($connection) {
   //  $connection->send('This message was sent, when server was started.');
    echo "New connection\n";
};

// Emitted when data received
$ws_worker->onMessage = function ($connection, $data) use ($rabbitMQService) {
    // if, server got message from frontend, server send message to Frontend $data
    $rabbitMQService->consumePosts($data, $connection);
};

// Emitted when connection closed
$ws_worker->onClose = function ($connection) {
    echo "Connection closed\n";
};

// Run worker
Worker::runAll();
