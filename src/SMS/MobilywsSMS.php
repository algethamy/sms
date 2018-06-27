<?php

namespace Cammac\LaravelSms\SMS;

use GuzzleHttp\Client;
use SimpleSoftwareIO\SMS\DoesNotReceive;
use SimpleSoftwareIO\SMS\Drivers\DriverInterface;
use SimpleSoftwareIO\SMS\OutgoingMessage;
use SimpleSoftwareIO\SMS\SMSNotSentException;

class MobilywsSMS implements DriverInterface
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
        $response = $this->client->post('https://www.mobily.ws/api/msgSend.php', [
            'form_params' => [
                'mobile'          => config('sms.mobilyws.username'),
                'password'        => config('sms.mobilyws.password'),
                'sender'          => config('sms.from'),
                'numbers'         => implode(',', $message->getTo()),
                'msg'             => $this->convertUtf8ToUnicode($message->composeMessage()),
                'applicationType' => 24,
                'notRepeat'       => 1,
            ],
        ]);

        $responseCode = $response->getBody()->getContents();

        if ($responseCode == '1') {
            return;
        }

        if ($responseCode == 5) {
            $reason = 'wrong credentials';
        } elseif ($responseCode == 4) {
            $reason = 'no user or mobile';
        } elseif ($responseCode == 3) {
            $reason = 'no charge';
        } elseif ($responseCode == 2) {
            $reason = 'no charge zero';
        } elseif ($responseCode == 6) {
            $reason = 'try later';
        } elseif ($responseCode == 10) {
            $reason = 'mobile numbers more than the points you have';
        } elseif ($responseCode == 13) {
            $reason = 'sender not approved';
        } elseif ($responseCode == 14) {
            $reason = 'empty sender';
        } elseif ($responseCode == 15) {
            $reason = 'empty numbers';
        } elseif ($responseCode == 16) {
            $reason = 'empty sender2';
        } elseif ($responseCode == 17) {
            $reason = 'message not encoding';
        } elseif ($responseCode == 18) {
            $reason = 'service is down';
        } elseif ($responseCode == 19) {
            $reason = 'app error';
        }

        throw new SMSNotSentException('Failed to send SMS. Error code: [' . $responseCode . '][' . $reason . ']');
    }


    /**
     * @return string
     */
    public function convertUtf8ToUnicode($string)
    {
        $normalizedNewLinesString = preg_replace('#(\r\n|\n|\r)#', "\r\n", $string);

        $unicode = implode(unpack('H*', iconv('UTF-8', 'UCS-4BE', $normalizedNewLinesString)));

        return str_replace('0000', '', $unicode);
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