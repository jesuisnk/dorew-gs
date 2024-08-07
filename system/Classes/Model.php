<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

namespace System\Classes;

use PDO;

class Model
{
    protected PDO $db;
    protected Config $config;

    public function __construct()
    {
        $this->db = app(DB::class);
        $this->config = app(Config::class);
    }
}
