<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
  public array $aliases = [
    'csrf'     => CSRF::class,
    'toolbar'  => DebugToolbar::class,
    'honeypot' => Honeypot::class,
    'invalidchars' => InvalidChars::class,
    'secureheaders' => SecureHeaders::class,

    // 👇 ADD THIS
    'role' => \App\Filters\RoleFilter::class,
];
}
