<?php

namespace Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins;

use Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build;

class Job
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $color = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $displayName = '';

    /**
     * @var \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build[]
     */
    protected $builds = [];

    public function __construct()
    {
        $this->builds = [];
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return array
     */
    public function getBuilds()
    {
        return $this->builds;
    }

    /**
     * @param array $builds
     */
    public function setBuilds(array $builds)
    {
        $objectTransformer = new \Kj187\JenkinsReleaseNotesBundle\Service\ObjectTransformer();
        foreach ($builds as $build) {
            $build = $objectTransformer->transformArrayToObject($build, 'Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build');
            $this->addBuild($build);
        }
    }

    /**
     * @param Build $build
     */
    public function addBuild(Build $build)
    {
        $this->builds[] = $build;
    }
}
