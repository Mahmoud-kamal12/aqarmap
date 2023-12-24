<?php

namespace App\Controller\Api;


use App\Helper\Helper;
use App\Service\PostService;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class PostController extends AbstractController
{

    private $postService;
    private $serializer;
    private $validator;
    private $eventDispatcher;


    public function __construct(PostService $postService , SerializerInterface $serializer , ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher)
    {
        $this->postService = $postService;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;

    }

    #[Route('/posts/index', name: 'posts_index',methods: 'get')]
    public function index()
    {

        try {
            $posts = $this->postService->findAll();
            return Helper::Response(data:$posts,msg: "response successfully" , status: 200);
        }catch (\Exception $exception ){
            return Helper::Response(data: [] ,msg: "error when retrieve data" , status: 500);
        }

    }

    #[Route('/posts/create', name: 'posts_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        try {

            $violations = $this->validator->validate(
                $request->request->all(),$this->validationRoles());

            if (count($violations) > 0) {
                return Helper::Response(data: [] ,msg: "complete your data" , status: 500);
            }

            $post = $this->postService->createPost(
                $request->request->get('title') ?? '',
                $request->request->get('description') ?? '',
                $request->request->get('schedule_date') ?? null,
            );

            return Helper::Response(data:$post,msg: "post created successfully" , status: 200);
        }catch (\Exception $exception ){
            return Helper::Response(data: [] ,msg: "error when create post" , status: 500);
        }
    }

    #[Route('/posts/show/{id}', name: 'posts_show',methods: 'get')]
    public function show($id)
    {
        try {
            $post = $this->postService->find($id);
            return Helper::Response(data:$post,msg: "post created successfully" , status: 200);
        }catch (\Exception $exception ){
            return Helper::Response(data: [] ,msg: "error when retrieve post" , status: 500);
        }
    }

    #[Route('/posts/update/{id}', name: 'posts_update', methods:['put'] )]
    public function update(ManagerRegistry $doctrine, Request $request , $id): JsonResponse
    {
        try {
            $violations = $this->validator->validate(
                $request->request->all(),$this->validationRoles());
            if (count($violations) > 0) {
                return Helper::Response(data: [] ,msg: "complete your data" , status: 500);
            }
            $post = $this->postService->updatePost($id,
                $request->request->get('title') ?? '',
                $request->request->get('description') ?? '',
                $request->request->get('schedule_date') ?? null,
            );
            return Helper::Response(data:$post,msg: "post updated successfully" , status: 200);
        }catch (\Exception $exception ){
            return Helper::Response(data: [] ,msg: "error when create post" , status: 500);
        }
    }

    #[Route('/posts/delete/{id}', name: 'posts_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, Request $request , $id): JsonResponse
    {
        try {
            $post = $this->postService->deletePost($id);
            return Helper::Response(data:$post,msg: "post deleted successfully" , status: 200);
        }catch (\Exception $exception ){
            return Helper::Response(data: [] ,msg: $exception->getMessage() , status: 500);
        }
    }

    public function validationRoles(): Assert\Collection
    {
        return new Assert\Collection([
            'title' => new Assert\NotBlank(),
            'description' => new Assert\NotBlank(),
            'schedule_date' => new Assert\Optional([
                new Assert\DateTime(),
            ]),
        ]);
    }

}
