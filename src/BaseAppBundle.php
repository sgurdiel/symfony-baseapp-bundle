<?php

namespace xVer\Symfony\Bundle\BaseAppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BaseAppBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
