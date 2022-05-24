<?php

namespace Tests\unit\Ui\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Form\ResetPasswordFormType;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Ui\Form\ResetPasswordFormType
 */
class ResetPasswordFormTypeTest extends TypeTestCase
{
    use \Symfony\Component\Form\Test\Traits\ValidatorExtensionTrait;

    public function testSubmitValidData(): void
    {       
        $formData = [
            'newPassword' => array('first' => 'password', 'second' => 'password')
        ];
        $expectedOutputFormData = $formData;

        $form = $this->factory->create(ResetPasswordFormType::class, $formData);
        
        // submit the data to the form directly
        $form->submit($formData);
        
        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expectedOutputFormData, $formData);
    }

    public function testCustomFormView()
    {
        // ... prepare the data as you need
        $formData = [           
            'newPassword' => 'password',
        ];

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(ResetPasswordFormType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('value', $view->vars);
        $this->assertSame($formData, $view->vars['value']);
    }
}
