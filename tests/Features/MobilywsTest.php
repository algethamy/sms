<?php

namespace Cammac\LaravelSms\Tests\Features;

use Cammac\LaravelSms\Tests\TestCase;
use Facades\GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Queue;
use SimpleSoftwareIO\SMS\Facades\SMS;
use SimpleSoftwareIO\SMS\OutgoingMessage;

class MobilywsTest extends TestCase
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
        $app['config']->set('sms.driver', 'mobilyws');
        $app['config']->set('sms.from', '');
        $app['config']->set('sms.smsgw', [
            'username'    => 'valid_username',
            'password'    => 'valid_password',
        ]);
    }

    /** @test */
    public function able_to_send_via_MobilywsTest()
    {
        Client::shouldReceive('post')
            ->once()
            ->andReturn(new Response(200, [], '1'));
        
        SMS::send('sms.forgot', compact('code'), function (OutgoingMessage $sms) {
            $sms->to(\request('mobile'));
        });
    }

    /** @test */
    public function able_to_queue_via_MobilywsTest()
    {
        Queue::fake();

        Client::shouldReceive('post')
            ->times(0);

        SMS::queue('sms.forgot', compact('code'), function (OutgoingMessage $sms) {
            $sms->to('0505550831');
        });

        Queue::assertPushed('sms@handleQueuedMessage', 1);
    }
}