<?php

namespace Tests\unit\Ui\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Form\RequestRecoverPasswordFormType;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Ui\Form\RequestRecoverPasswordFormType
 */
class RequestRecoverPasswordFormTypeTest extends TypeTestCase
{
    use \Symfony\Component\Form\Test\Traits\ValidatorExtensionTrait;

    public function testSubmitValidData(): void
    {       
        $formData = [
            'email' => 'test@example.com',
        ];
        $expectedOutputFormData = $formData;

        $form = $this->factory->create(RequestRecoverPasswordFormType::class, $formData);
        
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
            'email' => 'test@example.com',
        ];

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(RequestRecoverPasswordFormType::class, $formData)
            ->createView();

        $this->assertArrayHasKey('value', $view->vars);
        $this->assertSame($formData, $view->vars['value']);
    }
}
