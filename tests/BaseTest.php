<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use GuzzleHttp\Client;

class BaseTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $doctrine;

    /**
     * @var GuzzleHttp\Client
     */
    protected $http;



    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->doctrine = $kernel->getContainer()
            ->get('doctrine');

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->http = new Client([
          'base_uri' => 'http://127.0.0.1:8000/',
          'headers' => [
            'Content-Type' => "application/json"
          ]
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * Does everything work fine?
     */
    public function testDoesItWork()
    {
      $this->assertTrue(true);
    }
}
