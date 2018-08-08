<?php
/**
 * Created by PhpStorm.
 * User: Shevyakhov_K
 * Date: 25.07.2018
 * Time: 18:35
 */

namespace Application\Repository;


use Application\Entity\Testimonial;
use Doctrine\ORM\EntityRepository;

class TestimonialRepository extends EntityRepository
{
    public function findAllTestimonials()
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('u')
            ->from(Testimonial::class, 'u')
            ->orderBy('u.id');

        return $queryBuilder->getQuery();
    }
}