<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blogger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Helpers\HttpResponse;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Criteria;
use App\Repository\BloggerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;

class BloggersController extends AbstractController
{

    private $doctrineRegistry = null;
    private $entityManager = null;

    public function __construct(EntityManager $entityManager=null, Registry $doctrineRegistry=null)
    {
      $this->entityManager = $entityManager;
      $this->doctrineRegistry = $doctrineRegistry;
    }

    /**
     * Gets the Model's repository
     */
    public function getRepository() :BloggerRepository
    {
        return $this->doctrineRegistry != null
                ? $this->doctrineRegistry->getRepository(Blogger::class)
                : $this->getDoctrine()->getRepository(Blogger::class);
    }

    /**
     * Gets the Doctrine getManager
     */
    public function getManager() :EntityManager
    {
        return $this->entityManager != null
                ? $this->entityManager
                : $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/bloggers", name="bloggers", methods={"GET"})
     */
    public function index(Request $request) :Response
    {
        $criteria = new Criteria();

        if (\sizeof($request->request->all())) {
            foreach ($request->request->all() as $key => $value) {
                $criteria->orWhere(new Comparison($key, Comparison::CONTAINS, $value));
            }
        }

        $bloggers = $this->getRepository()->matching($criteria);
        return HttpResponse::send($bloggers);
    }

    /**
     * @Route("/bloggers/{id}", name="blogger_find", methods={"GET"})
     */
    public function find(Blogger $blogger = null) :Response
    {
        if (!$blogger) {
            return HttpResponse::send("Blogger with the provided id cannot be found", Response::HTTP_NOT_FOUND, false);
        }


        return HttpResponse::send($blogger);
    }

    /**
     * @Route("/bloggers", name="blogger_create", methods={"POST"})
     */
    public function create(Request $request) :Response
    {
        try {
            $blogger = $this->getRepository()->prepareEntity($request->request->all(), new Blogger());
            $this->persistBlogger($blogger);

            return HttpResponse::send($blogger);
        } catch (\Exception $e) {
            return HttpResponse::send($e->getMessage(), Response::HTTP_BAD_REQUEST, false);
        }
    }

    public function persistBlogger(Blogger $blogger) :?bool
    {
        try {
            $entityManager = $this->getManager();
            $entityManager->persist($blogger);
            $entityManager->flush();

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @Route("/bloggers/{id}", name="blogger_update", methods={"PATCH"})
     */
    public function update(Blogger $blogger = null, Request $request) :Response
    {
        try {
            if (!$blogger) {
                return HttpResponse::send(
                    "Blogger with the provided id cannot be found",
                    Response::HTTP_NOT_FOUND,
                    false
                );
            }

            $blogger = $this->getRepository()->prepareEntity($request->request->all(), $blogger);
            $this->persistBlogger($blogger);

            return HttpResponse::send($blogger);
        } catch (\Exception $e) {
            return HttpResponse::send($e->getMessage(), Response::HTTP_BAD_REQUEST, false);
        }
    }

    /**
     * @Route("/bloggers/{id}", name="blogger_delete", methods={"DELETE"})
     */
    public function delete(Blogger $blogger = null) :Response
    {
        try {
            if (!$blogger) {
                return HttpResponse::send(
                    "Blogger with the provided id cannot be found",
                    Response::HTTP_NOT_FOUND,
                    false
                );
            }

            $entityManager = $this->getManager();
            $entityManager->remove($blogger);
            $entityManager->flush();

            return HttpResponse::send("Blogger has been deleted");
        } catch (\Exception $e) {
            return HttpResponse::send($e->getMessage(), Response::HTTP_BAD_REQUEST, false);
        }
    }
}
