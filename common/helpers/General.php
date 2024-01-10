<?php

namespace common\helpers;

class General
{
    public static function formatPhoneNumber($phone)
    {
        $phone = str_replace(["(", ")", "-", " "], "", $phone);
        if (strlen($phone) === 10) {
            return substr($phone, -10, 3) . "-" . substr($phone, -7, 3) . "-" . substr($phone, -4, 4);
        }
        if (strlen($phone) === 11) {
            return $phone[strlen($phone) - 11] . "-"
                . substr($phone, -10, 3)
                . "-" . substr($phone, -7, 3)
                . "-" . substr($phone, -4, 4);
        }

        return $phone;
    }

    public static function base64($path): string
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
