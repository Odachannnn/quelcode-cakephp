<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShippingNoticesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShippingNoticesTable Test Case
 */
class ShippingNoticesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ShippingNoticesTable
     */
    public $ShippingNotices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ShippingNotices',
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
        $config = TableRegistry::getTableLocator()->exists('ShippingNotices') ? [] : ['className' => ShippingNoticesTable::class];
        $this->ShippingNotices = TableRegistry::getTableLocator()->get('ShippingNotices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShippingNotices);

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
