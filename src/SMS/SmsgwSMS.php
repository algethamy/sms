<?php

namespace Cammac\LaravelSms\SMS;


use GuzzleHttp\Client;
use SimpleSoftwareIO\SMS\DoesNotReceive;
use SimpleSoftwareIO\SMS\Drivers\DriverInterface;
use SimpleSoftwareIO\SMS\OutgoingMessage;
use SimpleSoftwareIO\SMS\SMSNotSentException;

class SmsgwSMS implements DriverInterface
{
    use DoesNotReceive;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * MobilyWSSMS constructor.
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Sends a SMS message.
     *
     * @param \SimpleSoftwareIO\SMS\OutgoingMessage $message
     * @throws \SimpleSoftwareIO\SMS\SMSNotSentException
     */
    public function send(OutgoingMessage $message)
    {
        $response = \Facades\GuzzleHttp\Client::post('http://api.smsgw.net/SendBulkSMS', [
            'form_params' => [
                'strUserName' => config('sms.smsgw.username'),
                'strPassword' => config('sms.smsgw.password'),
                'strTagName' => config('sms.from'),
                'strRecepientNumbers' => implode(',', $message->getTo()),
                'strMessage' => $message->composeMessage(),
                'sendDateTime' => '',
            ]
        ])->getBody()->getContents();

        if ($response == '1') {
            return;
        }

        if ($response == 0) {
            $reason = 'Failed';
        } elseif ($response == 2) {
            $reason = 'Pending';
        } elseif ($response == -10) {
            $reason = 'Invalid UserName and Password';
        } elseif ($response == -20) {
            $reason = 'Invalid TagName Format';
        } elseif ($response == -30) {
            $reason = 'TagName doesn\'t exist';
        } elseif ($response == -40) {
            $reason = 'Insufficient Fund';
        } elseif ($response == -40) {
            $reason = 'strRecepientNumber Length does not equal to ReplacementList Length';
        } elseif ($response == -50) {
            $reason = 'strRecepientNumber Length does not equal to ReplacementList Length';
        } elseif ($response == -60) {
            $reason = 'Invalid Mobile Number';
        } elseif ($response == -70) {
            $reason = 'System Error';
        }

        throw new SMSNotSentException('Failed to send SMS. Error code: [' . $response . '][' . $reason . ']');
    }

    /**
     * Checks the server for messages and returns their results.
     *
     * @param array $options
     *
     * @return array
     */
    public function checkMessages(array $options = [])
    {
        // TODO: Implement checkMessages() method.
    }

    /**
     * Gets a single message by it's ID.
     *
     * @param string|int $messageId
     *
     * @return \SimpleSoftwareIO\SMS\IncomingMessage
     */
    public function getMessage($messageId)
    {
        // TODO: Implement getMessage() method.
    }

    /**
     * Receives an incoming message via REST call.
     *
     * @param mixed $raw
     *
     * @return \SimpleSoftwareIO\SMS\IncomingMessage
     */
    public function receive($raw)
    {
        // TODO: Implement receive() method.
    }
}