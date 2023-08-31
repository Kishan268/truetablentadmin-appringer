<?php

namespace App\Constants;

interface StringConstants
{
    const WELCOME_MSG = 'Welcome to Truetalent API!';

    const SUCCESSS = 'success';

    /* Company Register Success Message */
    const REGISTER_SUCCESS_MSG = 'Check your email (including Spam folder) for account access instructions';
    /* Login Success Message */
    const LOGIN_SUCCESS_MSG = 'User logged in successfully!';
    /* Log out Success Message */
    const LOGOUT_SUCCESS_MSG = 'User logged out successfully!';

    const SOMETHING_WRONG_MSG = 'We seem to have hit a snag. Please retry in a while.';
    /* Incoorect current password on Reset password */
    const WRONG_PASSWORD_MSG = 'Your current password is incorrect!';
    /* Reset Password  */
    const SAME_PASSWORD_MSG = 'Please enter a password which is not similar then current password!';
    const PASSWORD_SUCCESS_MSG = 'Password updated successfully!';
    /* Forgot Password */
    const WRONG_OTP_MSG = 'Your otp is incorrect!';
    const RESET_PASSWORD_OTP_SUBJECT = 'OTP to reset password!';
    const FORGOT_PASSWORD_OTP_SUBJECT = 'Forgot password OTP!';
    const OTP_SEND_SUCCESS_MSG = 'OTP send successfully!';

    /* Block/Unblock company by candidate */
    const BLOCK_COMPANY_SUCCESS_MSG = 'Company blocked successfully!';
    const UNBLOCK_COMPANY_SUCCESS_MSG = 'Company unblocked successfully!';
    /* Add/Edit Job */
    const JOB_ADD_SUCCESS_MSG = 'Job added successfully!';
    const JOB_UPDATE_SUCCESS_MSG = 'Job updated successfully!';
    
    /* Company Register Error messages */
    const COMPANY_EXISTS_MSG = 'Your company has already been registered by another user. Please check your email for details.';
    const DOMAIN_BLOCKED_MSG = 'You cannot use this domain as per our terms of use, please try with another domain!';

    /* Get User Profile Success */
    const PROFILE_SUCCESS_MSG = 'Profile get successfully!';

    /* Company Dashboard Success */
    const DASHBOARD_DATA_SUCCESS_MSG = 'Dashboard data get successfully!';

    /* Master Data Success */
    const MASTER_DATA_SUCCESS_MSG = 'Master data get successfully!';


    /* Add Skill Success */
    const ADD_SKILL_SUCCESS_MSG = 'Skill added successfully!';
    
    /* Add/Edit workprofile  */
    const WORK_PROFILE_ADD_SUCCESS_MSG = 'Work Profile added successfully!';
    const WORK_PROFILE_UPDATE_SUCCESS_MSG = 'Work Profile updated successfully!';

    /* My Accout messages */
    const PROFILE_UPDATE_SUCCESS_MSG = 'Profile updated successfully!';
    const PREFERENCE_UPDATE_SUCCESS_MSG = 'Preferences updated successfully!';
    const ADD_BLOCK_COMPANY_SUCCESS_MSG = 'Company added and blocked successfully!';
    const GET_COMPANY_SUCCESS_MSG = 'Company list get successfully!';

    /* Job Search page success */
    const JOB_LISTING_SUCCESS_MSG = 'Jobs get successfully!';

    /* My Jobs success */
    const GET_JOBS_SUCCESS_MSG = 'Jobs get successfully!';
    
    /* Job detail actions for candidate */
    const JOB_REPORT_SUCCESS_MSG = 'Job reported successfully!';
    const JOB_APPLY_SUCCESS_MSG = 'Jobs applied successfully!';
    const JOB_SAVE_SUCCESS_MSG = 'Job saved successfully!';
    const JOB_UNSAVE_SUCCESS_MSG = 'Removed from saved jobs successfully!';

    /* Job Detail Actions for company */
    const JOB_DETAIL_SUCCESS_MSG = 'Job details get successfully!';
    const CHANGE_JOB_STATUS_SUCCESS_MSG = 'Job status updated successfully!';
    const JOB_CLOSE_SUCCESS_MSG = 'Job closed successfully!';
    const CHANGE_APPLICANT_STATUS_SUCCESS_MSG = 'Status updated successfully!';
    const INCOMPLETE_JOB_DETAILS = 'Please add all the required job details to publish';
    const JOB_RENEW_SUCCESS_MSG = 'Status renewed successfully!';
    const JOB_DUPLICATE_SUCCESS_MSG = 'Job duplicated successfully!';
    const JOB_END_REFERRAL_SUCCESS_MSG = 'Referral ended successfully!';

