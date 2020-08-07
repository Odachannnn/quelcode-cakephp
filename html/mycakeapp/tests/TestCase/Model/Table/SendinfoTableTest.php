<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SendinfoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SendinfoTable Test Case
 */
class SendinfoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SendinfoTable
     */
    public $Sendinfo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Sendinfo',
        'app.Users',
        'app.Bidinfos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Sendinfo') ? [] : ['className' => SendinfoTable::class];
        $this->Sendinfo = TableRegistry::getTableLocator()->get('Sendinfo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sendinfo);

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
