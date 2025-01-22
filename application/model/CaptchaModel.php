<?php

use GuzzleHttp\Client;

/**
 * Class CaptchaModel
 *
 * This model class handles all the captcha stuff.
 * Currently this uses the excellent Captcha generator lib from https://github.com/Gregwar/Captcha
 * Have a look there for more options etc.
 */
class CaptchaModel
{
    protected $httpClient;
    protected $secretKey;

    public function __construct(Client $httpClient, string $secretKey)
    {
        $this->httpClient = $httpClient;
        $this->secretKey = $secretKey;
    }

    /**
     * Validates the reCAPTCHA token sent from the client-side.
     *
     * @param string $token The reCAPTCHA response token from the frontend.
     * @param string $userIp The user's IP address (optional but recommended for security).
     * @return bool True if the token is valid, false otherwise.
     */
    public function validateRecaptcha(string $token, string $userIp = null): bool
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $response = $this->httpClient->post($url, [
            'form_params' => [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => "localhost",
            ],
        ]);

        $responseBody = json_decode((string) $response->getBody(), true);

        return isset($responseBody['success']) && $responseBody['success'] === true;
    }
}
