<?php
namespace Tests\Controllers;

use Tests\BaseTest;
use App\Controller\BloggersController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Blogger;

class BloggersControllerTest extends BaseTest
{

      protected $mockBlogger = [
         "name" => "unit_test",
         "email" => "unit_testunique@test.com",
         "username" => "unit_testunique",
         "rating" => 5
      ];

      public function getControllerInstance()
      {
        // passing in MOCK manager and MOCk doctrine
        return new BloggersController($this->entityManager, $this->doctrine);
      }

      public function getRepository()
      {
        // get the repository to mock
        return $this->entityManager->getRepository(Blogger::class);
      }

      public function testCreate()
      {
          $request = new Request([], $this->mockBlogger);
          $createdBlogger = $this->getControllerInstance()->create($request);

          $decodedBlogger = \json_decode($createdBlogger->getContent(), true);
          $this->assertEquals($decodedBlogger['email'], $this->mockBlogger['email']);
      }

      public function testIndex()
      {
        $request = new Request();
        $bloggers = \json_decode($this->getControllerInstance()->index($request)->getContent(), true);

        $this->assertTrue(\sizeof($bloggers) > 0);
      }

      public function testUpdate()
      {
        $request = new Request([], \array_merge($this->mockBlogger, ["name" => "update_name"]));
        $instance = $this->getRepository()->prepareEntity($this->mockBlogger, new Blogger());
        $updatedBlogger = $this->getControllerInstance()->update($instance, $request);

        $decodedBlogger = \json_decode($updatedBlogger->getContent(), true);
        $this->assertEquals($decodedBlogger['name'], "update_name");
      }

      public function testDelete()
      {
        $instance = $this->getRepository()->prepareEntity($this->mockBlogger, new Blogger());
        $deleteResponse = $this->getControllerInstance()->delete($instance);
        $this->assertEquals(\json_decode($deleteResponse->getContent()), "Blogger has been deleted");
      }

      public function testFind()
      {
        $instance = $this->getRepository()->prepareEntity($this->mockBlogger, new Blogger());
        $blogger = $this->getControllerInstance()->find($instance);

        $decodedBlogger = \json_decode($blogger->getContent(), true);
        $this->assertEquals($decodedBlogger['email'], $this->mockBlogger['email']);
      }
}
