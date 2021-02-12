<?php

namespace SafetyNet\Verifier;

use Firebase\JWT\JWT;
use SafetyNet\Verifier\Exception\CheckSignatureException;
use SafetyNet\Statement\Statement;

class OfflineVerifier extends Verifier
{

    protected function guardSignature(Statement $statement): bool
    {
        $jwsHeaders = $statement->getRawHeaders();
        $jwsBody = $statement->getRawBody();

        $signData = $jwsHeaders . '.' . $jwsBody;

        $stringPublicKey = (string)$statement->getHeader()->getCertificateChain()->getPublicKey();

        [$checkMethod, $algorithm] = JWT::$supported_algs[$statement->getHeader()->getAlgorithm()];

        if ($checkMethod != 'openssl') {
            throw new CheckSignatureException('Not supported algorithm function');
        }

        if (openssl_verify($signData, $statement->getSignature(), $stringPublicKey, $algorithm) < 1) {
            throw new CheckSignatureException('Signature is invalid');
        }

        return true;
    }
}