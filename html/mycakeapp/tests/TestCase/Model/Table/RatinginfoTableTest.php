<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RatinginfoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RatinginfoTable Test Case
 */
class RatinginfoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RatinginfoTable
     */
    public $Ratinginfo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Ratinginfo',
        'app.Bidinfos',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Ratinginfo') ? [] : ['className' => RatinginfoTable::class];
        $this->Ratinginfo = TableRegistry::getTableLocator()->get('Ratinginfo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Ratinginfo);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
