<?php

namespace SafetyNet\Verifier;

use SafetyNet\CustomEnum;

/**
 * Class VerifierType
 *
 * @package SafetyNet
 * @method static VerifierType ONLINE()
 * @method static VerifierType OFFLINE()
 * @method static bool isONLINE(VerifierType $type)
 * @method static bool isOFFLINE(VerifierType $type)
 */
class VerifierType extends CustomEnum
{
    private const ONLINE = 'online';
    private const OFFLINE = 'offline';
}