<?php

namespace AB\ABBundle\Model;

/**
 * AB test session: for each A/B test suite, you choose
 * one and only one version, attribute this version to
 * your visitor, and during this visit you will give him
 * the resources for this version of the test.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
interface SessionInterface
{

    /**
     * Get current version of the test suite for this session. If
     * no value is set yet, it should choose a random one.
     *
     * @param TestSuiteInterface $test_suite
     *
     * @return string : a value amongst $test_suite->getAvailableVersions().
     *
     * @throws ErrorNoVersionAvailable if the test suite has no available
     * version.
     */
    public function getVersion(TestSuiteInterface $test_suite);

    /**
     * Defines the version of the test suite to be used during this session.
     *
     * @param TestSuiteInterface $test_suite
     *
     * @param string $version
     *
     * @return void
     */
    public function setVersion(TestSuiteInterface $test_suite, $version);

}
