<?php

namespace App\MessageHandler;

use App\Message\QueueMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QueueMessageHandler implements MessageHandlerInterface
{
    public function __invoke(QueueMessage $message)
    {
        var_dump($message->getName());
        die;
    }
}
