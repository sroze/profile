<?php

namespace Profile;

use Symfony\Component\HttpKernel\Profiler\Profile;

interface ProfileRepository
{
    /**
     * @param Profile $profile
     */
    public function save(Profile $profile);

    /**
     * @param array $criteria
     * @param int $limit
     * @return Profile[]
     */
    public function findBy(array $criteria, $limit);

    /**
     * @param string $token
     * @return Profile
     */
    public function find($token);
}
