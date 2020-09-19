<?php declare(strict_types=1);

namespace App\Controller;
use Symfony\Component\Messenger\Exception\TransportException;
use App\Message\QueueTokenMessage;
use App\Service\JWTProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/queue/enter", name="queue_enter", methods={"GET"}))
 */
class QueueEnterController extends AbstractController
{
    const SERVER_ERROR = 'Server error';
    private MessageBusInterface $messageBus;
    private JWTProvider $JWTProvider;

    public function __construct(MessageBusInterface $messageBus, JWTProvider $JWTProvider)
    {
        $this->messageBus = $messageBus;
        $this->JWTProvider = $JWTProvider;
    }

    public function __invoke(): JsonResponse
    {
        $token = $this->JWTProvider->createQueueToken();
        try{
            $this->messageBus->dispatch(new QueueTokenMessage($token));
        }catch (TransportException $e){
            return $this->json(['error' => self::SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json(['queue_token' => $token]);
    }

}
