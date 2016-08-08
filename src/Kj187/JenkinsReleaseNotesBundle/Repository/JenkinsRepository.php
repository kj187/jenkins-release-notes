<?php

namespace Kj187\JenkinsReleaseNotesBundle\Repository;

use \Kj187\JenkinsReleaseNotesBundle\Service\ObjectTransformer;
use \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Job;
use \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build;

class JenkinsRepository
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client = null;

    /**
     * @var ObjectTransformer
     */
    protected $objectTransformer = null;

    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => $configuration['url']]);
        $this->objectTransformer = new \Kj187\JenkinsReleaseNotesBundle\Service\ObjectTransformer();
    }

    /**
     * @param string $uri
     * @return array
     */
    protected function request($uri)
    {
        $response = $this->client->request('GET', $uri, [
            'stream' => true,
            'stream_context' => [
                'ssl' => [
                    'allow_self_signed' => true
                ]
            ]
        ]);

        // TODO error handling
        $body = $response->getBody();
        $content = $body->getContents();
        $data = \GuzzleHttp\json_decode($content, true);

        return $data;
    }

    /**
     * @return \Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Job[]
     */
    public function findJobs()
    {
        $data = [];

        $response = $this->request('api/json');
        foreach ($response['jobs'] as $job) {
            $data[] = $this->objectTransformer->transformArrayToObject($job, 'Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Job');
        }

        return $data;
    }

    /**
     * @param Job $job
     * @return Job
     */
    public function findJob(Job $job)
    {
        $response = $this->request('job/' . $job->getName() . '/api/json');
        $data = $this->objectTransformer->transformArrayToObject($response, 'Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Job');
        return $data;
    }

    /**
     * @param Job $job
     * @param Build $build
     * @return Build
     */
    public function findBuild(Job $job, Build $build)
    {
        $response = $this->request('job/' . $job->getName() . '/' . $build->getNumber() . '/api/json');
        $response['commitHash'] = '';

        if (isset($response['actions'])) {
            foreach ($response['actions'] as $key => $action) {
                if (!array_key_exists('lastBuiltRevision', $action) && !isset($action['lastBuiltRevision']['branch'][0])) {
                    continue;
                }

                $response['vcsCommitHash'] = $action['lastBuiltRevision']['branch'][0]['SHA1'];
                $response['vcsBranchName'] = $action['lastBuiltRevision']['branch'][0]['name'];

                if (!array_key_exists('remoteUrls', $action) && !isset($action['remoteUrls'][0])) {
                    continue;
                }

                $remoteUrlParts = explode(':', $action['remoteUrls'][0]);
                $remoteUrlParts = explode('/', $remoteUrlParts[1]);

                $response['vcsRemoteUrl'] = $action['remoteUrls'][0];
                $response['vcsUsername'] = $remoteUrlParts[0];
                $response['vcsRepository'] = str_replace('.git', '', $remoteUrlParts[1]);
            }
        }

        $data = $this->objectTransformer->transformArrayToObject($response, 'Kj187\JenkinsReleaseNotesBundle\Entity\Jenkins\Build');
        return $data;
    }
}
