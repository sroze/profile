<?php

namespace Profile\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;
use Profile\Infrastructure\Doctrine\Entity\ProfileDto;
use Profile\ProfileRepository;
use Symfony\Component\HttpKernel\Profiler\Profile;

class DoctrineProfileRepository implements ProfileRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Profile $profile
     */
    public function save(Profile $profile)
    {
        $dto = new ProfileDto();
        $dto->token = $profile->getToken();
        $dto->serialized = base64_encode(serialize($profile));

        $this->entityManager->persist($dto);
        $this->entityManager->flush();
    }

    /**
     * @param array $criteria
     * @param int $limit
     * @return Profile[]
     */
    public function findBy(array $criteria, $limit)
    {
        return array_map(function(ProfileDto $dto) {
            return unserialize(base64_decode($dto->serialized));
        }, $this->getRepository()->findBy([], [], $limit));
    }

    /**
     * @param string $token
     * @return Profile
     */
    public function find($token)
    {
        if (null === ($dto = $this->getRepository()->find($token))) {
            throw new \RuntimeException('Not found');
        }

        return unserialize(base64_decode($dto->serialized));
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getRepository()
    {
        return $this->entityManager->getRepository(ProfileDto::class);
    }
}
