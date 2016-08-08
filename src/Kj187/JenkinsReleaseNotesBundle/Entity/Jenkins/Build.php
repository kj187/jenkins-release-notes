<?php

namespace Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins;

class Build
{
    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var int
     */
    protected $number = 0;

    /**
     * @var string
     */
    protected $vcsBranchName = '';

    /**
     * @var string
     */
    protected $vcsCommitHash = '';

    /**
     * @var string
     */
    protected $vcsRemoteUrl = '';

    /**
     * @var string
     */
    protected $vcsUsername = '';

    /**
     * @var string
     */
    protected $vcsRepository = '';

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
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getVcsBranchName()
    {
        return $this->vcsBranchName;
    }

    /**
     * @param string $vcsBranchName
     */
    public function setVcsBranchName($vcsBranchName)
    {
        $this->vcsBranchName = $vcsBranchName;
    }

    /**
     * @return string
     */
    public function getVcsCommitHash()
    {
        return $this->vcsCommitHash;
    }

    /**
     * @param string $vcsCommitHash
     */
    public function setVcsCommitHash($vcsCommitHash)
    {
        $this->vcsCommitHash = $vcsCommitHash;
    }

    /**
     * @return string
     */
    public function getVcsRemoteUrl()
    {
        return $this->vcsRemoteUrl;
    }

    /**
     * @param string $vcsRemoteUrl
     */
    public function setVcsRemoteUrl($vcsRemoteUrl)
    {
        $this->vcsRemoteUrl = $vcsRemoteUrl;
    }

    /**
     * @return string
     */
    public function getVcsUsername()
    {
        return $this->vcsUsername;
    }

    /**
     * @param string $vcsUsername
     */
    public function setVcsUsername($vcsUsername)
    {
        $this->vcsUsername = $vcsUsername;
    }

    /**
     * @return string
     */
    public function getVcsRepository()
    {
        return $this->vcsRepository;
    }

    /**
     * @param string $vcsRepository
     */
    public function setVcsRepository($vcsRepository)
    {
        $this->vcsRepository = $vcsRepository;
    }
}
