<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'UserController@login')->name('login');
Route::post('/doLogin', 'UserController@doLogin')->name('doLogin');
Route::get('/logout', 'UserController@logout')->name('logout');

Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');

Route::get('/members/ajaxcode', 'MemberController@ajaxcode')->name('members.ajaxcode');
Route::get('/videos/ajaxrelated', 'VideoController@ajaxrelated')->name('videos.ajaxrelated');
Route::get('/videos/ajax_association', 'VideoController@ajax_association')->name('videos.ajax_association');
Route::get('/news/ajax_cate_url', 'NewController@ajax_cate_url')->name('news.ajax_cate_url');
Route::get('/rss/ajax_cate_url', 'RssController@ajax_cate_url')->name('rss.ajax_cate_url');
Route::get('/rssnews/ajax_cate_url', 'NewController@ajax_cate_url_hh')->name('rssnews.ajax_cate_url');

Route::get('/users/ajax/pro', 'UserController@ajaxpro')->name('users.ajaxpro');
Route::get('/news/ajax/top', 'NewController@ajaxtop')->name('news.ajaxtop');
Route::get('/news/ajax/show', 'NewController@ajaxshow')->name('news.ajaxshow');
Route::get('/categorynews/ajax/show', 'CategoryNewController@ajaxshow')->name('categorynews.ajaxshow');
Route::get('/banners/ajax/show', 'BannerController@ajaxshow')->name('banners.ajaxshow');
Route::get('/newbanners/ajax/show', 'NewBannerController@ajaxshow')->name('newbanners.ajaxshow');

Route::get('/prs/ajax/show', 'PrController@ajaxshow')->name('prs.ajaxshow');
Route::get('/news/category/soft', 'CategoryNewController@ajaxsoft')->name('news.category.ajaxsoft');
Route::get('/channel/ajax/soft', 'ChannelController@ajaxsoft')->name('channel.ajax.ajaxsoft');
Route::get('/halls/soft', 'HallController@ajaxsoft')->name('halls.ajaxsoft');

Route::get('/associations/ajax/publish', 'CategoryController@ajaxpublish')->name('associations.ajaxpublish');
Route::get('/channels/ajax/publish', 'ChannelController@ajaxpublish')->name('channels.ajaxpublish');
Route::get('/channels/ajax/is_sponser', 'ChannelController@ajaxis_sponser')->name('channels.ajaxis_sponser');
Route::get('/channels/ajax/top', 'ChannelController@ajaxtop')->name('channels.ajaxtop');
Route::get('/videos/ajax/publish', 'VideoController@ajaxpublish')->name('videos.ajaxpublish');

