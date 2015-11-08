<?php

namespace ApiBundle\Controller;

use Profile\ProfileRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(service="api.controller.profile")
 */
class ProfileController
{
    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * @param ProfileRepository $profileRepository
     */
    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * @Route("/profile", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $profile = unserialize($request->getContent());

        $this->profileRepository->save($profile);

        return new Response(Response::HTTP_CREATED);
    }
}
