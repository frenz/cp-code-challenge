<?php

namespace App\Message;

final class QueueMessage
{
/*
 * Add whatever properties & methods you need to hold the
 * data for this message class.
 */

     private $token;

     public function __construct(string $token)
     {
         $this->token = $token;
     }

    public function getToken(): string
    {
        return $this->token;
    }
}
