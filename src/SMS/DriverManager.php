<?php

namespace Cammac\LaravelSms\SMS;


class DriverManager extends \SimpleSoftwareIO\SMS\DriverManager
{
    /**
     * Creates an instance of the EMail driver.
     *
     * @return MobilywsSMS
     */
    protected function createMobilywsDriver()
    {
        return app(MobilywsSMS::class);
    }
    /**
     * Creates an instance of the EMail driver.
     *
     * @return SmsgwSMS
     */
    protected function createSmsgwDriver()
    {
        return app(SmsgwSMS::class);
    }

    /**
     * Create an instance of the Log driver.
     *
     * @return LogSMS
     */
    protected function createLogDriver()
    {
        return app(LogSMS::class);
    }
}