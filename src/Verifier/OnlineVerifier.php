<?php

namespace SafetyNet\Verifier;

use SafetyNet\Statement\Statement;
use SafetyNet\Verifier\Exception\CheckSignatureException;
use SafetyNet\Verifier\Exception\GoogleRequestError;

class OnlineVerifier extends Verifier
{
    private const GOOGLE_API_ENDPOINT = 'https://www.googleapis.com/androidcheck/v1/attestations/verify';
    private ?array $googleAdiResponse = null;

    protected function guardSignature(Statement $statement): bool
    {
        if (empty($this->googleAdiResponse)) {
            $this->googleAdiResponse = $this->requestForGoogleServer($statement);
        }

        if (!$this->googleAdiResponse['isValidSignature']) {
            throw new CheckSignatureException('Signature is invalid');
        }

        return true;
    }

    private function requestForGoogleServer(Statement $statement) {
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/json',
                'content' => json_encode(
                    ['signedAttestation' => $statement->toString()]
                ),
            ],
        ];

        $context  = stream_context_create($opts);

        $result = @file_get_contents(self::GOOGLE_API_ENDPOINT . "?key=" . $this->config->getApiKey(), false, $context);
        $result = json_decode($result, true);

        if (!is_array($result)) {
            throw new GoogleRequestError("Request to Google service failed");
        }

        return $result;
    }
}