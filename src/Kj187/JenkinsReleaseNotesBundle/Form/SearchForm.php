<?php

namespace Kj187\JenkinsReleaseNotesBundle\Form;

use Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use \Kj187\JenkinsReleaseNotesBundle\Repository\JenkinsRepository;

class SearchForm extends AbstractType
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var Request
     */
    protected $request = null;

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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->request = $options['data']['request'];
        $this->configuration = $options['data']['configuration'];
        $jobs = $this->getJenkinsRepository()->findJobs();

        $builder->add('job', ChoiceType::class,
            [
                'choices' => $jobs,
                'choice_label' => function ($value) {
                    return $value->getName();
                },
                'preferred_choices' => function ($val, $key) {
                    if ($val->getName() == $this->configuration['jenkins']['defaultJob']) {
                        return true;
                    }
                }
            ]
        )
        ->add('build_from', HiddenType::class)
        ->add('build_to', HiddenType::class)
        ->add('submit', SubmitType::class,
            [
                'label' => 'next',
                'attr' => ['class' => 'btn btn-primary']
            ]
        );

        $formModifier = function(FormInterface $form, Job $job = null) {
            $job = $this->getJenkinsRepository()->findJob($job);


            $form->add('build_from', ChoiceType::class,
                [
                    'choices' => $job->getBuilds(),
                    'label' => 'newest build',
                    'choice_label' => function ($value) {
                        return $value->getNumber();
                    }
                ]
            )
            ->add('build_to', ChoiceType::class,
                [
                    'choices' => $job->getBuilds(),
                    'label' => 'old build',
                    'choice_label' => function ($value) {
                        return $value->getNumber();
                    }
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Generate release notes'
                ]
            )
            ;
        };

        $builder->get('job')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $job = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $job);
            }
        );
    }
}