    /* Candidate resume and resume upload */
    const UPLOAD_RESUME_SUCCESS_MSG = 'Resume uploaded successfully!';
    const UPLOAD_VIDEO_SUCCESS_MSG = 'Video uploaded successfully!';
    const UPLOAD_USER_IMAGE_SUCCESS_MSG = 'User image uploaded successfully!';

    /* Compny Logo Update Success */
    const UPLOAD_COMPANY_LOGO_SUCCESS_MSG = 'Company logo uploaded successfully!';

    const UPLOAD_COMPANY_COVER_SUCCESS_MSG = 'Company cover pic uploaded successfully!';
    
    const UPLOAD_COMPANY_MEDIA_SUCCESS_MSG = 'Company media uploaded successfully!';

    /* Candidate Search */
    const CANDIDATES_LISTING_SUCCESS_MSG = 'Candidates get successfully!';

    /* Company Admin page messages */
    const COMPANY_USER_ADD_SUCCESS_MSG = 'Users added successfully!';
    const COMPANY_USER_EDIT_SUCCESS_MSG = 'Users updated successfully!';
    const COMPANY_USERS_SUCCESS_MSG = 'Users get successfully!';
    const USER_STATUS_UPDATE_SUCCESS_MSG = 'User status updated successfully!';
    const USER_ROLE_UPDATE_SUCCESS_MSG = 'User role updated successfully!';
    const OFFLINE_PAYMENT_SUCCESS_MSG = 'Your request has been successfully submitted!';
    const ONLINE_PAYMENT_SUCCESS_MSG = ' TT Cash has been successfully added to you company account!';
    const GET_TOKEN_SUCCESS_MSG = 'Token get successfully!';

    /* Contact Us Success Message */
    const CONTACT_US_SUCCESS_MSG = 'Your message sent successfully, Admin will contact you shortly!';

    /* Graph Data Success */
    const GRAPH_DATA_SUCCESS_MSG = 'Graph data get successfully!';

    /* Homepage logo Success */
    const HOMEPAGE_LOGO_SUCCESS_MSG = 'Homepage logo get successfully!';

    /* Company details Success Messages */
    const COMPANY_DETAIL_SUCCESS_MSG = 'Company details get successfully!';
    const COMPANY_DETAIL_ADDED_SUCCESS_MSG = 'Company details added successfully!';
    const COMPANY_DETAIL_UPDATED_SUCCESS_MSG = 'Company details updated successfully!';
    const TOP_5_SKILLS = "Top 5 skills";
    const DATA_BY_SKILLS = "Data by skills";

    const TOP_5_LOCATIONS = "Top 5 locations";
    const DATA_BY_LOCATIONS = "Data by locations";

    /* Add/Edit Gig */
    const GIG_ADD_SUCCESS_MSG = 'Gig added successfully!';
    const GIG_UPDATE_SUCCESS_MSG = 'Gig updated successfully!';

    /* Gig Detail Actions for company */
    const GIG_DETAIL_SUCCESS_MSG = 'Gig details get successfully!';
    const CHANGE_GIG_STATUS_SUCCESS_MSG = 'Gig status updated successfully!';
    const GIG_CLOSE_SUCCESS_MSG = 'Gig closed successfully!';
    const GET_GIGS_SUCCESS_MSG = 'Gigs get successfully!';
    const GIG_LISTING_SUCCESS_MSG = 'Gigs get successfully!';

    /* Gig Detail Actions for candidate */
    const GIG_REPORT_SUCCESS_MSG = 'Gig reported successfully!';
    const GIG_APPLY_SUCCESS_MSG = 'Gig applied successfully!';
    const GIG_SAVE_SUCCESS_MSG = 'Gig saved successfully!';
    const GIG_UNSAVE_SUCCESS_MSG = 'Removed from saved gigs successfully!';
    const INCOMPLETE_GIG_DETAILS = 'Please add all the required gig details to publish';
    const GIG_DUPLICATE_SUCCESS_MSG = 'Gig duplicated successfully!';

