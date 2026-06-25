<?php

namespace App\Core;

class AuditActions
{
    public const CREATE = 'CREATE';

    public const UPDATE = 'UPDATE';

    public const DELETE = 'DELETE';

    public const LOGIN = 'LOGIN';

    public const LOGOUT = 'LOGOUT';

    public const PASSWORD_CHANGE = 'PASSWORD_CHANGE';

    public const ACTIVATE = 'ACTIVATE';

    public const SUSPEND = 'SUSPEND';
}