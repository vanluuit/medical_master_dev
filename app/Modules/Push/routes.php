<?php 
Route::group(['middleware' => ['web'], 'module' => 'Push', 'prefix' => 'push', 'namespace' => 'App\Modules\Push\Controllers'], function() {
	Route::get('/', 'UserController@login')->name('push.login');
	Route::post('/doLogin', 'UserController@doLogin')->name('push.doLogin');
	Route::get('/logout', 'UserController@logout')->name('push.logout');
	Route::get('/videos/ajaxrelated', 'VideoController@ajaxrelated')->name('push.videos.ajaxrelated');
	Route::get('/videos/ajax_association', 'VideoController@ajax_association')->name('push.videos.ajax_association');

	Route::get('/member/ajax_search', 'UserController@ajax_search')->name('push.member.ajax_search');

	Route::group(['middleware' => ['web','App\Http\Middleware\Push'], 'prefix' => ''], function() {
		Route::get('/associations', 'CategoryController@index')->name('push.associations');
		//Route::get('/notification/push', 'NotificationController@get_push_associations')->name('push.notification.get_push_associations');
		Route::post('/notification/push', 'NotificationController@post_push_associations')->name('push.notification.post_push_associations');
		Route::get('/user/confirm', 'UserController@listConfirm')->name('push.user.list_confirm');
		Route::get('/user/refuse', 'UserController@listRefuse')->name('push.user.list_refuse');
		Route::get('/user/list', 'UserController@index')->name('push.user.index');
		Route::get('/user/import', 'UserController@import')->name('push.user.import');
		Route::get('/user/export', 'UserController@export')->name('push.user.export');
		Route::post('/user/import', 'UserController@postImport')->name('push.user.import.post');
		Route::post('/user/approve', 'UserController@approve')->name('push.user.approve');
		Route::post('/user/editapprove', 'UserController@editapprove')->name('push.user.editapprove');
		Route::post('/user/resendAuth', 'UserController@resendAuth')->name('push.user.resendAuth');
		Route::post('/user/refuse', 'UserController@refuse')->name('push.user.refuse');
		Route::get('/user/deleterequest/{id}', 'UserController@deleterequest')->name('push.user.deleterequest');

		Route::get('/notification/member/import', 'NotificationController@importMember')->name('push.notification.importMember');
		Route::post('/notification/member/import', 'NotificationController@postImportMember')->name('push.notification.importMember.post');
		Route::get('/notification/member/edit/{id}', 'NotificationController@editMember')->name('push.notification.editMember');
		Route::post('/notification/member/edit/{id}', 'NotificationController@postEditMember')->name('push.notification.editMember.post');
		Route::get('/notification/member/delete/{id}', 'NotificationController@deleteMember')->name('push.notification.deleteMember');

		Route::get('/notification/member/codepush', 'NotificationController@memberCodePush')->name('push.notification.memberCodePush');
		Route::post('/notification/member/delectcheck', 'NotificationController@deleteCheck')->name('push.notification.deleteCheck');
		Route::get('/notification/member/delectall', 'NotificationController@deleteAll')->name('push.notification.deleteAll');

		Route::resource('members', 'MemberController', ['as' => 'push']);
		Route::get('/members/delete/{id}', 'MemberController@destroy')->name('push.members.delete');
		Route::resource('users', 'UserController', ['as' => 'push']);

		// push
		Route::resource('notification', 'NotificationController', ['as' => 'push']);
		Route::get('/notification/message/push/{id}', 'NotificationController@push')->name('push.notification.push');
		Route::get('/notification/delete/{id}', 'NotificationController@destroy')->name('push.notification.delete');
		Route::get('/notification/message/push_associations/', 'NotificationController@get_push_associations')->name('push.notification.get_push_associations');
		Route::post('/notification/message/push_associations/', 'NotificationController@post_push_associations')->name('push.notification.post_push_associations');

		Route::get('/notification/create/tvpro', 'NotificationController@pushTVpro')->name('push.notification.create.tvpro');
		Route::get('/user/ajax/get', 'NotificationController@ajax_get_user')->name('push.user.ajax.get');
		Route::get('/member/ajax/getlist', 'NotificationController@ajax_get_member_list')->name('push.member.ajax.getlist');
		Route::get('/careers/ajax/getlist', 'NotificationController@ajax_get_careers_list')->name('push.careers.ajax.getlist');
		Route::get('/provinces/ajax/getlist', 'NotificationController@ajax_get_provinces_list')->name('push.provinces.ajax.getlist');
		
		Route::match(['get', 'post'],'/user/list/review', 'NotificationController@list_user_review')->name('push.user.list.review');

		Route::match(['get', 'post'],'/notification/push/preview', 'NotificationController@pushPreview')->name('push.notification.push.preview');
		Route::get('/channel/by/category', 'ChannelController@listByCategory')->name('push.channel.by.category');
		Route::get('/videos/by/channel', 'VideoController@listByChannel')->name('push.videos.by.channel');

		Route::resource('listmemberpushs', 'ListMemberPushController', ['as' => 'push']);
		Route::post('/listmemberpushs/create', 'ListMemberPushController@create')->name('push.listmemberpushs.create');
		Route::post('/listmemberpushs/edit/{id}', 'ListMemberPushController@edit')->name('push.listmemberpushs.edit');
		Route::get('/listmemberpushs/delete/{id}', 'ListMemberPushController@destroy')->name('push.listmemberpushs.delete');

		Route::resource('videos', 'VideoController', ['as' => 'push']);
		Route::get('/videos/add/pdf', 'VideoController@addpdf')->name('push.videos.addpdf');
		Route::get('/videos/add/slider', 'VideoController@addslider')->name('push.videos.addslider');
		Route::get('/videos/question/{id}', 'VideoController@question')->name('push.videos.question');
		Route::match(['get', 'post'],'/videos/question/edit/{id}', 'VideoController@questionedit')->name('push.videos.question.edit');
		Route::get('/videos/delete/{id}', 'VideoController@destroy')->name('push.videos.delete');
		Route::get('/videos/ajax/publish', 'VideoController@ajaxpublish')->name('push.videos.ajaxpublish');
		Route::resource('questions', 'QuestionController', ['as' => 'push']);
		Route::get('/questions/delete/{id}', 'QuestionController@destroy')->name('push.questions.delete');
		
		// analytic
		Route::get('/analytic/TVpro/channels', 'AnalyticController@channels')->name('push.analytic.channels');
		Route::get('/analytic/TVpro/content', 'AnalyticController@content')->name('push.analytic.content');
		Route::post('/analytic/TVpro/content', 'AnalyticController@content')->name('push.analytic.content.port');
		Route::get('/analytic/TVpro/content/{id}', 'AnalyticController@contentDetail')->name('push.analytic.content.detail');
		Route::get('/analytic/TVpro/content/{id}/{day}', 'AnalyticController@contentDetailDay')->name('push.analytic.content.detail.day');
		Route::get('/analytic/TVpro/content/{id}/user/{status}', 'AnalyticController@contentDetailUser')->name('push.analytic.content.detail.user');
		Route::get('/analytic/TVpro/content/history/{id}/user', 'AnalyticController@contentDetailUserHistory')->name('push.analytic.content.user.history');
		Route::get('/analytic/TVpro/content/history/{id}/access', 'AnalyticController@contentDetailAccessHistory')->name('push.analytic.content.access.history');
		Route::get('/analytic/TVpro/content/{id}/{day}/{time}/view', 'AnalyticController@contentDetailDayTime')->name('push.analytic.content.detail.day.time');
		});

		
	});
?>