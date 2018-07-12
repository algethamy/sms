<?php

namespace Cammac\LaravelSms\Tests\Features;

use Cammac\LaravelSms\Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use SimpleSoftwareIO\SMS\Facades\SMS;
use SimpleSoftwareIO\SMS\OutgoingMessage;

class LogTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('sms.driver', 'log');
        $app['config']->set('sms.from', 'Abdullah');
    }

    /** @test */
    public function able_to_send_via_MobilywsTest()
    {
        Log::shouldReceive('notice')
            ->once()
            ->with("Sending SMS message to: 0505555555");
        
        SMS::send('sms.forgot', compact('code'), function (OutgoingMessage $sms) {
            $sms->to('0505555555');
        });
    }

    /** @test */
    public function able_to_queue_via_MobilywsTest()
    {
        Queue::fake();

        Log::shouldReceive('notice')
            ->times(0);

        SMS::queue('sms.forgot', compact('code'), function (OutgoingMessage $sms) {
            $sms->to('0505550831');
        });

        Queue::assertPushed('sms@handleQueuedMessage', 1);
    }
}