<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
                'posts' => $posts->findAllWithComments(),
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

        $form = $this->createForm(
            MicroPostType::class,
            new MicroPost()
        );



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
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
        name: 'app_micro_post_edit',
        // priority: 2
    )]

    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        MicroPost $post,
        Request $request,
        MicroPostRepository $posts,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(
            MicroPostType::class,
            $post
        );

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
            'micro_post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post
            ]
        );
    }

    #[Route(
        '/micro-post/{post}/comment',
        name: 'app_micro_post_comment',
        // priority: 2
    )]

    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(
        MicroPost $post,
        Request $request,
        CommentRepository $comments,
        EntityManagerInterface $entityManager
    ): Response {


        $form = $this->createForm(
            CommentType::class,
            new Comment()
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush(); // Save the changes to the database

            $this->addFlash(
                'success',
                'Your comment has been updated.'
            );

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            );
        }


        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post
            ]
        );
    }
}
