<?php
/**
 * @group SG
 * @group SG_Rule
 */
class SG_Rule_RuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test with simple param
     */
    public function testValue()
    {
        $variables = new SG_Rule_Variables();
        $value = new SG_Rule_Param();
        $rule  = new SG_Rule($value);
        $this->assertTrue(false === $rule->isValid($variables));
        
        $value = new SG_Rule_Param(70);
        $rule  = new SG_Rule($value);
        $this->assertTrue(true === $rule->isValid($variables));
    }
    
    /**
     * Test with variable param
     */
    public function testVariable()
    {
        $variables = new SG_Rule_Variables(array('false' => 0, 'true' => 1));
        $var  = new SG_Rule_Param_Variable('false');
        $rule = new SG_Rule($var);
        $this->assertTrue(false === $rule->isValid($variables));
        
        $var  = new SG_Rule_Param_Variable('true');
        $rule = new SG_Rule($var);
        $this->assertTrue(true === $rule->isValid($variables));
    }
    
    /**
     * Test with comparison
     */
    public function testFormula()
    {
        $variables = new SG_Rule_Variables(array(0 => 0, 10 => 10, 20 => 20));
        $var0  = new SG_Rule_Param_Variable(0);
        $var10 = new SG_Rule_Param_Variable(10);
        $var20 = new SG_Rule_Param_Variable(20);
        
        $less  = new SG_Rule_Comparison_LessThan(
            $var20, $var10
        );
        $rule = new SG_Rule($less);
        $this->assertTrue(false === $rule->isValid($variables));
        
        $less = new SG_Rule_Comparison_LessThan(
            $var10, $var20
        );
        $rule = new SG_Rule($less);
        $this->assertTrue(true === $rule->isValid($variables));
    }
    
    /**
     * Test full blown formula
     * 
     * AND(
     *     AVG(K10+K11+K12+K13+K14+K15) >= 40,
     *     K10 >= 70,
     *     AVG(K10+K18) >= 70,
     *     --(ABS(K10-(K13+K18)/2)/K10) >= 0.20,??
     *     K10 <= AVG(K13+K18)
     * )
     */
    public function testAllInOne()
    {
        $variables = new SG_Rule_Variables(array(
            'q10' => 70,
            'q11' => 50,
            'q12' => 50,
            'q13' => 100,
            'q14' => 50,
            'q15' => 50,
            'q16' => 50,
            'q17' => 50,
            'q18' => 90,
        ));
        $var10 = new SG_Rule_Param_Variable('q10');
        $var11 = new SG_Rule_Param_Variable('q11');
        $var12 = new SG_Rule_Param_Variable('q12');
        $var13 = new SG_Rule_Param_Variable('q13');
        $var14 = new SG_Rule_Param_Variable('q14');
        $var15 = new SG_Rule_Param_Variable('q15');
        $var16 = new SG_Rule_Param_Variable('q16');
        $var17 = new SG_Rule_Param_Variable('q17');
        $var18 = new SG_Rule_Param_Variable('q18');
        
        $and = new SG_Rule_Formula_And(array(
            new SG_Rule_Comparison_GreatherThanOrEqual(
                new SG_Rule_Formula_Average(array(
                    $var10, $var11, $var12, $var13, $var14, $var15
                )),
                new SG_Rule_Param(40)
            ),
            new SG_Rule_Comparison_GreatherThanOrEqual(
                $var10,
                new SG_Rule_Param(70)
            ),
            new SG_Rule_Comparison_GreatherThanOrEqual(
                new SG_Rule_Formula_Average(array(
                    $var10, $var18
                )),
                new SG_Rule_Param(70)
            ),
            new SG_Rule_Comparison_LessThanOrEqual(
                $var10,
                new SG_Rule_Formula_Average(array(
                    $var13, $var18
                ))
            ),
        ));
        $rule = new SG_Rule($and);
        $this->assertTrue($rule->isValid($variables));
        
        // test serialize
        $ruleText = serialize($rule);
        $ruleCode = unserialize($ruleText);
        $this->assertTrue($ruleCode->isValid($variables));
    }
}