<?php

namespace App\Const;

class NotificationTypes
{
    public const USER = 'user';
    public const ALL = 'all';
    public const LAWYER = 'lawyer';
    public const ADMIN = 'admin';

    public const CUSTOMER = 'customer';


    public static function getNotificationsTypes(): array
    {
        return [
            self::USER,
            self::ALL,
            self::LAWYER,
            self::ADMIN,
            self::CUSTOMER
        ];
    }
}
