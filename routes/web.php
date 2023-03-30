<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\v1;
use App\Http\Controllers\AdultosController;
use App\Http\Controllers\AdultosCategoryController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EpgController;
use App\Http\Controllers\EpgCategoryController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PaymentSettingsController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RequestedVideoController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\SerieCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffContentController;
use App\Http\Controllers\StaffDutyController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoCategoryController;
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

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route::get('/', 'App\Http\Controllers\MophyadminController@dashboard_1');
Route::get('/index', 'App\Http\Controllers\MophyadminController@dashboard_1');
Route::get('/my-wallet', 'App\Http\Controllers\MophyadminController@my_wallet');
Route::get('/invoices', 'App\Http\Controllers\MophyadminController@invoices');
Route::get('/cards-center', 'App\Http\Controllers\MophyadminController@cards_center');
Route::get('/transactions', 'App\Http\Controllers\MophyadminController@transactions');
Route::get('/transactions-details', 'App\Http\Controllers\MophyadminController@transactions_details');
Route::get('/app-calender', 'App\Http\Controllers\MophyadminController@app_calender');
Route::get('/app-profile', 'App\Http\Controllers\MophyadminController@app_profile');
Route::get('/post-details', 'App\Http\Controllers\MophyadminController@post_details');
Route::get('/chart-chartist', 'App\Http\Controllers\MophyadminController@chart_chartist');
Route::get('/chart-chartjs', 'App\Http\Controllers\MophyadminController@chart_chartjs');
Route::get('/chart-flot', 'App\Http\Controllers\MophyadminController@chart_flot');
Route::get('/chart-morris', 'App\Http\Controllers\MophyadminController@chart_morris');
Route::get('/chart-peity', 'App\Http\Controllers\MophyadminController@chart_peity');
Route::get('/chart-sparkline', 'App\Http\Controllers\MophyadminController@chart_sparkline');
Route::get('/ecom-checkout', 'App\Http\Controllers\MophyadminController@ecom_checkout');
Route::get('/ecom-customers', 'App\Http\Controllers\MophyadminController@ecom_customers');
Route::get('/ecom-invoice', 'App\Http\Controllers\MophyadminController@ecom_invoice');
Route::get('/ecom-product-detail', 'App\Http\Controllers\MophyadminController@ecom_product_detail');
Route::get('/ecom-product-grid', 'App\Http\Controllers\MophyadminController@ecom_product_grid');
Route::get('/ecom-product-list', 'App\Http\Controllers\MophyadminController@ecom_product_list');
Route::get('/ecom-product-order', 'App\Http\Controllers\MophyadminController@ecom_product_order');
Route::get('/email-compose', 'App\Http\Controllers\MophyadminController@email_compose');
Route::get('/email-inbox', 'App\Http\Controllers\MophyadminController@email_inbox');
Route::get('/email-read', 'App\Http\Controllers\MophyadminController@email_read');
Route::get('/form-editor-summernote', 'App\Http\Controllers\MophyadminController@form_editor_summernote');
Route::get('/form-element', 'App\Http\Controllers\MophyadminController@form_element');
Route::get('/form-pickers', 'App\Http\Controllers\MophyadminController@form_pickers');
Route::get('/form-validation-jquery', 'App\Http\Controllers\MophyadminController@form_validation_jquery');
Route::get('/form-wizard', 'App\Http\Controllers\MophyadminController@form_wizard');
Route::get('/map-jqvmap', 'App\Http\Controllers\MophyadminController@map_jqvmap');
Route::get('/page-error-400', 'App\Http\Controllers\MophyadminController@page_error_400');
Route::get('/page-error-403', 'App\Http\Controllers\MophyadminController@page_error_403');
Route::get('/page-error-404', 'App\Http\Controllers\MophyadminController@page_error_404');
Route::get('/page-error-500', 'App\Http\Controllers\MophyadminController@page_error_500');
Route::get('/page-error-503', 'App\Http\Controllers\MophyadminController@page_error_503');
Route::get('/page-forgot-password', 'App\Http\Controllers\MophyadminController@page_forgot_password');
Route::get('/page-lock-screen', 'App\Http\Controllers\MophyadminController@page_lock_screen');
Route::get('/page-login', 'App\Http\Controllers\MophyadminController@page_login');
Route::get('/page-register', 'App\Http\Controllers\MophyadminController@page_register');
Route::get('/table-bootstrap-basic', 'App\Http\Controllers\MophyadminController@table_bootstrap_basic');
Route::get('/table-datatable-basic', 'App\Http\Controllers\MophyadminController@table_datatable_basic');
Route::get('/uc-lightgallery', 'App\Http\Controllers\MophyadminController@uc_lightgallery');
Route::get('/uc-nestable', 'App\Http\Controllers\MophyadminController@uc_nestable');
Route::get('/uc-noui-slider', 'App\Http\Controllers\MophyadminController@uc_noui_slider');
Route::get('/uc-select2', 'App\Http\Controllers\MophyadminController@uc_select2');
Route::get('/uc-sweetalert', 'App\Http\Controllers\MophyadminController@uc_sweetalert');
Route::get('/uc-toastr', 'App\Http\Controllers\MophyadminController@uc_toastr');
Route::get('/ui-accordion', 'App\Http\Controllers\MophyadminController@ui_accordion');
Route::get('/ui-alert', 'App\Http\Controllers\MophyadminController@ui_alert');
Route::get('/ui-badge', 'App\Http\Controllers\MophyadminController@ui_badge');
Route::get('/ui-button', 'App\Http\Controllers\MophyadminController@ui_button');
Route::get('/ui-button-group', 'App\Http\Controllers\MophyadminController@ui_button_group');
Route::get('/ui-card', 'App\Http\Controllers\MophyadminController@ui_card');
Route::get('/ui-carousel', 'App\Http\Controllers\MophyadminController@ui_carousel');
Route::get('/ui-dropdown', 'App\Http\Controllers\MophyadminController@ui_dropdown');
Route::get('/ui-grid', 'App\Http\Controllers\MophyadminController@ui_grid');
Route::get('/ui-list-group', 'App\Http\Controllers\MophyadminController@ui_list_group');
Route::get('/ui-media-object', 'App\Http\Controllers\MophyadminController@ui_media_object');
Route::get('/ui-modal', 'App\Http\Controllers\MophyadminController@ui_modal');
Route::get('/ui-pagination', 'App\Http\Controllers\MophyadminController@ui_pagination');
Route::get('/ui-popover', 'App\Http\Controllers\MophyadminController@ui_popover');
Route::get('/ui-progressbar', 'App\Http\Controllers\MophyadminController@ui_progressbar');
Route::get('/ui-tab', 'App\Http\Controllers\MophyadminController@ui_tab');
Route::get('/ui-typography', 'App\Http\Controllers\MophyadminController@ui_typography');
Route::get('/widget-basic', 'App\Http\Controllers\MophyadminController@widget_basic');

