<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'App\Http\Middleware\ApiMidd', 'prefix' => 'v1'], function() {
	Route::get('/foo/{api_key}', function (Request $request) {
		dd(json_decode('"1":"1000001", "3":"10002001"'));
	});
	Route::get('authentication_email/{api_key}', 'UserController@api_authentication_email');
	Route::get('check_email_code/{api_key}', 'UserController@api_check_email_code');

	Route::post('user_register/{api_key}', 'UserController@api_register');
	Route::post('reset_password/{api_key}', 'UserController@api_reset_password');
	Route::get('association_list/{api_key}', 'CategoryController@api_list');
	Route::get('careers_list/{api_key}', 'CareerController@api_list');
	Route::get('faculty_list/{api_key}', 'FacultyController@api_list');
	Route::get('association_check/{api_key}', 'MemberController@api_check');
	Route::post('user_login/{api_key}', 'UserController@api_login');

	Route::get('province_list/{api_key}', 'UserController@api_list_province');
	Route::get('versions/{api_key}', 'VersionController@api_version');
	Route::get('versions_android/{api_key}', 'VersionController@api_version_android');
	

	Route::group(['middleware' => 'App\Http\Middleware\ApiAuth', 'prefix' => ''], function() {
		Route::post('user_device_token/{api_key}', 'UserController@api_user_device_token');
		Route::post('user_edit/{api_key}', 'UserController@api_edit');
		Route::get('user_info/{api_key}', 'UserController@api_info');
		Route::get('user_logout/{api_key}', 'UserController@api_logout');
		Route::post('user_avatar_set/{api_key}', 'UserController@api_avatar_set');
		Route::post('change_password/{api_key}', 'UserController@api_change_password');
		Route::post('notification_view/{api_key}', 'UserController@out_time');
		Route::get('notification/{api_key}', 'NotificationController@api_list');
		Route::get('notification_list_all/{api_key}', 'NotificationController@api_list_all');




		Route::get('new_category_list/{api_key}', 'CategoryNewController@api_list');
		Route::get('new_list/{api_key}', 'NewController@api_list');
		Route::get('new_detail/{api_key}', 'NewController@api_detail');
		Route::post('new_mypage_set/{api_key}', 'NewController@api_mypage_set');
		Route::post('new_like_set/{api_key}', 'NewController@api_like_set');

		Route::post('new_add_comment/{api_key}', 'NewController@api_add_comment');
		Route::post('new_edit_comment/{api_key}', 'NewController@api_edit_comment');
		Route::post('new_delete_comment/{api_key}', 'NewController@api_delete_comment');
		Route::post('new_comment_like_set/{api_key}', 'NewController@api_comment_like_set');

		Route::get('association_list_by_user/{api_key}', 'CategoryController@api_list_by_user');
		Route::get('channel_list_category/{api_key}', 'ChannelController@api_list_category');
		
		Route::get('channel_list_by_association/{api_key}', 'ChannelController@api_list_by_association');
		Route::get('channel_list_by_top/{api_key}', 'ChannelController@api_list_by_top');
		Route::post('channel_like_set/{api_key}', 'ChannelController@api_like_set');
		Route::post('channel_mypage_set/{api_key}', 'ChannelController@api_mypage_set');

		Route::get('channel_list_month_expect/{api_key}', 'ChannelController@api_list_month_expect');
		Route::get('channel_list_expect_publish/{api_key}', 'ChannelController@api_list_expect_publish');

		Route::get('video_list_by_channel/{api_key}', 'VideoController@api_list_by_channel');
		Route::get('video_detail/{api_key}', 'VideoController@api_video_detail');
		Route::post('video_add_view/{api_key}', 'VideoController@api_video_add_view');
		Route::post('video_like_set/{api_key}', 'VideoController@api_like_set');
		Route::get('video_list_question/{api_key}', 'VideoController@api_list_question');
		Route::post('video_add_answer/{api_key}', 'VideoController@api_add_answer');
		Route::post('video_close_question/{api_key}', 'VideoController@api_close_question');

		Route::get('video_list_by_top/{api_key}', 'VideoController@api_list_by_top');

		Route::get('rss_list_month/{api_key}', 'RssController@api_list_month');
		Route::get('rss_list_by_month/{api_key}', 'RssController@api_list_by_month');
		Route::get('rss_list_limit/{api_key}', 'RssController@api_list_limit');

		Route::get('topic_list_month/{api_key}', 'TopicController@api_list_month');
		Route::get('topic_list_by_month/{api_key}', 'TopicController@api_list_by_month');
		Route::get('topic_list_limit/{api_key}', 'TopicController@api_list_limit');
		Route::get('topic_detail/{api_key}', 'TopicController@api_detail');

		Route::get('discusstion_top_by_association/{api_key}', 'DiscussionController@api_top_by_association');
		Route::get('discusstion_list_by_association/{api_key}', 'DiscussionController@api_list_by_association');
		Route::get('discusstion_detail/{api_key}', 'DiscussionController@api_detail');
		Route::post('discusstion_add/{api_key}', 'DiscussionController@api_add');
		Route::post('discusstion_edit/{api_key}', 'DiscussionController@api_edit');
		Route::post('discusstion_delete/{api_key}', 'DiscussionController@api_delete');
		Route::post('discusstion_like_set/{api_key}', 'DiscussionController@api_like_set');
		Route::post('discusstion_comment_like_set/{api_key}', 'DiscussionController@api_like_comment_set');

		
		Route::post('discusstion_add_comment/{api_key}', 'DiscussionController@api_add_comment');
		Route::post('discusstion_edit_comment/{api_key}', 'DiscussionController@api_edit_comment');
		Route::post('discusstion_delete_comment/{api_key}', 'DiscussionController@api_delete_comment');
		Route::post('discusstion_add_report_comment/{api_key}', 'DiscussionController@api_add_report_comment');
		Route::post('discussion_mypage_set/{api_key}', 'DiscussionController@api_mypage_set');
		Route::get('authentication_discussion/{api_key}', 'DiscussionController@api_authentication');

		Route::get('seminar_by_assosication/{api_key}', 'SeminarController@api_by_association');
		Route::get('seminar_ads/{api_key}', 'SeminarController@api_banner_ads');
		Route::get('event_list_by_day/{api_key}', 'EventController@api_list_by_day');
		Route::get('event_detail/{api_key}', 'EventController@api_detail');
		Route::post('event_mypage_set/{api_key}', 'EventController@api_mypage_set');
		
		Route::get('mypage_new_list/{api_key}', 'NewController@api_mypage_list');
		Route::get('mypage_channel_list/{api_key}', 'ChannelController@api_mypage_list');
		Route::get('mypage_discussion_list/{api_key}', 'DiscussionController@api_mypage_list');
		Route::get('mypage_seminar_list/{api_key}', 'SeminarController@api_mypage_list');
		Route::get('mypage_event_list_by_day/{api_key}', 'EventController@api_mypage_list_by_day');
		Route::get('mypage_event_list_day/{api_key}', 'EventController@api_mypage_list_day');

		Route::get('event_list_day/{api_key}', 'EventController@api_list_day');


		Route::post('content_add_view/{api_key}', 'AnalyticController@api_content_add_view');
		Route::post('channel_add_view/{api_key}', 'AnalyticController@api_channel_add_view');
		Route::post('new_add_view/{api_key}', 'AnalyticController@api_new_add_view');
		
		Route::post('rss_read/{api_key}', 'NotificationController@api_rss_read');
		Route::post('channel_read/{api_key}', 'NotificationController@api_channel_read');
		Route::post('content_read/{api_key}', 'NotificationController@api_content_read');
		Route::post('discussion_read/{api_key}', 'NotificationController@api_discussion_read');

		Route::get('event_list_by_calendar/{api_key}', 'EventController@api_list_by_calendar');
		Route::get('event_search/{api_key}', 'EventController@api_search');
		Route::get('event_list_by_category/{api_key}', 'EventController@api_list_by_category');
		Route::get('event_list_by_theme/{api_key}', 'EventController@api_list_by_theme');
		Route::get('event_list_by_global/{api_key}', 'EventController@api_list_by_global');
		Route::get('event_list_by_contact/{api_key}', 'EventController@event_list_by_contact');
		Route::get('event_list_by_name/{api_key}', 'EventController@event_list_by_name');

		Route::get('event_list_ranking/{api_key}', 'EventController@api_list_ranking');
		Route::post('event_set_rating/{api_key}', 'EventController@event_set_rating');

		Route::post('event_add_view/{api_key}', 'AnalyticController@api_event_add_view');
		Route::post('banner_add_view/{api_key}', 'AnalyticController@api_banner_add_view');

		Route::post('seminar_read/{api_key}', 'NotificationController@api_event_read');
		Route::get('notification_seminar_list/{api_key}', 'NotificationController@notification_seminar_list');
		Route::get('notification_seminar_count/{api_key}', 'NotificationController@notification_seminar_count');





		Route::get('generate-pdf/{api_key}', 'EventController@generatePDF');

		Route::post('video_stops/{api_key}', 'VideoController@api_video_stops');

		Route::post('request_confirm/{api_key}', 'UserController@api_request_confirm');
		Route::get('check_assosication_login/{api_key}', 'UserController@api_check_assosication_login');

		Route::post('user_register_back/{api_key}', 'UserController@user_register_back');

		Route::post('access_push/{api_key}', 'NotificationController@api_access_push');
		

	});
});

