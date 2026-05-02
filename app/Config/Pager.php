<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    public array $templates = [
        'default_full'     => 'CodeIgniter\Pager\Views\default_full',
        'default_simple'   => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'     => 'CodeIgniter\Pager\Views\default_head',

        // ✅ Custom Bootstrap templates
        'bootstrap_full'   => 'App\Views\pagers\bootstrap_full',
        'bootstrap_simple' => 'App\Views\pagers\bootstrap_simple',
    ];

    public int $perPage = 20;
}
