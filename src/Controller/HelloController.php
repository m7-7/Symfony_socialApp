<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2024/10/12'],
        ['message' => 'Hi', 'created' => '2024/11/12'],
        ['message' => 'Bye', 'created' => '2023/05/12']
    ];

    #[Route('/', name: 'app_index')]
public function index(UserProfileRepository $profiles, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    // $email = 'email@email.com';

    // // Check if the email already exists in the database
    // $existingUser = $userRepository->findOneBy(['email' => $email]);

    // if ($existingUser) {
    //     // Optionally, handle the case where the user already exists (e.g., show an error, redirect, etc.)
    //     return $this->render('hello/index.html.twig', [
    //         'messages' => $this->messages,
    //         'limit' => 3,
    //         'error' => 'Email already exists!',
    //     ]);
    // }

    // // Create a new User object if the email doesn't exist
    // $user = new User();
    // $user->setEmail($email);
    // $user->setPassword('123456789'); // Remember to hash the password!

    // // Create and associate the UserProfile with the User
    // $profile = new UserProfile();
    // $profile->setUser($user);

    // // Persist the User and UserProfile to the database
    // $entityManager->persist($user);
    // $entityManager->persist($profile);

    // // Flush to save the changes
    // $entityManager->flush();

    // $profile = $profiles->find(1);
    // $profiles->remove($profile, true);

    // Return the response
    return $this->render('hello/index.html.twig', [
        'messages' => $this->messages,
        'limit' => 3,
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
