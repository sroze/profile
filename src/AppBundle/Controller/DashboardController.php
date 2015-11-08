<?php

namespace AppBundle\Controller;

use Profile\ProfileRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(service="app.controller.dashboard")
 */
class DashboardController
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
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        return [
            'profiles' => $this->profileRepository->findBy([], 10),
        ];
    }
}
