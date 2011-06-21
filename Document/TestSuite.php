<?php

namespace AB\ABBundle\Document;

use AB\ABBundle\Model\TestSuiteInterface;
use AB\ABBundle\Base\TestSuite as BaseTestSuite;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="ab_test_suites")
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 * @license MIT <http://www.opensource.org/licenses/mit-license.php>
 */
class TestSuite extends BaseTestSuite
{

    /**
     * @ODM\Id
     */
    protected $id;

    /**
     * @ODM\String
     * @ODM\UniqueIndex(order="asc")
     */
    protected $uid;

    /**
     * @ODM\String
     */
    protected $description;

    /**
     * @ODM\Collection
     */
    protected $versions = array();

    /**
     * @ODM\Hash
     */
    protected $scores = array();

    /**
     * @ODM\Hash
     */
    protected $replacements;

    /**
     * @ODM\Boolean
     */
    protected $active;

}
