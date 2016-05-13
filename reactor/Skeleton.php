<?php

namespace Reactor\Bootstrap;

use Evaneos\REST\HttpKernel;
use PHPPM\Bootstraps\BootstrapInterface;
use Ramsey\Uuid\Uuid;

class Skeleton implements BootstrapInterface
{
    /**
     * @var string
     */
    private $appenv;

    /**
     * @var bool
     */
    private $debug;


    public function __construct($appenv, $debug)
    {
        $this->appenv = $appenv;
        $this->debug = $debug;
    }

    public function getApplication()
    {
        require __DIR__ . '/../vendor/autoload.php';

        $app = new HttpKernel($this->appenv, $this->debug, (string) Uuid::uuid4());
        return $app;
    }

    public function getStaticDirectory()
    {
        return 'public/';
    }

}
