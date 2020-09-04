<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ReceivingNoticesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ReceivingNoticesTable Test Case
 */
class ReceivingNoticesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ReceivingNoticesTable
     */
    public $ReceivingNotices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ReceivingNotices',
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
        $config = TableRegistry::getTableLocator()->exists('ReceivingNotices') ? [] : ['className' => ReceivingNoticesTable::class];
        $this->ReceivingNotices = TableRegistry::getTableLocator()->get('ReceivingNotices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ReceivingNotices);

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
