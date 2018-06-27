<?php

namespace Cammac\LaravelSms\SMS;


class DriverManager extends \SimpleSoftwareIO\SMS\DriverManager
{
    /**
     * Creates an instance of the EMail driver.
     *
     * @return \App\SMS\MobilywsSMS
     */
    protected function createMobilywsDriver()
    {
        return app(MobilywsSMS::class);
    }
    /**
     * Creates an instance of the EMail driver.
     *
     * @return \App\SMS\MobilywsSMS
     */
    protected function createSmsgwDriver()
    {
        return app(SmsgwSMS::class);
    }
}