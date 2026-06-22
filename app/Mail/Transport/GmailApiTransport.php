<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * Sends email via the Gmail REST API using OAuth2 credentials stored in .env.
 *
 * Flow (mirrors WP Mail SMTP / Gmail provider):
 *   1. POST https://oauth2.googleapis.com/token with refresh_token → access_token
 *   2. Build raw MIME message → base64url-encode
 *   3. POST https://gmail.googleapis.com/gmail/v1/users/me/messages/send
 */
class GmailApiTransport extends AbstractTransport
{
    private string $clientId;
    private string $clientSecret;
    private string $refreshToken;

    public function __construct(string $clientId, string $clientSecret, string $refreshToken)
    {
        parent::__construct();

        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->refreshToken = $refreshToken;
    }

    protected function doSend(SentMessage $message): void
    {
        $accessToken = $this->getAccessToken();

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $raw = $message->toString();

        $base64url = rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');

        $response = $this->httpPost(
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send',
            json_encode(['raw' => $base64url]),
            [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
            ]
        );

        $data = json_decode($response['body'], true);

        if (empty($data['id'])) {
            throw new \RuntimeException(
                'Gmail API send failed: ' . ($data['error']['message'] ?? $response['body'])
            );
        }
    }

    /**
     * Exchange the stored refresh_token for a fresh access_token.
     */
    private function getAccessToken(): string
    {
        $response = $this->httpPost(
            'https://oauth2.googleapis.com/token',
            http_build_query([
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type'    => 'refresh_token',
            ]),
            ['Content-Type: application/x-www-form-urlencoded']
        );

        $data = json_decode($response['body'], true);

        if (empty($data['access_token'])) {
            throw new \RuntimeException(
                'Gmail OAuth2 token refresh failed: ' . ($data['error_description'] ?? $response['body'])
            );
        }

        return $data['access_token'];
    }

    /**
     * Simple cURL POST helper.
     */
    private function httpPost(string $url, string $body, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 15,
        ]);

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ['body' => $result ?: '', 'status' => $httpCode];
    }

    public function __toString(): string
    {
        return 'gmail+api://default';
    }
}
