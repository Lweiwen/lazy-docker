<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class SystemCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    const SYSTEM_ERROR = 500;
}
