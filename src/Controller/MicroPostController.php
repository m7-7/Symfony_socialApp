<?php

namespace App\Controller;

use DateTime;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render(
            'micro_post/index.html.twig',
            [
                'posts' => $posts->findAll(),
            ]
        );
    }
    // #[Route('/micro-post/top-liked', name: 'app_micro_post_topliked')]
    // public function topLiked(MicroPostRepository $posts): Response
    // {
    //     return $this->render(
    //         'micro_post/top_liked.html.twig',
    //         [
    //             'posts' => $posts->findAllWithMinLikes(2),
    //         ]
    //     );
    // }

    // #[Route('/micro-post/follows', name: 'app_micro_post_follows')]
    // // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    // public function follows(MicroPostRepository $posts): Response
    // {
    //     /** @var User $currentUser */
    //     $currentUser = $this->getUser();

    //     return $this->render(
    //         'micro_post/follows.html.twig',
    //         [
    //             'posts' => $posts->findAllByAuthors(
    //                 $currentUser->getFollows()
    //             ),
    //         ]
    //     );
    // }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    // #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response
    {

        return $this->render(
            'micro_post/show.html.twig',
            [
                'post' => $post,
            ]
        );
    }

    #[Route(
        '/micro-post/add',
        name: 'app_micro_post_add',
        priority: 2
    )]

    // #[IsGranted('ROLE_WRITER')]
    public function add(
        Request $request,
        MicroPostRepository $posts,
        EntityManagerInterface $entityManager
    ): Response {

        $microPost = new MicroPost();
        $form = $this->createFormBuilder($microPost)
        ->add('title', TextType::class, [
            'label' => 'Post Title',  // Custom label
            // 'attr' => ['placeholder' => 'Enter a title for your post'], // HTML attributes
        ])
        ->add('text', TextType::class, [
            'label' => 'Post Content',
            // 'attr' => ['placeholder' => 'Write the content here'],
        ])
        
        ->getForm();
    


        //     $form = $this->createForm(
        //         MicroPostType::class,
        //         new MicroPost()
        //     );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();
                $post->setCreated(new DateTime());
            
                $entityManager->persist($post);
                $entityManager->flush(); // Save the changes to the database
            
                $this->addFlash(
                    'success',
                    'Your micro post has been added.'
                );
            
                return $this->redirectToRoute('app_micro_post'); 
            }
            

            return $this->render(
                'micro_post/add.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
            
    }

    #[Route(
        '/micro-post/{post}/edit',
        name: 'edit',
        // priority: 2
    )]

    // #[IsGranted('ROLE_WRITER')]
    public function edit(
        MicroPost $post,
        Request $request,
        MicroPostRepository $posts,
        EntityManagerInterface $entityManager
    ): Response {

        // $microPost = new MicroPost();
        $form = $this->createFormBuilder($post)
        ->add('title', TextType::class, [
            'label' => 'Post Title',  // Custom label
            // 'attr' => ['placeholder' => 'Enter a title for your post'], // HTML attributes
        ])
        ->add('text', TextType::class, [
            'label' => 'Post Content',
            // 'attr' => ['placeholder' => 'Write the content here'],
        ])
        
        ->getForm();
    


        //     $form = $this->createForm(
        //         MicroPostType::class,
        //         new MicroPost()
        //     );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $post = $form->getData();
                // $post->setCreated(new DateTime());
            
                $entityManager->persist($post);
                $entityManager->flush(); // Save the changes to the database
            
                $this->addFlash(
                    'success',
                    'Your micro post has been updated.'
                );
            
                return $this->redirectToRoute('app_micro_post'); 
            }
            

            return $this->render(
                'micro_post/add.html.twig',
                [
                    'form' => $form->createView()
                ]
            );
            
    }
}
