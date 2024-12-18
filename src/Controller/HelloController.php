<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HelloController extends AbstractController
{

    private array $messages = [
        ['message' => 'Hello', 'created' => '2024/10/12'],
        ['message' => 'Hi', 'created' => '2024/11/12'],
        ['message' => 'Bye', 'created' => '2023/05/12']
    ];

    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit)
    {

        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]);
        
    }

    #[Route('/messages/{id<\d+>}', 'app_show_one')]
    public function showOne($id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    }
}
