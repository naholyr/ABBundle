<?php

namespace AB\ABBundle\Documents;

/**
 * @Document
 * @author Nicolas Chambrier <naholyr@gmail.com>
 *
 */
class ABTestSuiteDoctrineODMImpl implements ABTestSuiteInterface
{

    /**
     * @Id
     */
    private $id;

    /**
     * @String
     * @UniqueIndex(order="asc")
     */
    private $uid;

    /**
     * @String
     */
    private $description;

    /**
     * @Collection
     */
    private $versions = array();

    /**
     * @Hash
     */
    private $scores = array();

    /**
     * @Hash
     */
    private $replacements;

    /**
     * @Boolean
     */
    private $active;

    public function getUID()
    {
        return $this->uid;
    }

    private function checkVersion($version)
    {
        if (!in_array($version, $this->versions)) {
            throw new ABErrorUnavailableVersion();
        }
    }

    public function removeVersion($version)
    {
        $this->checkVersion($version);
    }

    public function addVersion($version)
    {
        $this->versions[] = $version;
        $this->scores[$version] = 0;
    }

    public function getAvailableVersions()
    {
        return $this->versions;
    }

    public function addReplacements($version, array $replacements)
    {
        $this->checkVersion($version);

        $this->replacements[$version] = array_merge(@$this->replacements[$version] ?: array(), $replacements);
    }

    public function setReplacements($version, array $replacements)
    {
        $this->checkVersion($version);

        $this->replacements[$version] = $replacements;
    }

    public function getResource($version, $resource)
    {
        $this->checkVersion($version);

        return @$this->replacements[$version][$resource] ?: $resource;
    }

    public function addScore($version, $points = +1)
    {
        $this->checkVersion($version);

        if (!isset($this->scores[$version])) {
            $this->scores[$version] = $points;
        } else {
            $this->scores[$version] += $points;
        }
    }

    public function getScores()
    {
        return $this->scores;
    }

}