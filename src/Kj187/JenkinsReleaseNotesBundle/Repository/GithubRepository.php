<?php

namespace Kj187\JenkinsReleaseNotesBundle\Repository;

use \Kj187\JenkinsReleaseNotesBundle\Service\ObjectTransformer;
use \Kj187\JenkinsReleaseNotesBundle\Entity\Github\Commit;

class GithubRepository
{
    /**
     * @var \Github\Client
     */
    protected $client = null;

    /**
     * @var ObjectTransformer
     */
    protected $objectTransformer = null;

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $repository = '';

    /**
     * @param string $username
     * @param string $token
     * @param string $repository
     */
    public function __construct($username, $token, $repository)
    {
        $this->client = new \Github\Client();
        $this->client->authenticate($token, null, \Github\Client::AUTH_HTTP_TOKEN);

        $this->username = $username;
        $this->repository = $repository;

        $this->objectTransformer = new \Kj187\JenkinsReleaseNotesBundle\Service\ObjectTransformer();
    }

    /**
     * @param string $startSha
     * @param string $endSha
     * @param array $commits
     */
    public function findCommitsByRange($startSha, $endSha, &$commits)
    {
        /** @var \Github\Api\Repo $repository */
        $repository = $this->client->api('repo');

        $results = $repository->commits()->setPerPage(100)->all($this->username, $this->repository, ['sha' => $startSha]);
        $recursion = true;

        foreach ($results as $result) {
            if (
                strstr($result['commit']['message'], 'Merge branch') ||
                strstr($result['commit']['message'], 'Merge pull request')
            ) {
                continue;
            }

            /** @var \Kj187\JenkinsReleaseNotesBundle\Entity\Github\Commit $commit */
            $commit = $this->objectTransformer->transformArrayToObject($result, 'Kj187\JenkinsReleaseNotesBundle\Entity\Github\Commit');
            $commit->setMessage($result['commit']['message']);
            $commit->setDate(new \DateTime($result['commit']['author']['date']));
            $commit->setUrl($result['html_url']);
            $commit->setAuthor($result['commit']['author']['name']);
            $commits[] = $commit;

            if ($result['sha'] == $endSha) {
                $recursion = false;
                break;
            }
        }

        if ($recursion === true) {
            $this->findCommitsByRange($result['sha'], $endSha, $commits);
        }
    }
}