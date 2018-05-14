<?php

namespace SilverStripe\UserForms\Tests\Model;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\UserForms\Model\EditableCustomRule;

/**
 * Class EditableCustomRulesTest
 */
class EditableCustomRuleTest extends SapphireTest
{
    protected static $fixture_file = 'EditableCustomRuleTest.yml';

    public function testBuildExpression()
    {
        /** @var EditableCustomRule $rule1 */
        $rule1 = $this->objFromFixture(EditableCustomRule::class, 'rule1');
        $result1 = $rule1->buildExpression();

        //Dropdowns expect change event
        $this->assertEquals('change', $result1['event']);
        $this->assertNotEmpty($result1['operation']);

        //Check for equals sign
        $this->assertContains('==', $result1['operation']);

        /** @var EditableCustomRule $rule2 */
        $rule2 = $this->objFromFixture(EditableCustomRule::class, 'rule2');
        $result2 = $rule2->buildExpression();

        //TextField expect change event
        $this->assertEquals('keyup', $result2['event']);
        $this->assertNotEmpty($result2['operation']);

        //Check for greater than sign
        $this->assertContains('>', $result2['operation']);
    }

    /**
     * Test that methods are returned for manipulating the presence of the "hide" CSS class depending
     * on whether the field should be hidden or shown
     */
    public function testToggleDisplayText()
    {
        $rule1 = $this->objFromFixture(EditableCustomRule::class, 'rule1');
        $this->assertSame('addClass("hide")', $rule1->toggleDisplayText('show'));
        $this->assertSame('removeClass("hide")', $rule1->toggleDisplayText('hide'));
    }

    /**
     * Test that methods are returned for manipulating the presence of the "hide" CSS class depending
     * on whether the field should be hidden or shown
     */
    public function testValidateAgainstFormData()
    {
        $rule1 = $this->objFromFixture(EditableCustomRule::class, 'rule1');

        $this->assertFalse($rule1->validateAgainstFormData([]));
        $this->assertFalse($rule1->validateAgainstFormData(['CountrySelection' => 'AU']));
        $this->assertTrue($rule1->validateAgainstFormData(['CountrySelection' => 'NZ']));

        $rule2 = $this->objFromFixture(EditableCustomRule::class, 'rule2');

        $this->assertFalse($rule2->validateAgainstFormData([]));
        $this->assertFalse($rule2->validateAgainstFormData(['CountryTextSelection' => 0]));
        $this->assertFalse($rule2->validateAgainstFormData(['CountryTextSelection' => 1]));
        $this->assertTrue($rule2->validateAgainstFormData(['CountryTextSelection' => 2]));
    }
}