Route::group(['middleware' => ['web','App\Http\Middleware\Admin'], 'prefix' => ''], function() {
	Route::resource('users', 'UserController');
	Route::get('/users/delete/{id}', 'UserController@destroy')->name('users.delete');
	Route::resource('pushusers', 'PushUserController');
	Route::get('/pushusers/delete/{id}', 'PushUserController@destroy')->name('pushusers.delete');

	Route::resource('members', 'MemberController');
	Route::get('/members/delete/{id}', 'MemberController@destroy')->name('members.delete');
	Route::post('/members/import', 'MemberController@import')->name('members.import');

	Route::resource('category_news', 'CategoryNewController');
	Route::get('/category_news/delete/{id}', 'CategoryNewController@destroy')->name('category_news.delete');

	Route::resource('news', 'NewController');
	Route::get('/news/get/rss', 'NewController@getrss')->name('news.getrss');
	Route::post('/news/post/rss', 'NewController@postrss')->name('news.postrss');
	// Route::get('/news/comments/list/{id}', 'NewController@list_comment_by_news')->name('news.comments_news');
	// Route::get('/news/comments/destroy/{id}/{new}', 'NewController@comment_destroy')->name('news.comments_destroy');
	// Route::get('/news/comments/edit/{id}/{new}', 'NewController@comment_edit')->name('news.comment_edit');
	Route::get('/news/delete/{id}', 'NewController@destroy')->name('news.delete');
	

	Route::resource('commentnews', 'CommentNewController');
	Route::get('/commentnews/delete/{id}', 'CommentNewController@destroy')->name('commentnews.delete');

	Route::resource('rssnews', 'RssNewController');
	Route::get('/rssnews/delete/{id}', 'RssNewController@destroy')->name('rssnews.delete');

	Route::resource('associations', 'CategoryController');


	Route::resource('associations_video', 'VideoCategoryRelaController');
	Route::get('/associations_video/delete/{id}', 'VideoCategoryRelaController@destroy')->name('associations_video.delete');
	Route::get('/associations_video/aj/soft', 'VideoCategoryRelaController@ajaxsoft')->name('associations_video.ajaxsoft');

	Route::resource('rss', 'RssController');
	Route::get('/rss/get/rss', 'RssController@getrss')->name('rss.getrss');
	Route::post('/rss/post/rss', 'RssController@postrss')->name('rss.postrss');
	Route::get('/rss/delete/{id}', 'RssController@destroy')->name('rss.delete');

	Route::resource('rssurls', 'RssUrlController');
	Route::get('/rssurls/delete/{id}', 'RssUrlController@destroy')->name('rssurls.delete');

	Route::resource('topics', 'TopicController');
	Route::get('/topics/get/topics', 'TopicController@gettopics')->name('topics.gettopics');
	Route::post('/topics/post/topics', 'TopicController@posttopics')->name('topics.posttopics');
	Route::get('/topics/delete/{id}', 'TopicController@destroy')->name('topics.delete');

	Route::resource('discussion', 'DiscussionController');
	Route::get('/discussion/delete/{id}', 'DiscussionController@destroy')->name('discussion.delete');
	Route::get('/discussion/comments/delete/{id}', 'DiscussionController@comments_destroy')->name('discusion.comment.delete');
	Route::get('/discussion/comments/empty/{id}', 'DiscussionController@comments_destroy')->name('discusion.comment.empty');
	Route::get('/discussion/comments/list', 'DiscussionController@comments_list')->name('discussion.comments_list');

	Route::resource('banners', 'BannerController');
	Route::get('/banners/set/number', 'BannerController@setNumber')->name('banners.setNumber');
	Route::post('/banners/set/number', 'BannerController@postNumber')->name('banners.postNumber');
	Route::get('/banners/delete/{id}', 'BannerController@destroy')->name('banners.delete');

	Route::resource('prs', 'PrController');
	Route::get('/prs/delete/{id}', 'PrController@destroy')->name('prs.delete');

	Route::resource('seminars', 'SeminarController');
	Route::get('/seminars/delete/{id}', 'SeminarController@destroy')->name('seminars.delete');

	Route::resource('events', 'EventController');
	Route::get('/events/event/getevents', 'EventController@getevents')->name('events.getevents');
	Route::post('/events/event/postevents', 'EventController@postevents')->name('events.postevents');
	Route::get('/events/delete/{id}', 'EventController@destroy')->name('events.delete');
	Route::get('/events/deleteall/{id}', 'EventController@deleteall')->name('events.deleteall');

	Route::resource('categoryevent', 'CategoryEventController');
	Route::get('/categoryevent/delete/{id}', 'CategoryEventController@destroy')->name('categoryevent.delete');


	Route::resource('channels', 'ChannelController');
	Route::get('/channels/delete/{id}', 'ChannelController@destroy')->name('channels.delete');

	Route::resource('videos', 'VideoController');
	Route::get('/videos/add/pdf', 'VideoController@addpdf')->name('videos.addpdf');
	Route::get('/videos/add/slider', 'VideoController@addslider')->name('videos.addslider');
	Route::get('/videos/question/{id}', 'VideoController@question')->name('videos.question');
	Route::get('/videos/delete/{id}', 'VideoController@destroy')->name('videos.delete');

	Route::resource('questions', 'QuestionController');
	Route::get('/questions/delete/{id}', 'QuestionController@destroy')->name('questions.delete');

	Route::get('/api_key', 'ApiController@index')->name('api_key');
	Route::get('/create', 'ApiController@create')->name('apis.create');
	Route::get('/destroy/{id}', 'ApiController@destroy')->name('apis.destroy');

	Route::get('/socket', 'SocketController@index')->name('socket.index');
	Route::get('/writeMessage', 'SocketController@writeMessage')->name('socket.writeMessage');
	Route::post('/sendMessage', 'SocketController@sendMessage')->name('socket.sendMessage');


	Route::resource('notification', 'NotificationController');
	Route::get('/notification/delete/{id}', 'NotificationController@destroy')->name('notification.delete');
	Route::get('/notification/message/push/{id}', 'NotificationController@push')->name('notification.push');
	Route::get('/notification/delete/{id}', 'NotificationController@destroy')->name('notification.delete');
	Route::get('/notification/message/push_associations/{id}', 'NotificationController@get_push_associations')->name('notification.get_push_associations');
	Route::post('/notification/message/push_associations/{id}', 'NotificationController@post_push_associations')->name('notification.post_push_associations');
	Route::get('/notification/create/news', 'NotificationController@pushNew')->name('notification.create.news');
	Route::get('/notification/create/tvpro', 'NotificationController@pushTVpro')->name('notification.create.tvpro');
	Route::get('/news/by/category', 'NewController@listByCategory')->name('news.by.category');
	Route::get('/channel/by/category', 'ChannelController@listByCategory')->name('channel.by.category');
	Route::get('/videos/by/channel', 'VideoController@listByChannel')->name('videos.by.channel');

	Route::get('/rss/crontime/set', 'RssController@crontime')->name('rss.crontime');
	Route::post('/rss/crontime/set', 'RssController@postcrontime')->name('rss.postcrontime');

	Route::get('/news/crontime/set', 'NewController@crontime')->name('new.crontime');
	Route::post('/news/crontime/set', 'NewController@postcrontime')->name('new.postcrontime');
	Route::get('/news/crontime/delete/{id}', 'NewController@crontimedelete')->name('new.crontime.delete');


	Route::get('/analytic/TVpro/channels', 'AnalyticController@channels')->name('analytic.channels');
	Route::get('/analytic/TVpro/content', 'AnalyticController@content')->name('analytic.content');
	Route::post('/analytic/TVpro/content', 'AnalyticController@content')->name('analytic.content.port');
	Route::get('/analytic/TVpro/content/{id}', 'AnalyticController@contentDetail')->name('analytic.content.detail');
	Route::get('/analytic/TVpro/content/{id}/{day}', 'AnalyticController@contentDetailDay')->name('analytic.content.detail.day');
	Route::get('/analytic/TVpro/content/{id}/user/{status}', 'AnalyticController@contentDetailUser')->name('analytic.content.detail.user');
	Route::get('/analytic/TVpro/content/history/{id}/user', 'AnalyticController@contentDetailUserHistory')->name('analytic.content.user.history');
	Route::get('/analytic/TVpro/content/history/{id}/access', 'AnalyticController@contentDetailAccessHistory')->name('analytic.content.access.history');
	Route::get('/analytic/TVpro/content/{id}/{day}/{time}/view', 'AnalyticController@contentDetailDayTime')->name('analytic.content.detail.day.time');


	Route::get('/analytic/News', 'AnalyticController@news')->name('analytic.news');
	Route::get('/analytic/News/{id}/access', 'AnalyticController@newsAccess')->name('analytic.news.access');


	Route::get('/analytic/Ranking', 'AnalyticController@Ranking')->name('analytic.rankink');



	Route::post('/news/delete/all/check', 'NewController@destroyall')->name('news.deleteall');
	Route::post('/channels/delete/all/check', 'ChannelController@destroyall')->name('channels.deleteall');
	Route::post('/videos/delete/all/check', 'VideoController@destroyall')->name('videos.deleteall');

	Route::get('/channels/Notification/push/{id}', 'ChannelController@pushNotification')->name('channels.pushNotification');


	Route::resource('newdefault', 'NewDefaultController');
	Route::get('/seminars/ajax/show', 'SeminarController@ajaxshow')->name('seminars.ajaxshow');
	Route::get('/seminars/ajax/link', 'SeminarController@ajaxlink')->name('seminars.ajaxlink');
	Route::get('/rss/ajax/show', 'RssController@ajaxshow')->name('rss.ajaxshow');
	
	Route::get('/news/cron/list', 'NewController@list_cron_delete')->name('new.list_cron_delete');
	Route::resource('newbanners', 'NewBannerController');
	Route::get('/newbanners/delete/{id}', 'NewBannerController@destroy')->name('newbanners.delete');

	Route::resource('categoryevents', 'CategoryEventController');
	Route::get('/categoryevents/delete/{id}', 'CategoryEventController@destroy')->name('categoryevents.delete');
	Route::get('/categoryevent/index/namesearch', 'CategoryEventController@namesearch')->name('categoryevents.namesearch');
	Route::get('/categoryevent/category/setcolor', 'CategoryEventController@setcolor')->name('categoryevents.setcolor');
	Route::get('/pdf', 'EventController@generatePDF1');

	Route::get('/analytic/user', 'UserController@userAnalytic')->name('analytic.user');
	Route::get('/analytic/banner', 'AnalyticController@analyticBanner')->name('analytic.banner');
	Route::get('/analytic/banner/view/{id}', 'AnalyticController@analyticBannerView')->name('analytic.banner.view');
	Route::resource('versions', 'VersionController');

	Route::get('/user/export/download', 'UserController@export')->name('user.export.download');

	Route::resource('admin', 'AdminController');
	Route::get('/admin/delete/{id}', 'AdminController@destroy')->name('admin.delete');

	Route::get('/place/{seminar_id}', 'HallController@index')->name('place.index');

	Route::get('/user/ajax/get', 'NotificationController@ajax_get_user')->name('user.ajax.get');
	Route::get('/member/ajax/getlist', 'NotificationController@ajax_get_member_list')->name('member.ajax.getlist');
	Route::get('/careers/ajax/getlist', 'NotificationController@ajax_get_careers_list')->name('careers.ajax.getlist');
	Route::get('/provinces/ajax/getlist', 'NotificationController@ajax_get_provinces_list')->name('provinces.ajax.getlist');
	
	Route::match(['get', 'post'],'/user/list/review', 'NotificationController@list_user_review')->name('user.list.review');

	Route::match(['get', 'post'],'/notification/push/preview', 'NotificationController@pushPreview')->name('notification.push.preview');
	
});


Route::get('/cronjob/rss', 'RssController@cronjob')->name('cronjob.rss');
Route::get('/cronjob/new', 'NewController@cronjob')->name('cronjob.new');
Route::get('/cronjob/newdelete', 'NewController@crondelete')->name('cronjob.crondelete');
Route::get('/cronjob/push_event', 'EventController@push_event_15')->name('cronjob.push_event');
Route::get('/cronjob/push_discussion_comment', 'NotificationController@push_comment_discussioin')->name('cronjob.push_discussion_comment');
Route::get('/cronjob/push_global', 'NotificationController@cron_push_global')->name('cronjob.push_global');

Route::get('/test/postvideo', 'TestController@postvideo')->name('test.postvideo');
Route::post('/test/postvideo', 'TestController@postvideo1')->name('test.postvideo1');
Route::get('/init/device', 'SyncInitController@InitDevice');
Route::get('/init/hall', 'SyncInitController@InitHall');
Route::get('/init/member', 'SyncInitController@member');




