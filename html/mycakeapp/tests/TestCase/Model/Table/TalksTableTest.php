<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TalksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TalksTable Test Case
 */
class TalksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TalksTable
     */
    public $Talks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Talks',
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
        $config = TableRegistry::getTableLocator()->exists('Talks') ? [] : ['className' => TalksTable::class];
        $this->Talks = TableRegistry::getTableLocator()->get('Talks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Talks);

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
