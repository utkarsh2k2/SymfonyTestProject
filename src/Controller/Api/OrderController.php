<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\SonataUserUser;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 */
class OrderController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/orders", name="api_get_orders")
     */
    public function getOrdersApiAction(SerializerInterface $serializer)
    {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $data = $serializer->serialize($orders, 'json');
        return new Response($data);
    }

    /**
     * @Route("/get-token/{username}", name="get_token")
     */
    public function getTokenAction(JWTTokenManagerInterface $JWTManager, $username)
    {
        /**
         * @var SonataUserUser $user
         */
        $user = $this->getDoctrine()->getRepository(SonataUserUser::class)->findOneBy(['username' => $username]);
        $token = $user ? $JWTManager->create($user) : '';
        return new JsonResponse(['token' => $token]);
    }

    /**
     * @Route("/get-orders/{username}", name="get_orders")
     */
    public function getOrdersAction(JWTTokenManagerInterface $JWTManager, $username)
    {
        /**
         * @var SonataUserUser $user
         */
        $user = $this->getDoctrine()->getRepository(SonataUserUser::class)->findOneBy(['username' => $username]);
        $token = $JWTManager->create($user);
        //guzzle client
        $client = new Client();
        $res = $client->get($this->generateUrl('api_get_orders', [], 0), [
            'headers' => [ 'Authorization' => 'Bearer '.$token ]
        ]);
//        dump($res->getBody()->getContents());exit;
        return new Response($res->getBody()->getContents());
    }

}