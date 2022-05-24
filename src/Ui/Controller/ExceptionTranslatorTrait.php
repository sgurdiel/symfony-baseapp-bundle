<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Ui\Controller;

use DomainException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use xVer\Bundle\DomainBundle\Application\TranslatableExceptionOperator;

trait ExceptionTranslatorTrait
{
    public function getTranslatedException(\Throwable $th, TranslatorInterface $translator, bool $asHtml = true): \Exception
    {
        $message = $this->traverseExceptionTree($th, $translator);
        return (
            $asHtml
            ? new DomainException(nl2br((string) $message), $th->getCode())
            : new DomainException($message, $th->getCode())
        );
    }

    private function traverseExceptionTree(
        Throwable $th,
        TranslatorInterface $translator,
        string $message = ''
    ): string {
        $message .= $this->translateMessage($th, $translator);
        $previous = $th->getPrevious();
        if (false === is_null($previous)) {
            $message .= PHP_EOL . $this->traverseExceptionTree($previous, $translator);
        }
        return $message;
    }

    private function translateMessage(\Throwable $th, TranslatorInterface $translator): string
    {
        if (TranslatableExceptionOperator::exceptionIsTranslatable($th)) {
            $parameters = TranslatableExceptionOperator::getTranslationParameters($th);
            foreach ($parameters as $key => $parameter) {
                if (TranslatableExceptionOperator::messageIsTranslatable($parameter)) {
                    $parameters[$key] = $translator->trans(
                        TranslatableExceptionOperator::getTranslationVOId($parameter),
                        TranslatableExceptionOperator::getTranslationVOParameters($parameter),
                        TranslatableExceptionOperator::getTranslationVODomain($parameter)
                    );
                }
            }
            return $translator->trans(
                TranslatableExceptionOperator::getTranslationVOId(
                    TranslatableExceptionOperator::getTranslationVO($th)
                ),
                $parameters,
                TranslatableExceptionOperator::getTranslationVODomain(
                    TranslatableExceptionOperator::getTranslationVO($th)
                ),
            );
        }
        return $th->getMessage();
    }
}
