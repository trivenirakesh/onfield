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
        'status_lte' => 'Status should be 0 or 1',
        'image' => 'Please select image',
        'image-max' => 'Please select below 2 MB images',
        'image-mimes' => 'Please select only jpg, png, jpeg files',
        'entity_type' => 'Please enter entity type',
        'entity_type_digits' => 'Entity type value must be numeric',
        'entity_type_lte' => 'Entity type value must between 0 and 2',
        'type' => 'Please enter type',
        'type_lte' => 'Type should be 0 or 1',
        'old_password' => 'Please enter old password',
        'new_password' => 'Please enter new password',
        'id' => 'Please enter id',
        'email_password_wrong' => 'Email or password you entered did not match our records.',
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
    ],
    'service' => [
        'uom_id_required' => 'Please enter unit of measurement id',
        'item_category_id_required' => 'Please enter item category id',
        'price_required' => 'Please enter Price',
        'is_vendor_required' => 'Please enter is vendor',
        'is_vendor_numeric' => 'Is vendor value must be numeric',
        'vendor_id_required' => 'Please enter vendor id',
    ],
    'auth' => [
        'login_failed' => 'These credentials do not match our records.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        'password_reset_link_success' => 'Reset password mail send to your registered mail.',
        'password_reset_link_failed' => 'Password reset link failed, Something went wrong!!',
        'token' => 'Token field is required',
        'password_reset_success' => 'Your password has been changed successfully. Please log in to continue.',
        'password_reset_failed' => 'Password reset failed, Something went wrong!!',
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
    ],
    'failed' => [
        'general' => 'Something went wrong!!',
    ]
];
