<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'browser' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'time' => date('H:i:s')
        ];
    }
}