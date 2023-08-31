<?php

namespace App\Config;

use App\AppRinger\Logger;
use App\Models\SystemConfig;
use Exception;

class AppConfig
{

    public static function getAdminEmail()
    {
        return SystemConfig::getValue('admin_email') != '' ? SystemConfig::getValue('admin_email') : 'prateek@yopmail.com';
    }

    public static function isNotificationsEnabled()
    {
        return SystemConfig::getValue('is_notification_enabled');
    }

    public static function getGoogleApiKey()
    {
        return SystemConfig::getValue('google_api_key');
    }

    public static function getDataSyncedId()
    {
        return SystemConfig::getValue('data_synced_id');
    }
    public static function getDataPerCycle()
    {
        return SystemConfig::getValue('data_per_cycle');
    }

    public static function updateDataSyncedId($value)
    {
        return SystemConfig::updateValue('data_synced_id',$value);
    }

    public static function getReminderAfterDays()
    {
        return SystemConfig::getValue('reminder_after_days') != '' ? SystemConfig::getValue('reminder_after_days') : 30;
    }

    public static function getProfileViewCountBandwidth()
    {
        return SystemConfig::getValue('profile_view_count_bandwidth') != '' ? SystemConfig::getValue('profile_view_count_bandwidth') : 60;
    }

    public static function getProfileShowCountBandwidth()
    {
        return SystemConfig::getValue('profile_view_show_bandwidth') != '' ? SystemConfig::getValue('profile_view_show_bandwidth') : 90;
    }

    public static function getRazorPayKeys()
    {
        $data['key'] = SystemConfig::getValue('RZP_KEY') != '' ? SystemConfig::getValue('RZP_KEY') : "rzp_test_OZ1zcfIxS5AzZW";
        $data['secret'] = SystemConfig::getValue('RZP_SECRET') != '' ? SystemConfig::getValue('RZP_SECRET') : "pZuEcyA1VcU8COKo1JDbpAtf";
        return $data;
    }

    public static function isRemoveIncompleteCandidates()
    {
        return SystemConfig::getValue('is_remove_incomplete_candidates_from_search') != '' ? SystemConfig::getValue('is_remove_incomplete_candidates_from_search') : false;
    }

    public static function getGST()
    {
        return SystemConfig::getValue('gst') != '' ? SystemConfig::getValue('gst') : 18;
    }

    public static function getCurrency()
    {
        return SystemConfig::getValue('currency') != '' ? SystemConfig::getValue('currency') : 'Rs';
    }

    public static function getInvoiceCompanyName()
    {
        return SystemConfig::getValue('invoice_company_name') != '' ? SystemConfig::getValue('invoice_company_name') : 'FINDR PRO TECHNOLOGY SOLUTIONS PRIVATE LIMITED';
    }

    public static function getCompanyGSTNumber()
    {
        return SystemConfig::getValue('invoice_company_gst_number') != '' ? SystemConfig::getValue('invoice_company_gst_number') : '29AAFCF0800P1ZH';
    }

    public static function getChatGPTApiKey()
    {
        return SystemConfig::getValue('chatgpt_id') != '' ? SystemConfig::getValue('chatgpt_id') : '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';
    }

    public static function getWhitelistedEmails()
    {
        return SystemConfig::getValue('WHITELISTED_EMAILS') != '' ? SystemConfig::getValue('WHITELISTED_EMAILS') : '';
    }

    public static function getKeywordSplitters()
    {
        return SystemConfig::getValue('keyword_splitters') != '' ? SystemConfig::getValue('keyword_splitters') : '';
    }

    public static function isRemoveSkillEnabled()
    {
        return SystemConfig::getValue('remove_skill_enabled') != '' ? SystemConfig::getValue('remove_skill_enabled') : false;
    }

    public static function isDataPullEnabled()
    {
        return SystemConfig::getValue('data_pull_enabled') != '' ? SystemConfig::getValue('data_pull_enabled') : false;
    }

    public static function isUpdateSearchVisibilityEnabled()
    {
        return SystemConfig::getValue('update_search_visibility_enabled') != '' ? SystemConfig::getValue('update_search_visibility_enabled') : false;
    }

    public static function isUpdateCVEnabled()
    {
        return SystemConfig::getValue('update_cv_enabled') != '' ? SystemConfig::getValue('update_cv_enabled') : false;
    }

    public static function userSearchVisibilitySyncedId()
    {
        return SystemConfig::getValue('user_search_visibility_synced_id') != '' ? SystemConfig::getValue('user_search_visibility_synced_id') : 0;
    }

    public static function updateUserSearchVisibilitySyncedId($value)
    {
        return SystemConfig::updateValue('user_search_visibility_synced_id',$value);
    }

    public static function isUpdateJobsEnabled()
    {
        return SystemConfig::getValue('update_jobs_enabled') != '' ? SystemConfig::getValue('update_jobs_enabled') : false;
    }

    public static function isUpdateGigsEnabled()
    {
        return SystemConfig::getValue('update_gigs_enabled') != '' ? SystemConfig::getValue('update_gigs_enabled') : false;
    }
}