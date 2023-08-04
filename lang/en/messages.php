<?php

return [
    'validation' => [
        'not_found' => ' not found',
        'first_name' => 'Please enter first name',
        'last_name' => 'Please enter last name',
        'name' => 'Please enter name',
        'max_name' => 'Name can not be more than 255 characters',
        'email' => 'Please enter email',
        'email_email' => 'Invalid email address',
        'email_unique' => 'Email address is already registered. Please, use a different email',
        'mobile' => 'Please enter mobile',
        'mobile_digits' => 'Mobile should be 10 digit number',
        'mobile_unique' => 'Mobile number is already registered. Please, use a different mobile',
        'password' => 'Please enter password',
        'strong_password' => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.',
        'role_id' => 'Please enter role',
        'status' => 'Please enter status',
        'status_in' => 'Status should be 0 or 1',
        'image' => 'Please select image',
        'image-max' => 'Please select below 2 MB images',
        'image-mimes' => 'Please select only jpg, png, jpeg files',
        'user_type' => 'Please enter user type',
        'user_type_in' => 'User type value must 1 or 2',
        'type' => 'Please enter type',
        'type_lte' => 'Type should be 0 or 1',
        'old_password' => 'Please enter old password',
        'new_password' => 'Please enter new password',
        'id' => 'Please enter id',
        'mobile_password_wrong' => 'Mobile or password you entered did not match our records.',
        'user_not_found' => 'User not found',
        'unique_code' => 'Please enter unique code',
        'unique_code_unique' => 'This unique code already used',
        'must_numeric' => ' must be numeric',
        'title' => 'Please enter title',
        'subject' => 'Please enter subject',
        'message' => 'Please enter message',
        'alpha_num' => ' only contain letters and numbers',
        'max' => 'Max 200 characters allow',
        'password_confirmed' => 'The password confirmation does not match.',
        'password_min' => "Invalid password",
        'title_invalid' => 'Title is invalid',
        'password_confirmation' => 'Please enter password confirmation',
        'password_confirmation_same' => 'The password confirmation does not match',
        'old_password_incorrect' => 'The old password is incorrect.',
        'new_password_min' => 'Password must 8 character.',
        'factor_required' => 'Please enter factor',
        'factor_numeric' => 'Factor must be a number or decimal',
        'skill_required' => 'Please select skill',
    ],
    'item' => [
        'uom_id_required' => 'Please enter unit of measurement id',
        'item_category_id_required' => 'Please enter item category id',
        'price_required' => 'Please enter Price',
        'is_vendor_required' => 'Please enter is vendor',
        'is_vendor_numeric' => 'Is vendor value must be numeric',
        'user_id_required' => 'Please enter vendor id',
    ],
    'signup' => [
        'addressproof' => 'Please select addressproff',
        'addressproof-max' => 'Please select below 2 MB images',
        'addressproof-mimes' => 'Please select only jpg, png, jpeg files',
        'idproof' => 'Please select idproof',
        'idproof-max' => 'Please select below 2 MB images',
        'idproof-mimes' => 'Please select only jpg, png, jpeg files',
        'resume' => 'Please select resume',
        'resume-max' => 'Please select below 2 MB file',
        'resume-mimes' => 'Please select only pdf files',
        'state_id' => 'Please enter state_id',
        'address' => 'Please enter address',
        'city' => 'Please enter city',
    ],
    'auth' => [
        'login_failed' => 'These credentials do not match our records.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'password_reset_link_success' => 'Reset password mail send to your registered mail.',
        'password_reset_link_failed' => 'Password reset link failed, Something went wrong!!',
        'token' => 'Token field is required',
        'password_reset_success' => 'Your password has been changed successfully. Please log in to continue.',
        'password_reset_failed' => 'Password reset failed, Something went wrong!!',
        'account_not_approved' => 'Your account is not active please contact admin.',
        'invalid_otp' => "Invalid otp or opt expired",
        'account_not_verified' => "Account is not verified, Please verfiy you account.",
        'account_already_verified' => "Account already verified",
    ],

    'success' => [
        'details' => ' details fetch successfully',
        'create' => ' created successfully',
        'update' => ' updated successfully',
        'delete' => ' deleted successfully',
        'password_reset' => 'Password reset successfully',
        'old_password_wrong' => "Old Password Doesn't match",
        'user_logout' => 'User logout successfully',
        'user_login' => 'User successfully logged in',
        'list' => ' list fetch successfully',
        'password_reset' => "Passowrd reset successfully.",
        'forgot_password_otp' => 'Forgot password OTP sent on your email.',
        'account_verified' => 'Congratulations! Your account has been successfully verified',
        'verification_otp_Send_successfully' => 'Verification otp send successfully on registered email',
        'register_successfully' => 'Your registration was successful! Please verify your email using the sent OTP',
    ],
    'failed' => [
        'general' => 'Something went wrong!!',
    ]
];
