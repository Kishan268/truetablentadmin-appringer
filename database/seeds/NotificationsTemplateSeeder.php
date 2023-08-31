<?php

use Illuminate\Database\Seeder;

class NotificationsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            [
                'key' => 'company_registration_faild',
                'name' => 'Email or website diffrent domain when we register company',
                'subject' => 'Issue with Company Registration',
                'variables' => '$first_name,$last_name,$email,$contact,$company_name,$website,$location_name,$company_size_name,$industry_domain_name',
                'mail_body' => 'Hi $first_name $last_name,<br /><br />

                                Your email address and website domain do not match. In line with our security policy, TrueTalent Support Team will reach out to you to create your account.</b> <br />

                                Your name is:$first_name $last_name </b> <br />
                                Your email is: $email </b> <br />
                                Your mobile number is: $contact </b> <br />
                                Your company name is: $company_name </b> <br />
                                Your company website is: $website </b> <br />
                                Your location is: $location_name </b> <br />
                                Your company size is: $company_size_name </b> <br />
                                Your industry domain is: $industry_domain_name </b> <br />


                                Thank you for signing with,',
            ],
            [
                'key' => 'company_register',
                'name' => 'Company Registration',
                'subject' => 'Confirm your account!',
                'variables' => '$name',
                'mail_body' => 'Dear $name .<br /><br />

                    It’s our pleasure to on-board you as a client user on <a href="https://truetalent.io/" >TrueTalent.io</a>, India`s First RaaS (Recruitment as a Service) Talent Search Platform.<br /><br />

                    Your registration process is successfully completed, and you are all set to search for the best candidates on TrueTalent platform.<br /><br />

                    Our candidate search feature, including viewing candidates’ profiles and contacting them, is absolutely free of charge.<br />

                    Besides searching for candidates, you could also post your job requirements free of charge.</b><br /><br />

                    
                    However we have great ways to highlight your organisation and your job postings on the homepage of TrueTalent, that gets <b>25X</b> higher traffic, at a small cost. In case you are keen on exploring these features you are requested to email us at <a href="mailto:maya@truetalent.io">maya@truetalent.io</a><br />

                    We look forward to a successful hiring journey for your organisation on the TrueTalent platform.',
            ],
            [
                'key' => 'candidates_register',
                'name' => 'Candidates Registration',
                'subject' => 'Confirm your account!',
                'variables' => '$name',
                'mail_body' => 'Hi $name,<br/>
                   Welcome to <b>TrueTalent</b> - India`s first <b>RaaS</b> (Recruitment as a Service) Platform. We are excited to have you on board.<br /><br />
                        We look forward to you exploring our portal and finding the best job that suit your skills.<br /><br />
                        I wish you all the best in your job search!<br /><br />
                        I would love to hear what you think of TrueTalent and if there is anything we can improve. If you have any questions, please reply to this email. I will be happy to help!<br /><br />',
            ],
            [
                'key' => 'evaluator_register',
                'name' => 'Evaluator Registration',
                'subject' => 'Confirm your account!',
                'variables' => '$name',
                'mail_body' => 'Hi $name,<br/>
                    Welcome to <b>TrueTalent</b> - India`s first <b>RaaS</b> (Recruitment as a Service) Platform. We are excited to have you on board.<br /><br />
                        We look forward to you exploring our portal and help campanies to find the best candidates.<br /><br />
                        I would love to hear what you think of TrueTalent and if there is anything we can improve. If you have any questions, please reply to this email. I will be happy to help!<br /><br />',
            ],
            [
                'key' => 'incomplete_profile',
                'name' => 'Incomplete Profile',
                'subject' => 'Profile Incomplete Reminder',
                'variables' => '$first_name,$last_name',
                'mail_body' => 'Dear $first_name,$last_name.<br/><br/>


                Please complete your profile to continue your journey with TrueTalent.

                Thank you for signing with'
            ],
            [
                'key' => 'send_job_apply',
                'name' => 'Send Job Apply',
                'subject' => 'New applicant for job',
                'variables' => '$recruiter_name',
                'mail_body' => 'Dear $recruiter_name.<br /><br />

                Your job posting has new applicants. Please visit the link below to view the new applicants and contact them if you find their resume suitable for your open position(s).<br /><br />'
            ],
            [
                'key' => 'send_invitation_comapany',
                'name' => 'Send Invitation Comapany',
                'subject' => 'is inviting you to apply for an exciting job on TrueTalent',
                'variables' => '$recruiter_name',
                'mail_body' => 'Dear $recruiter_name.<br/>
                    We have been using <span style="color: #14BC9A">TrueTalent</span> platform to fulfill our current talent needs and have seen a significant value add to our hiring process both in terms of time and cost.<br /><br />

                    We would love to see you there and are sure <span style="color: #14BC9A">TrueTalent’s</span> innovative platform offerings will positively impact your hiring strategies.<br /><br />

                    So do click on the below link and register to search for some of the best talent in the industry.<br /><br />

                    PS: Do not miss out on clicking this link, since you registering through the below link will not just open up a whole lot of new features for us, but will also help you get pro features at a highly discounted price because of our referral code.<br /><br />'
            ],
            [
                'key' => 'send_invitation_other',
                'name' => 'Send Invitation Other',
                'subject' => 'is inviting you to apply for an exciting job on TrueTalent',
                'variables' => '$recruiter_name',
                'mail_body' => 'Dear $recruiter_name.<br /><br />
                   I have come across some exciting job opportunities with companies of different sizes on <span style="color: #14BC9A">TrueTalent</span> and I strongly believe your skills & experience matches a number of those. Besides, I believe this could be a great career move for you in applying to them.<br /><br />

                        Please click on the below link and register on <span style="color: #14BC9A">TrueTalent</span> to apply for these fantastic opportunities.<br /><br />

                        PS: Do not miss out on clicking this link, since you applying for jobs through the link will help me earn some great rewards. You too could earn extraordinary rewards by referring friends once you are on the <span style="color: #14BC9A">TrueTalent</span> platform.<br /><br />'
            ],
            [
                'key' => 'send_otp',
                'name' => 'Send Otp',
                'subject' => 'Forgot password OTP!',
                'variables' => '$user_name,$otp',
                'mail_body' => 'Dear $user_name.<br /><br />
                    We understand you have requested a reset of your password.<br />
                    The OTP for resetting your password is $otp .<br /><br />
                    We request that you not share this OTP or your password with anyone.<br />
                    PS: If you have not requested a password change, please ignore this email and login using your existing password.<br />

                    Best Regards,<br /> '
            ],
            [
                'key' => 'user_created',
                'name' => 'User Created',
                'subject' => 'Your account was successfully created.',
                'variables' => '$name,$company_admin,$email,$password',
                'mail_body' => 'Dear $name.<br /><br />
                   You have been added as a user to <a href="">TrueTalent.io</a> by your company admin,$company_admin.
                    It`s our pleasure to on-board you as a client user on <a href="">TrueTalent.io</a>, India`s First RaaS (Recruitment as a Service) Talent Search Platform.<br /><br />

                    You are all set to search for the best candidates on TrueTalent platform by using the below credentials.<br /><br />

                    Your login email is: $email <br />
                    Your login password is: $password <br />

                    Our candidate search feature, including viewing candidates’ profiles and contacting them, is absolutely free of charge.<br />
                    Besides searching for candidates, you could also post your job requirements free of charge.<br /><br />


                    However we have great ways to highlight your organization and your job postings on the homepage of TrueTalent, that gets 25X higher traffic, at a small cost. In case you are keen on exploring these features you are requested to email us at <a href="mailto:maya@truetalent.io">maya@truetalent.io</a>
                    We look forward to a successful hiring journey for your organization on the TrueTalent platform.<br />

                    Click the button below to login into your account.'
            ],
            [
                'key' => 'user_registration_request',
                'name' => 'User Registration Request',
                'subject' => 'Issue with User Registration',
                'variables' => '$reciepient_first_name,$reciepient_last_name,$first_name,$last_name,$email',
                'mail_body' =>'Hi $reciepient_first_name $reciepient_last_name,<br /><br />

                        You are trying to add a user whose email id domain and website domain (as registered by your organisation) do not match. In line with our security policy, the TrueTalent Support Team will reach out to you to create your account.<br /><br />


                        User name is: $first_name $last_name<br />
                        User email is: $email<br /><br />


                        Thank you for doing business with'
            ],
            [
                'key' => 'verification_reminder',
                'name' => 'Email Verification Reminder',
                'subject' => 'Email Verification Reminder',
                'variables' => '$first_name,$last_name',
                'mail_body' => 'Hi $first_name $last_name,<br /><br />

                    Please verify your email to continue your journey with TrueTalent.<br /><br />


                    Thank you for signing with '
            ],
            [
                'key' => 'company_exist_admin',
                'name' => 'Company Exist Admin',
                'subject' => 'Re-Registration of Company',
                'variables' => '$company_admin_name,$user_name,$user_email',
                'mail_body' => 'Hi $company_admin_name,<br /><br />

                                We are reaching out to you to let you know that another user from your company has been trying to join our platform. Since you are the admin for your company, we request you to active the user for them to start using our services and hire the best talent.<br /><br />

                                The details of the user are given below <br /><br />
                                Name of the new user: $user_name <br />
                                Email of the new user: $user_email <br />
                                Best Regards, <br />'
            ],
            [
                'key' => 'contact_us',
                'name' => 'Contact Us',
                'subject' => 'A new TrueTalent contact form submission!',
                'variables' => '$name,$email,$phone,$company_name,$message',
                'mail_body' => 'You have a new contact form request: Below are the details:<br /><br />
                                Full Name: $name <br /><br />
                                E-mail Address: $email <br /><br />
                                Phone: $phone <br /><br />
                                Company Name: $company_name <br /><br />
                                Message: $message <br /><br />'
            ]
        ];
        foreach ($values as $value) {
            $data = [
                'key'        => $value['key'],
                'name'       => $value['name'],
                'subject'    => $value['subject'],
                'variables'  => $value['variables'],
                'variables'  => $value['variables'],
                'mail_body'  => $value['mail_body'],
                'is_mail_enabled'  => '1',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            DB::table('notification_settings')->insert($data);
        }
    }
}
