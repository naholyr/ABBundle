<?php

namespace AB\ABBundle\Entity;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Base\TestSuite as BaseTestSuite;

/**
 * @orm:Entity
 * @orm:Table(name="ab_test_suites")
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
class TestSuite extends BaseTestSuite
{

    /**
     * @orm:Column(type="integer")
     * @orm:Id
     */
    protected $id;

    /**
     * @orm:Column(type="string", length=32, unique=true)
     */
    protected $uid;

    /**
     * @orm:Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @orm:Column(type="array")
     */
    protected $versions = array();

    /**
     * @orm:Column(type="array")
     */
    protected $scores = array();

    /**
     * @orm:Column(type="array")
     */
    protected $replacements;

    /**
     * @orm:Column(type="boolean")
     */
    protected $active;

}
