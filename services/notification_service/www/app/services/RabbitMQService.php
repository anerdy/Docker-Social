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

    public function addNotifications(array $friendsIds)
    {
        $connection = new AMQPStreamConnection(
            rabbitmq,	#host - имя хоста, на котором запущен сервер RabbitMQ
            5672,       	#port - номер порта сервиса, по умолчанию - 5672
            'guest',    	#user - имя пользователя для соединения с сервером
            'guest'     	#password
        );
        $channel = $connection->channel();
        $exchange = 'test_exchange';

        # Create the exchange if it doesnt exist already.
        $channel->exchange_declare(
            $exchange, 
            'fanout', # type
            false,    # passive
            false,    # durable
            false     # auto_delete
        );

        foreach($friendsIds as $friendId) {
            /** @var $channel AMQPChannel */
            $queueName = 'notification'.$friendId;

            $channel->queue_declare(
                $queueName,	#queue name - Имя очереди может содержать до 255 байт UTF-8 символов
                false,      	#passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
                false,      	#durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
                false,      	#exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
                false       	#autodelete - очередь удаляется, когда отписывается последний подписчик
            );
            $channel->queue_bind($queueName, $exchange);

            $msg = new AMQPMessage('New notification', array('delivery_mode' => 2));
            $channel->basic_publish(
                $msg,       	#message
                '',         	#exchange
                $queueName 	#routing key
            );
        }

        $channel->close();
        $connection->close();

    }

    public function getNotifications($userId): int
    {
        $connection = new AMQPStreamConnection(
            rabbitmq,	#host
            5672,       	#port
            'guest',    	#user
            'guest'     	#password
        );

        $channel = $connection->channel();
        $queueName = 'notification'.$userId;
        
        $countNotifications = 0;
        try {
            $message = $channel->basic_get($queueName);
            while ($message !== null) {
                $message->ack();
            //    $messages[] = $message->body;
                $countNotifications++;
                sleep(1);
                $message = $channel->basic_get($queueName);
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

        return $countNotifications;
    }


}
