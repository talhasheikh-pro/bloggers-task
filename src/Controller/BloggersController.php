<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Blogger;
use App\Serializers\EntitySerializer;

class BloggersController extends AbstractController
{
    public function getRepository()
    {
      return $this->getDoctrine()->getRepository(Blogger::class);
    }

    /**
     * @Route("/bloggers", name="bloggers")
     */
    public function index()
    {
        $bloggers = $this->getRepository()->findAll();

        return new Response(
          EntitySerializer::serialize($bloggers),
          Response::HTTP_OK,
          [
            'Content-Type' => 'application/json'
          ]
        );
    }

    /**
     * @Route("/bloggers/{id}", name="blogger_find")
     */
    public function find(\App\Entity\Blogger $blogger = null)
    {
      if (!$blogger) {
        return new Response("Blogger with the provided id cannot be found", Response::HTTP_NOT_FOUND);
      }

      return new Response(
        EntitySerializer::serialize($blogger),
        Response::HTTP_OK
      );
    }

    /**
     * @Route("/bloggers", name="blogger_create")
     */
    public function create(array $args)
    {
        $blogger = $this->getRepository()->create($args);
        return $this->json($bloggers);
    }

    /**
     * @Route("/bloggers", name="blogger_update")
     */
    public function update(int $id, array $args)
    {
        $bloggers = $this->getRepository()->findAll();
        return new Response($this->json($bloggers), 200);
    }

    /**
     * @Route("/bloggers", name="blogger_delete")
     */
    public function delete()
    {
        $bloggers = $this->getRepository()->findAll();
        return $this->json($bloggers);
    }


}
