<?php

declare(strict_types=1);

namespace App\Service\Entity;

use Doctrine\ORM\EntityManagerInterface;

class EntityService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(object $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete(object $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
