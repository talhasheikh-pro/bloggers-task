<?php
namespace Tests\Controllers;

use Tests\BaseTest;

class BloggersControllerIntegrationTest extends BaseTest
{

  // pre-requisite: fresh database is being used for testing, else this id would need to be updated
  protected $stubBloggerId = 8;

  public function testIndex() :void
  {
    // get list of bloggers
    $bloggers = $this->http->request('GET', '/bloggers');
    $decodedBloggers = \json_decode($bloggers->getBody()->getContents(), true);

    // did it return something?
    $this->assertTrue(\sizeof($decodedBloggers) > 0);
  }

  public function testCreate() :array
  {
      $name = "integration_test_user" . time();
      $mockBlogger = [
         "name" => $name,
         "email" => "{$name}@test.com",
         "username" => $name,
         "rating" => 5
      ];

      $prevSize = \sizeof(\json_decode($this->http->request('GET', '/bloggers')->getBody()->getContents(), true));

      // create blogger
      $blogger = $this->http->request('POST', '/bloggers', [
        "json" => $mockBlogger
      ]);

      // does creating increment list count?
      $newSize = \sizeof(\json_decode($this->http->request('GET', '/bloggers')->getBody()->getContents(), true));
      $this->assertEquals($prevSize+1, $newSize);

      return \json_decode($blogger->getBody()->getContents(), true);
  }

  public function testFind() :void
  {
    $blogger = $this->http->request('GET', "/bloggers/{$this->stubBloggerId}");
    $decodedBlogger = \json_decode($blogger->getBody()->getContents(), true);
    $this->assertEquals($decodedBlogger['name'], "mickey-{$this->stubBloggerId}");
  }

  public function testDelete()
  {
    sleep(2);
    $createdUserId = $this->testCreate()['id'];

    $deleteResponse = $this->http->request('DELETE', "/bloggers/{$createdUserId}");
    $this->assertEquals(\json_decode($deleteResponse->getBody()->getContents()), "Blogger has been deleted");
  }

  public function testUpdate() :void
  {
    sleep(2);
    $createdUser = $this->testCreate();
    $createdUserId = $createdUser['id'];

    $updatedUser = $this->http->request('PATCH', "/bloggers/{$createdUserId}", [
      "json" => [
        "name" => "updated_name_integration_test"
      ]
    ]);

    $this->assertEquals(\json_decode($updatedUser->getBody()->getContents(), true)['name'], "updated_name_integration_test");
  }

}
