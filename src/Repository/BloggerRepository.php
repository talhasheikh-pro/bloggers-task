<?php 
namespace App\Repository;

use App\Entity\Blogger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blogger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blogger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blogger[]    findAll()
 * @method Blogger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BloggerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blogger::class);
    }

    /**
     * Creates/Prepares the passed `Blogger` object for creation/updation process
     *
     * @param {array} $bloggerArgs - parameters for the blogger instance
     * @param {Blogger} $blogger - instance of App\Entity\Blogger
     */
    public function prepareEntity(array $bloggerArgs, Blogger $blogger)
    {
      try{
        // mandatory params
        if(isset($bloggerArgs['name'])) $blogger->setName($bloggerArgs['name']);
        if(isset($bloggerArgs['email'])) $blogger->setEmail($bloggerArgs['email']);
        if(isset($bloggerArgs['username'])) $blogger->setUsername($bloggerArgs['username']);

        // optional params while
        $blogger->setActive(1);
        $blogger->setRating($bloggerArgs['rating'] ?? 0);

        return $blogger;

      }catch(\Exception $e){
        throw new \Exception($e->getMessage());
      }
    }
}
