<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $defaultGroup = 'default';

    public array $default = [];

    public function __construct()
    {
        parent::__construct();

        $this->default = [
            'DSN' => '',
            'hostname' => getenv('database.default.hostname'),
            'username' => getenv('database.default.username'),
            'password' => getenv('database.default.password'),
            'database' => getenv('database.default.database'),
            'DBDriver' => getenv('database.default.DBDriver'),
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => (bool) getenv('database.default.DBDebug'),
            'charset' => 'utf8mb4',
            'DBCollat' => 'utf8mb4_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => (int) getenv('database.default.port') ?: 3307,
            'numberNative' => false,
            'foundRows' => false,
            'dateFormat' => [
                'date' => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time' => 'H:i:s',
            ],
        ];
    }
}
