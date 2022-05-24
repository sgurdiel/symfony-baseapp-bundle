<?php

namespace Tests\unit\Ui\Controller;

use Exception;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use xVer\Bundle\DomainBundle\Domain\DomainException;
use xVer\Bundle\DomainBundle\Domain\TranslationVO;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Controller\ExceptionTranslatorTrait;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Ui\Controller\ExceptionTranslatorTrait
 */
class ExceptionTranslatorTraitTest extends TestCase
{
    public function testGetTranslatedExceptionWhenExceptionIsNotTranslatable(): void
    {
        $exceptionPrevious = new Exception('Im previous');
        $exception = new Exception('Im the exception', 0, $exceptionPrevious);
        $translatorMock = $this->createStub(TranslatorInterface::class);
        $exceptionTranslatorTraitmock = $this->getMockForTrait(ExceptionTranslatorTrait::class);
        $translatedException = $exceptionTranslatorTraitmock->getTranslatedException($exception, $translatorMock, false);
        $this->assertInstanceOf(Exception::class, $translatedException);
        $this->assertSame('Im the exception' . PHP_EOL . 'Im previous', $translatedException->getMessage());
        $translatedException = $exceptionTranslatorTraitmock->getTranslatedException($exception, $translatorMock);
        $this->assertSame('Im the exception<br />' . PHP_EOL . 'Im previous', $translatedException->getMessage());
    }

    public function testGetTranslatedExceptionWhenExceptionIsTranslatable(): void
    {
        $translationVOPreviousParameters = [
            'parameter1' => new TranslationVO('previous')
        ];
        $translationVOPrevious = new TranslationVO('Im {parameter1}', $translationVOPreviousParameters);
        $exceptionPrevious = new DomainException($translationVOPrevious);
        $translationVOParameters = [
            'parameter1' => new TranslationVO('exception')
        ];
        $translationVO = new TranslationVO('Im the {parameter1}', $translationVOParameters);
        $exception = new DomainException($translationVO, null, 0, $exceptionPrevious);
        /** @var TranslatorInterface&Stub */
        $translatorMock = $this->createStub(TranslatorInterface::class);
        $translatorMock->method('trans')->will(
            $this->returnValueMap([
                ['exception', [], 'messages', null, 'exception'],
                ['previous', [], 'messages', null, 'previous'],
                ['Im the {parameter1}', ["parameter1" => "exception"], 'messages', null, 'Im the exception'],
                ['Im {parameter1}', ["parameter1" => "previous"], 'messages', null, 'Im previous']
            ])
        );
        $exceptionTranslatorTraitmock = $this->getMockForTrait(ExceptionTranslatorTrait::class);
        $translatedException = $exceptionTranslatorTraitmock->getTranslatedException($exception, $translatorMock, false);
        $this->assertInstanceOf(Exception::class, $translatedException);
        $this->assertSame('Im the exception' . PHP_EOL . 'Im previous', $translatedException->getMessage());
    }
}