Route::middleware('checkPermission:owner,admin,staff,distributor,seller')->prefix('dashboard')->name('dashboard.')->group(function(){
	Route::get('/', [DashboardController::class, 'index'])->middleware('checkPermission:owner,staff')->name('index');
	Route::get('/search', [DashboardController::class, 'search'])->middleware('checkPermission:owner,staff')->name('search');
	Route::get('/update-stats', [DashboardController::class, 'update_stats'])->middleware('checkPermission:owner');

	Route::resource('/app_versions', AppVersionController::class)->middleware('checkPermission:owner');

	Route::prefix('messages')->name('messages.')->group(function(){
		Route::get('/', [MessageController::class, 'index'])->middleware('checkPermission:owner')->name('index');
		Route::get('/list', [MessageController::class, 'list'])->middleware('checkPermission:owner')->name('list');
		Route::post('/send', [MessageController::class, 'send'])->middleware('checkPermission:owner')->name('send');
		Route::get('/read/{message}', [MessageController::class, 'read'])->middleware('checkPermission:owner,admin,distributor,seller');
	});
	
	Route::prefix('settings')->name('settings.')->group(function(){
		Route::get('/', [SettingController::class, 'index'])->middleware('checkPermission:owner')->name('index');
		Route::get('/git/to_master', [SettingController::class, 'gitToMaster'])->middleware('checkPermission:owner');
		Route::post('/update', [SettingController::class, 'update'])->middleware('checkPermission:owner');
		Route::get('/movie-episode-list', [SettingController::class, 'movie_episode_list'])->middleware('checkPermission:owner')->name('movie-episode-list');
		Route::get('/export', [SettingController::class, 'export'])->middleware('checkPermission:owner');
	
		Route::get('/change-password', [SettingController::class, 'change_password'])->middleware('checkPermission:staff,admin,distributor,seller')->name('change-password');
		Route::post('/update-user', [SettingController::class, 'update_user'])->middleware('checkPermission:staff,admin,distributor,seller');
	});

	Route::prefix('videos')->name('videos.')->middleware('checkPermission:owner,staff')->group(function(){
		Route::get('/', [VideoController::class, 'index'])->name('index');
		Route::get('/create', [VideoController::class, 'create'])->name('create');
		Route::post('/', [VideoController::class, 'store'])->name('store');
		Route::get('/edit/{video}', [VideoController::class, 'edit'])->name('edit');
		Route::patch('/update/{video}', [VideoController::class, 'update'])->name('update');
		Route::get('/delete/{video}', [VideoController::class, 'destroy'])->name('destroy');

		Route::get('/pull-title-es', [VideoController::class, 'pullTitleEs'])->name('pull-title-es');
		Route::get('/duplicate/{video}', [VideoController::class, 'duplicate'])->name('duplicate');
		Route::get('/initialize', [VideoController::class, 'initialize'])->name('initialize');
		Route::get('/check-link', [VideoController::class, 'checkLink'])->name('check-link');
		Route::get('/view-count', [VideoController::class, 'viewCount'])->name('view-count');

		Route::resource('categories', VideoCategoryController::class);
		Route::post('/categories/order', [VideoCategoryController::class, 'order'])->name('categories.order');
	});
	
	Route::prefix('events')->name('events.')->group(function(){
		Route::resource('options', OptionController::class)->middleware('checkPermission:owner,staff');
		Route::resource('teams', TeamController::class)->middleware('checkPermission:owner,staff');
	});
	Route::post('/events/order', [EventController::class, 'order'])->middleware('checkPermission:owner,staff');
	Route::resource('events', EventController::class)->middleware('checkPermission:owner,staff');

	Route::post('/recommendations/order', [RecommendationController::class, 'order'])->middleware('checkPermission:owner,staff');
	Route::resource('recommendations', RecommendationController::class)->middleware('checkPermission:owner,staff')->except(['store', 'show']);
	Route::get('/recommendations/store', [RecommendationController::class, 'store'])->middleware('checkPermission:owner,staff')->name('recommendations.store');

	Route::prefix('series')->name('series.')->middleware('checkPermission:owner,staff')->group(function(){
		Route::get('/', [SerieController::class, 'index'])->name('index');
		Route::get('/create', [SerieController::class, 'create'])->name('create');
		Route::post('/', [SerieController::class, 'store'])->name('store');
		Route::get('/edit/{series}', [SerieController::class, 'edit'])->name('edit');
		Route::patch('/update/{series}', [SerieController::class, 'update'])->name('update');
		Route::get('/delete/{series}', [SerieController::class, 'destroy'])->name('destroy');

		Route::get('/pull-title-es', [SerieController::class, 'pullTitleEs']);
		Route::get('/duplicate/{serie}', [SerieController::class, 'duplicate']);
		Route::get('/toggle-subtitle-check/{serie}', [SerieController::class, 'toggleSubtitleCheck']);
		Route::get('/view-count', [SerieController::class, 'viewCount'])->middleware('checkPermission:owner,staff')->name('view-count');

		Route::resource('categories', SerieCategoryController::class);
		Route::post('/categories/order', [SerieCategoryController::class, 'order']);

		Route::get('/{serie}/delete', [SerieController::class, 'destroy'])->name('destroy');
	});

	// Route::resource('series', SerieController::class)->middleware('checkPermission:owner,staff')->except(['destroy']);
	
	Route::resource('series.seasons', SeasonController::class)->middleware('checkPermission:owner,staff');

	Route::get('/seasons/{season}/update-poster', [SeasonController::class, 'updatePoster'])->middleware('checkPermission:owner,staff');
	
	Route::get('/series/{series}/seasons/{season}/episodes/{episode}/delete', [EpisodeController::class, 'destroy'])->name('series.seasons.episodes.destroy');
	Route::resource('series.seasons.episodes', EpisodeController::class)->middleware('checkPermission:owner,staff')->except(['destroy']);
	Route::get('/episodes/{episode}/update-poster', [EpisodeController::class, 'updatePoster'])->middleware('checkPermission:owner,staff');
	
	Route::get('series/{series}/seasons/{season}/episode_manual/create', [EpisodeController::class, 'manualcreate'])->middleware('checkPermission:owner,staff')->name('series.seasons.episode_manual.create');
	Route::post('series/{series}/seasons/{season}/episode_manual/store', [EpisodeController::class, 'manualstore'])->middleware('checkPermission:owner,staff')->name('series.seasons.episode_manual.store');

	Route::prefix('staff-content')->name('staff-content.')->group(function(){
		Route::get('/', [StaffContentController::class, 'create'])->middleware('checkPermission:owner')->name('index');
		Route::get('/get-seasons', [StaffContentController::class, 'getSeasons'])->middleware('checkPermission:owner');
		Route::post('/seasons/store', [StaffContentController::class, 'seasonsStore'])->middleware('checkPermission:owner');
		Route::post('/store', [StaffContentController::class, 'store'])->middleware('checkPermission:owner');
	});
	
	Route::prefix('staff_duties')->name('staff_duties.')->group(function(){
		Route::get('/', [StaffDutyController::class, 'index'])->middleware('checkPermission:owner')->name('index');
		Route::post('/', [StaffDutyController::class, 'update'])->middleware('checkPermission:owner')->name('store');
	});

	Route::prefix('adultos')->name('adultos.')->middleware('checkPermission:owner,staff')->group(function(){
		Route::post('/categories/order', [AdultosCategoryController::class, 'order']);
		Route::resource('categories', AdultosCategoryController::class);
	});

	Route::resource('adultos', AdultosController::class)->middleware('checkPermission:owner,staff');

	Route::group(['prefix' => 'media'], function(){
		Route::get('/', [MediaController::class, 'index'])->middleware('checkPermission:owner');
		Route::post('/files', [MediaController::class, 'files'])->middleware('checkPermission:owner');
		Route::post('/new_folder', [MediaController::class, 'new_folder'])->middleware('checkPermission:owner');
		Route::post('/delete_file_folder', [MediaController::class, 'delete_new_folder'])->middleware('checkPermission:owner');
		Route::get('/directories', [MediaController::class, 'get_all_dirs'])->middleware('checkPermission:owner');
		Route::post('/move_file', [MediaController::class, 'move_file'])->middleware('checkPermission:owner');
		Route::post('/upload', [MediaController::class, 'upload'])->middleware('checkPermission:owner');
	});

	Route::resource('users', UserController::class)->middleware('checkPermission:owner,staff,admin,distributor,seller');
	Route::group(['prefix' => 'users'], function(){
		Route::get('/request', [UserController::class, 'request'])->middleware('checkPermission:owner,staff,admin,distributor,seller');
		Route::post('/send_request', [UserController::class, 'sendRequest'])->middleware('checkPermission:owner,staff,admin,distributor,seller');
		Route::post('/updateUser', [UserController::class, 'updateUser'])->middleware('checkPermission:owner,staff,admin,distributor,seller');
		Route::get('/unlink/{user}', [UserController::class, 'unlink'])->middleware('checkPermission:owner,staff,admin,distributor,seller');
		Route::get('/report', [UserController::class, 'report'])->middleware('checkPermission:owner');
		Route::post('/report', [UserController::class, 'exportReport'])->middleware('checkPermission:owner');
	});
	
	Route::post('menu/order', [MenuController::class, 'order'])->middleware('checkPermission:owner');
	Route::resource('menu', MenuController::class)->middleware('checkPermission:owner');

	Route::get('payment_settings', [PaymentSettingsController::class, 'index'])->middleware('checkPermission:owner')->name('payment_settings.index');
	Route::post('payment_settings', [PaymentSettingsController::class, 'save_payment_settings'])->middleware('checkPermission:owner');

	Route::prefix('transfer')->name('transfer.')->middleware('checkPermission:owner,staff,admin,distributor,seller')->group(function(){
		Route::get('/credit', [TransferController::class, 'credit'])->name('credit');
		Route::post('/transfer', [TransferController::class, 'transfer'])->name('transfer');
		Route::get('/log', [TransferController::class, 'log'])->name('log');
		Route::get('/mylog', [TransferController::class, 'mylog'])->name('mylog');
		Route::get('/create', [TransferController::class, 'create'])->middleware('checkPermission:owner')->name('create');
		Route::get('/get-users', [TransferController::class, 'getUsers']);
		Route::post('/store', [TransferController::class, 'store'])->middleware('checkPermission:owner');
		Route::get('/export', [TransferController::class, 'export'])->middleware('checkPermission:owner');
		Route::get('/anti-fraud', [TransferController::class, 'antifraud'])->middleware('checkPermission:owner');
	});

	Route::prefix('errors')->name('errors.')->middleware('checkPermission:owner,staff')->group(function(){
		Route::get('/', [ErrorController::class, 'index'])->name('index');
		Route::get('/movies', [ErrorController::class, 'showNotWorkingLinkVideos'])->name('movies');
		Route::get('/episodes', [ErrorController::class, 'showNotWorkingLinkEpisodes'])->name('episodes');
		
		Route::get('/check-link-movie/{video}', [ErrorController::class, 'checkLinkMovie']);
		Route::get('/check-link-subtitle-movie/{video}', [ErrorController::class, 'checkLinkSubtitleMovie']);
		Route::get('/check-link-subtitle-es-movie/{video}', [ErrorController::class, 'checkLinkSubtitleEsMovie']);
		Route::get('/archive-link-movie/{video}', [ErrorController::class, 'archiveLinkMovie']);
		Route::get('/archive-link-subtitle-movie/{video}', [ErrorController::class, 'archiveLinkSubtitleMovie']);
		Route::get('/archive-link-subtitle-es-movie/{video}', [ErrorController::class, 'archiveLinkSubtitleEsMovie']);
		Route::get('/unarchive-link-movie/{video}', [ErrorController::class, 'unarchiveLinkMovie']);
		Route::get('/unarchive-link-subtitle-movie/{video}', [ErrorController::class, 'unarchiveLinkSubtitleMovie']);
		Route::get('/unarchive-link-subtitle-es-movie/{video}', [ErrorController::class, 'unarchiveLinkSubtitleEsMovie']);

		Route::get('/check-link-episode/{episode}', [ErrorController::class, 'checkLinkEpisode']);
		Route::get('/check-link-subtitle-episode/{episode}', [ErrorController::class, 'checkLinkSubtitleEpisode']);
		Route::get('/check-link-subtitle-es-episode/{episode}', [ErrorController::class, 'checkLinkSubtitleEsEpisode']);
		Route::get('/archive-link-episode/{episode}', [ErrorController::class, 'archiveLinkEpisode']);
		Route::get('/archive-link-subtitle-episode/{episode}', [ErrorController::class, 'archiveLinkSubtitleEpisode']);
		Route::get('/archive-link-subtitle-es-episode/{episode}', [ErrorController::class, 'archiveLinkSubtitleEsEpisode']);
		Route::get('/unarchive-link-episode/{episode}', [ErrorController::class, 'unarchiveLinkEpisode']);
		Route::get('/unarchive-link-subtitle-episode/{episode}', [ErrorController::class, 'unarchiveLinkSubtitleEpisode']);
		Route::get('/unarchive-link-subtitle-es-episode/{episode}', [ErrorController::class, 'unarchiveLinkSubtitleEsEpisode']);

		Route::get('/{error}', [ErrorController::class, 'error']);
		Route::post('error/delete/{error}', [ErrorController::class, 'delete']);
		Route::post('error/resolve/{error}', [ErrorController::class, 'resolve']);
	});

	Route::prefix('epg')->name('epg.')->group(function(){
		Route::get('/', [EpgController::class, 'index'])->middleware('checkPermission:owner,staff')->name('index');
		Route::resource('channels', EpgController::class)->middleware('checkPermission:owner,staff');
		
		Route::get('/programs/{channel}', [EpgController::class, 'programs'])->middleware('checkPermission:owner,staff');
		Route::post('/programs/{channel}', [EpgController::class, 'storeprograms'])->middleware('checkPermission:owner,staff');

		Route::post('epg/categories/order', [EpgCategoryController::class, 'order'])->middleware('checkPermission:owner,staff');
		Route::resource('categories', EpgCategoryController::class)->middleware('checkPermission:owner,staff');
	});

	Route::get('requested-videos', [RequestedVideoController::class, 'index'])->middleware('checkPermission:owner,staff')->name('requested-videos.index');
	Route::get('requested-videos/{requested_video}/update', [RequestedVideoController::class, 'update'])->middleware('checkPermission:owner,staff')->name('requested-videos.update');
});


