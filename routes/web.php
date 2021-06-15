<?php


Route::get('/', function () {
    return view('users.login');
});
// route for user controller
Route::get('user-registration',[
	'uses'=>'User\UserController@showRegistrationForm',
	'as'  =>'user-registration'
]);

Route::post('user-registration',[
	'uses'=>'User\UserController@userSave',
	'as'  =>'user-save'
]);

Route::post('user-update',[
	'uses'=>'User\UserController@userUpdate',
	'as'  =>'user-update'
]);
Route::post('change-password',[
	'uses'=>'User\UserController@password_save',
	'as'  =>'password-save'
]);

//route for user
Route::get('user-list','User\UserController@user_list')->name('user-list');
Route::post('user-info','User\UserController@user_data');
Route::get('user-edit/{id}','User\UserController@user_edit');
Route::post('user-delete','User\UserController@user_delete');
Route::post('get_username','User\UserController@get_username');
Route::get('change-password','User\UserController@change_password');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// route for client controller
Route::get('client/create','ClientController@create')->name('client.create');
Route::get('client/client_update/{id}','ClientController@client_update')->name('client.client_update');
Route::get('client/client_profile/{id}','ClientController@client_profile')->name('client.profile');
Route::post('client/client_insert','ClientController@client_insert')->name('client.insert');
Route::post('client/client_delete','ClientController@client_delete')->name('client.delete');
Route::post('client/client_data_show','ClientController@client_data_show')->name('client.client_data_show');
Route::post('client/client_edit','ClientController@client_edit');
Route::get('client/client_show','ClientController@client_show')->name('client.client_show');
Route::get('client/show_upazilla','ClientController@show_upazilla')->name('client.show_upazilla');
Route::get('client/client_data_pdf','ClientController@client_data_pdf')->name('client.client_data_pdf');

// route for billprepare controller
Route::get('billprepare/create_bill','BillprepareController@create_bill')->name('billprepare.create_bill');
Route::get('billprepare/bill_update/{id}','BillprepareController@bill_update')->name('billprepare.bill_update');

Route::post('generate-bill','BillprepareController@bill_generate');
Route::get('bill/payment','BillpaymentController@payment')->name('billpayment.payment');
Route::post('payment-bill','BillpaymentController@bill_payment');
Route::get('billpayment/money-receipt','BillpaymentController@money_receipt_list')->name('billpayment.money_receipt');
Route::post('payment/money_receipt_show','BillpaymentController@money_receipt_list_show')->name('payment.money_receipt_list_show');
Route::get('billpayment/money_receipt_view/{id}','BillpaymentController@money_receipt_view')->name('payment.money_receipt_view');

Route::get('billprepare/ajax_client','BillprepareController@ajax_client')->name('billprepare.ajax_client');
Route::get('client/get_union_list','ClientController@get_union_list')->name('client.get_union_list');

Route::get('billprepare/bill_show','BillprepareController@bill_show')->name('billprepare.bill_show');
Route::post('billprepare/bill_list_show','BillprepareController@bill_list_show')->name('billprepareclientbillprepare.bill_list_show');
Route::get('billprepare/get_fee_info','BillprepareController@get_fee_info');
Route::post('billpayment/get_payment_fee_info','BillpaymentController@get_payment_fee_info');
Route::post('billprepare/get_monthly_fee_info','BillprepareController@get_monthly_fee_info');
Route::post('billprepare/bill_delete','BillprepareController@bill_delete')->name('billprepare.delete');
Route::get('billprepare/bill_view/{id}','BillprepareController@bill_view')->name('billprepare.bill_view');
Route::post('billprepare/sms_save','BillprepareController@sms_save')->name('billprepare.sms_save');
Route::post('billprepare/email_save','BillprepareController@email_save')->name('billprepare.email_save');

//route for sms,email process
Route::get('process/sms','Process@sms')->name('process.sms');
Route::get('process/email','Process@email')->name('process.email');

//route for communication
Route::get('communication/report_show','CommunicationController@report_show')->name('communication.report_show');
Route::post('communication/search_report_list_show','CommunicationController@search_report_list_show')->name('communication.serach_report_list_show');
Route::get('communication/quicksms_show','CommunicationController@quicksms_show')->name('communication.quicksms_show');
Route::post('communication/quicksms_insert','CommunicationController@quicksms_insert')->name('communication.quicksms_insert');


// Report daily_collection

Route::get('report/daily_collection','ReportController@daily_collection')->name('report.daily_collection');
Route::post('report/daily_collection_report','ReportController@daily_collection_report')->name('report.daily_collection_report');
Route::get('report/monthly_collection','ReportController@monthly_collection')->name('report.monthly_collection');
Route::post('report/monthly_collection_report','ReportController@monthly_collection_report')->name('report.monthly_collection_report');
Route::post('report/monthly_collection_report','ReportController@monthly_collection_report')->name('report.monthly_collection_report');

Route::get('report/accounts_wise_collection','ReportController@accounts_wise_collection')->name('report.accounts_wise_collection');
Route::post('report/accounts_report','ReportController@accounts_report')->name('report.accounts_report');


// Support Ticket 
Route::get('support/new_ticket', 'SupportCornerController@new_ticket')->name('support.new_ticket');
Route::post('support/corner/send_support', 'SupportCornerController@support_save')->name('support.support_save');
Route::get('support/reports', 'SupportCornerController@support_reports')->name('support.support_reports');
Route::post('support/get_list', 'SupportCornerController@get_support_list')->name('support.get_support_list');
Route::post('support/ticket_assign_save', 'SupportCornerController@ticket_assign_save')->name('support.ticket_assign_save');
Route::post('support/ticket_close_save', 'SupportCornerController@ticket_close_save')->name('support.ticket_close_save');
Route::post('support/re_open_ticket', 'SupportCornerController@re_open_ticket')->name('support.re_open_ticket');
Route::post('support/remove_assign', 'SupportCornerController@remove_assign')->name('support.remove_assign');

Route::get('support/view_ticket/{id}', 'SupportCornerController@view_ticket')->name('support.view_ticket');

//route for conversation controller
Route::get('conversation/show','ConversationController@show')->name('conversation.show');
Route::get('conversation/client_search','ConversationController@client_search')->name('conversation.client_search');
Route::get('conversation/show_conversation','ConversationController@show_conversation')->name('conversation.show_conversation');
Route::post('conversation/conversation_insert','ConversationController@conversation_insert')->name('conversation.conversation_insert');
Route::get('conversation/client_name','ConversationController@client_name')->name('conversation.client_name');

//route for activity log of users
Route::get('activity/show','ActivityController@show')->name('activity.show');
Route::get('activity/user_search','ActivityController@user_search')->name('activity.user_search');
Route::get('activity/show_conversation','ActivityController@show_conversation')->name('activity.show_conversation');
Route::get('activity/user_name','ActivityController@user_name')->name('activity.user_name');