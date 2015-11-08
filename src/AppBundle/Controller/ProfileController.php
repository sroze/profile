<?php

namespace AppBundle\Controller;

use Profile\ProfileRepository;
use Symfony\Bundle\WebProfilerBundle\Profiler\TemplateManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route(service="app.controller.profile")
 */
class ProfileController
{
    /**
     * @var ProfileRepository
     */
    private $profileRepository;
    /**
     * @var TemplateManager
     */
    private $templateManager;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param ProfileRepository $profileRepository
     * @param TemplateManager $templateManager
     * @param \Twig_Environment $twig
     */
    public function __construct(ProfileRepository $profileRepository, TemplateManager $templateManager, \Twig_Environment $twig)
    {
        $this->profileRepository = $profileRepository;
        $this->templateManager = $templateManager;
        $this->twig = $twig;
    }

    /**
     * @Route("/profile/{token}", methods={"GET"}, name="profile")
     */
    public function panelAction(Request $request, $token)
    {
        $panel = $request->query->get('panel', 'request');
        $page = $request->query->get('page', 'home');

        $profile = $this->profileRepository->find($token);

        if (!$profile->hasCollector($panel)) {
            throw new NotFoundHttpException(sprintf('Panel "%s" is not available for token "%s".', $panel, $token));
        }

        return new Response($this->twig->render($this->templateManager->getName($profile, $panel), array(
            'token' => $token,
            'profile' => $profile,
            'collector' => $profile->getCollector($panel),
            'panel' => $panel,
            'page' => $page,
            'request' => $request,
            'templates' => $this->templateManager->getTemplates($profile),
            'is_ajax' => $request->isXmlHttpRequest(),
            'profiler_markup_version' => 2, // 1 = original profiler, 2 = Symfony 2.8+ profiler
        )), 200, array('Content-Type' => 'text/html'));
    }
}
