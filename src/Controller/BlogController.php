<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;


/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     * @param int $page
     * @param Request $request
     * @return JsonResponse
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);

        //Get data to database
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (BlogPost $item){
                    return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post){
        // It's a same as doing find($id) on repository
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     * The below annotation is not required when $post is typehinted with BlogPost
     * and route parameter name matches any field on the BlogPost entity
     * @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "slug"}})
     */
    public function postBySlug(BlogPost $post){
        // same as doing $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy(['slug' => $slug])
        return $this->json($post);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {

        /**
         * @var  Serializer $serializer
         */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     * @param BlogPost $post
     * @return JsonResponse
     */
    public function delete(BlogPost $post){
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
