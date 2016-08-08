<?php

namespace Kj187\JenkinsReleaseNotesBundle\Entity\Github;

class Commit
{
    /**
     * @var string
     */
    protected $sha = '';

    /**
     * @var string
     */
    protected $message = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var \DateTime
     */
    protected $date = null;

    /**
     * @var string
     */
    protected $author = '';

    /**
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * @param string $sha
     */
    public function setSha($sha)
    {
        $this->sha = $sha;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}
