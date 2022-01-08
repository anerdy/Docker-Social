<?php
namespace App\Services;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;
use App\Core\View;
use \Exception;
//use Acme\AmqpWrapper\SimpleSender;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;


class RabbitMQService
{
    function __construct()
    {
    }

    public function execute($userId, $message)
    {
        $connection = new AMQPConnection(
            rabbitmq,	#host - имя хоста, на котором запущен сервер RabbitMQ
            5672,       	#port - номер порта сервиса, по умолчанию - 5672
            'guest',    	#user - имя пользователя для соединения с сервером
            'guest'     	#password
        );

        /** @var $channel AMQPChannel */
        $channel = $connection->channel();

        $channel->queue_declare(
            'feed'.$userId,	#queue name - Имя очереди может содержать до 255 байт UTF-8 символов
            false,      	#passive - может использоваться для проверки того, инициирован ли обмен, без того, чтобы изменять состояние сервера
            true,      	#durable - убедимся, что RabbitMQ никогда не потеряет очередь при падении - очередь переживёт перезагрузку брокера
            false,      	#exclusive - используется только одним соединением, и очередь будет удалена при закрытии соединения
            false       	#autodelete - очередь удаляется, когда отписывается последний подписчик
        );

        $msg = new AMQPMessage($message, array('delivery_mode' => 2));

        $channel->basic_publish(
            $msg,       	#message
            '',         	#exchange
            'feed'.$userId 	#routing key
        );

        $channel->close();
        $connection->close();

    }

    public function listen($userId)
    {
        $connection = new AMQPConnection(
            rabbitmq,	#host
            5672,       	#port
            'guest',    	#user
            'guest'     	#password
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            'feed'.$userId,	#имя очереди, такое же, как и у отправителя
            false,      	#пассивный
            true,      	#надёжный
            false,      	#эксклюзивный
            false       	#автоудаление
        );

        $callback = function ($msg) {
            var_dump($msg);
            echo ' [x] Received ', $msg->body, "\n";
        //    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };


   //     $result = $channel->basic_get('feed'.$userId, true);
     //   var_dump($result);
  //      die;

      //  $msg = $ch->basic_get($queue);


        $result = $channel->basic_consume(
            'feed'.$userId,                	#очередь
            '',                         	#тег получателя - Идентификатор получателя, валидный в пределах текущего канала. Просто строка
            false,                      	#не локальный - TRUE: сервер не будет отправлять сообщения соединениям, которые сам опубликовал
            false,                       	#без подтверждения - отправлять соответствующее подтверждение обработчику, как только задача будет выполнена
            false,                      	#эксклюзивная - к очереди можно получить доступ только в рамках текущего соединения
            false,                      	#не ждать - TRUE: сервер не будет отвечать методу. Клиент не должен ждать ответа
            $callback  // array($this, 'processOrder')	#функция обратного вызова - метод, который будет принимать сообщение
        );

     //   var_dump($result);die;

        foreach ($channel->callbacks as $item) {
            //$item->
            var_dump($item);
        }

//
  //      while (count($channel->callbacks)) {
     //       $channel->wait();
    //    }


        $channel->close();
        $connection->close();
    }


    public function processOrder($msg)
    {
        var_dump($msg);
        echo ' [x] Received ', $msg->body, "\n";
        /* ... КОД ОБРАБОТКИ ЗАКАЗА ... */
    }

}