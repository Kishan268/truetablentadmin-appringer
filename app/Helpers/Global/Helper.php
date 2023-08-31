<?php

    
if (!function_exists('getSystemConfig')) {
    function getSystemConfig($key)
    {
        return \App\Models\SystemConfig::getValue($key);
    }
}

if (!function_exists('notificationTemplates')) {
    function notificationTemplates($key)
    {
        return \App\Models\NotificationSetting::getValue($key);
    }
}
