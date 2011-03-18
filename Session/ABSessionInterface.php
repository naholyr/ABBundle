<?php

namespace AB\ABBundle\Session;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

/**
 * AB test session: for each A/B test suite, you choose
 * one and only one version, attribute this version to
 * your visitor, and during this visit you will give him
 * the resources for this version of the test.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
interface ABSessionInterface
{

    /**
     * Get current version of the test suite for this session. If
     * no value is set yet, it should choose a random one.
     *
     * @param ABTestSuiteInterface $test_suite
     *
     * @return string : a value amongst $test_suite->getAvailableVersions().
     * 
     * @throws ABErrorNoVersionAvailable if the test suite has no available
     * version.
     */
    public function getVersion(ABTestSuiteInterface $test_suite);

    /**
     * Defines the version of the test suite to be used during this session.
     *
     * @param ABTestSuiteInterface $test_suite
     * 
     * @param string $version
     *
     * @return void
     */
    public function setVersion(ABTestSuiteInterface $test_suite, $version);

}
