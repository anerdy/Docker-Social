<?php
namespace App\Services;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;


class RabbitMQService
{
    function __construct()
    {
    }

    public function addPosts(array $friendsIds, string $message)
    {
        $connection = new AMQPStreamConnection(
            rabbitmq,	#host - имя хоста, на котором запущен сервер RabbitMQ
            5672,       	#port - номер порта сервиса, по умолчанию - 5672
            'guest',    	#user - имя пользователя для соединения с сервером
            'guest'     	#password
        );

        foreach($friendsIds as $friendId) {
            /** @var $channel AMQPChannel */
            $channel = $connection->channel();

            $channel->queue_declare(
                'feed'.$friendId,	#queue name - Имя очереди может содержать до 255 байт UTF-8 символов
                false,      	#passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
                true,      	#durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
                false,      	#exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
                false       	#autodelete - очередь удаляется, когда отписывается последний подписчик
            );

            $msg = new AMQPMessage($message, array('delivery_mode' => 2));
            $channel->basic_publish(
                $msg,       	#message
                '',         	#exchange
                'feed'.$friendId 	#routing key
            );

            $channel->close();
        }

        $connection->close();

    }

    public function getPosts($userId): array
    {
        $connection = new AMQPStreamConnection(
            rabbitmq,	#host
            5672,       	#port
            'guest',    	#user
            'guest'     	#password
        );

        $channel = $connection->channel();

        $messages = [];
        
        try {
            $message = $channel->basic_get('feed'.$userId);
            while ($message !== null) {
                $message->ack();
                $messages[] = $message->body;
                sleep(1);
                $message = $channel->basic_get('feed'.$userId);
            }
        } catch (Exception $e) {
            if ($e->getCode() != 404) {
                die('Ошибка Redis: '.$e->getMessage());
            } else {
                // queue not found
            }
        }
        

        $channel->close();
        $connection->close();

        return $messages;
    }


}
