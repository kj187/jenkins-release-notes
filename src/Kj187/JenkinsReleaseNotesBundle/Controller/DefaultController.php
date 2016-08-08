<?php

namespace Kj187\JenkinsReleaseNotesBundle\Controller;

use Kj187\JenkinsReleaseNotesBundle\Entity\Search;
use Kj187\JenkinsReleaseNotesBundle\Repository\JenkinsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var JenkinsRepository
     */
    protected $jenkinsRepository = null;

    /**
     * @return array
     */
    protected function getConfiguration()
    {
        if (empty($this->configuration)) {
            $this->configuration = $this->container->getParameter('jenkins_release_notes');
        }

        return $this->configuration;
    }

    /**
     * @return JenkinsRepository
     */
    protected function getJenkinsRepository()
    {
        if ($this->jenkinsRepository === null) {
            $configuration = $this->getConfiguration();
            $this->jenkinsRepository = new JenkinsRepository($configuration['jenkins']);
        }

        return $this->jenkinsRepository;
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $commits = [];
        $configuration = $this->getConfiguration();
        $form = $this->createForm(\Kj187\JenkinsReleaseNotesBundle\Form\SearchForm::class, [
            'request' => $request,
            'configuration' => $configuration
        ]);

        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            //dump($formData);

            if (isset($formData['build_from']) && isset($formData['build_to'])) {
                $job = $formData['job'];
                $job = $this->getJenkinsRepository()->findJob($job);

                /** @var \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build $buildFrom */
                $buildFrom = $this->getJenkinsRepository()->findBuild($job, $formData['build_from']);

                /** @var \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build $buildTo */
                $buildTo = $this->getJenkinsRepository()->findBuild($job, $formData['build_to']);

                if ($buildFrom->getNumber() >= $buildTo->getNumber()) {

                    if ($buildFrom->getNumber() == $buildTo->getNumber()) {
                        $this->addFlash('notice', '"Build from" and "Build to" must not be equals!');
                    } else {
                        $githubRepository = new \Kj187\JenkinsReleaseNotesBundle\Repository\GithubRepository($buildFrom->getVcsUsername(), $configuration['jenkins']['token'], $buildFrom->getVcsRepository());
                        $githubRepository->findCommitsByRange($buildFrom->getVcsCommitHash(), $buildTo->getVcsCommitHash(), $commits);
                    }
                } else {
                    $this->addFlash('notice', '"Build from" must be higher than "Build to"!');
                }
            }
        }

        return $this->render('Kj187JenkinsReleaseNotesBundle:Default:index.html.twig',
            [
                'form' => $form->createView(),
                'commits' => $commits
            ]
        );
    }
}
