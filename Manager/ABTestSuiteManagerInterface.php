<?php

namespace AB\ABBundle\Manager;

/**
 * AB tests manager: a "manager" will basically
 * handle the retrieving of test suites.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
interface ABTestSuiteManagerInterface
{

    /**
     * Returns all available tests suites.
     *
     * @return array(ABTestSuiteInterface)
     */
    public function getActiveTestSuites();

    /**
     * Retrieves a test suite by its UID.
     *
     * @param string $uid
     *
     * @return ABTestSuiteInterface
     */
    public function getTestSuite($uid);

}