    /* Chat Messages */
    const BLOCK_USER_SUCCESS_MSG = 'User blocked successfully!';
    const UNBLOCK_USER_SUCCESS_MSG = 'User unblocked successfully!';
    const REPORT_USER_SUCCESS_MSG = 'User reported successfully!';
    const REPORT_AND_BLOCK_USER_SUCCESS_MSG = 'User reported and blocked successfully!';

    const CHAT_MUTE_SUCCESS_MSG = 'Chat muted successfully!';
    const CHAT_UNMUTE_SUCCESS_MSG = 'Chat unmuted successfully!';

    const MESSAGE_LIMIT_ERROR = 'The message field is cannot be greater than 1000 characters!';
    const MEDIA_MESSAGE_LIMIT_ERROR = 'The media file that you have selected is larger than 16 MB limit.';

    const TRANSACTIONS_EMAIL_SUCCESS_MSG = 'Please check your email, we have emailed you the file.';

    const EMAIL_RESEND_SUCCESS_MSG = "Verification email sent successfully";
    
    const INVITATION_SEND_SUCCESS_MSG = "Invitation sent successfully";
    

    const ACCOUNT_DELETE_SUCCESS_MSG = "Accout deleted successfully";


    const OTP_VERIFY_SUCCESS_MSG = "OTP verified successfully";
    const OTP_VERIFY_ERROR_MSG = "Invalid OTP";
    const EMAIL_ALREADY_VERIFIED_MSG = "Email already verified";
    const EMAIL_VERIFY_SUCCESS_MSG = "Email verified successfully";

    /*Error Messages*/

    /* Login Errors */
    const ACCOUNT_DELETED_LOGIN_MSG = 'Your account has been deleted!';
    const ACCOUNT_RESTORE_SUCCESS_MSG = 'Your account has been restored successfully!';
    const LOGIN_ERROR_MSG = 'Incorrect email or password!';
    const COMPANY_DEACTIVATE_ERROR_MSG = 'Your company has been deactivated by admin!';
    const ACCOUNT_DEACTIVATE_ERROR_MSG = 'Your account has been deactivated by admin!';
    const EMAIL_VERIFY_ERROR_MSG = 'Please verify your email to proceed!';
    const MOBILE_VERIFY_ERROR_MSG = 'Please verify your mobile to proceed!';

    /* Upload media Error */
    const UPLOAD_MEDIA_TYPE_ERROR_MSG = 'Media type is not valid!';

    /* Company User visits candidate profile page error messages  */
    const ACCESS_DENIED_ERROR_MSG = 'Access Denied!';
    const NO_TT_CASH_ERROR_MSG = "You don't have enough TT-Cash to review this work profile!";

    /* Show error to candidate if workprofile is incomplete and candidate tries to apply for job  */
    const WORK_PROFILE_COMPLETE = "Please complete your workprofile to apply for the jobs!";

    /* Invalid email and website on registration */
    const INVALID_WEBSITE_ERROR_MSG = "Please enter valid website!";
    const INVALID_EMAIL_ERROR_MSG = "Please enter valid email!";

    /* Error on different domain of email and company website */
    const DIFFERENT_EMAIL_WEBSITE_DOMAIN_ERROR_MSG = "Your email address and website domain do not match. In line with our security policy, TrueTalent Support Team will reach out to you to create your account.";
    const DIFFERENT_USER_EMAIL_COMPANY_WEBSITE_DOMAIN_ERROR_MSG = "You are trying to add a user whose email domain does not match your company domain. Our support team will reach out to you for further assistance.";

    /* User not found on login and forgot password */
    const USER_NOT_FOUND_MSG = 'Unable to find user with this email, please create a new account!';


    /* Boost Job Error */

    const REFERRAL_EXISTS_FOR_JOB = "Referral already exists for this job";


    const NOT_FOUND = "Not found!";
    const COMPANY_DELETE_LOGIN_MSG = "Your company account has been deleted. Please contact your company admin.";
    const COMPANY_ADMIN_DELETE_LOGIN_MSG = "Your company account has been deleted by the support team as per your request. If you wish to recover your account, you can do so within 30 days of your initial request by contacting the support team.";


    /* Workprofile View Error Messages */

    const COMPANY_BLOCKED_BY_USER = "Your company has been blocked by user";
    const WORKPROFILE_NOT_EXISTS  = "Workprofile doesn't Exists";
    const WORKPROFILE_INCOMPLETE  = "User workprofile is incomplete";
    const NOT_AUTHORIZE_TO_VIEW  = "You are not authorize to view this workprofile";
}

