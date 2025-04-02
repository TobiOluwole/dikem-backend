<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\SocialsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\EventController;

Route::group(['prefix' => 'api'], function () {

    Route::get('/', function () {
        return response()->json(['message' => 'Hello, World!']);
    });


    Route::group(['prefix' => 'news'], function () {
        Route::get('/', [NewsController::class, 'getAllNews']);
        Route::get('/search', [NewsController::class, 'searchNews']);
        Route::get('/tag/{tag}', [NewsController::class, 'getNewsByTag']);
        Route::get('/{id}', [NewsController::class, 'getNews']);
    });

    Route::group(['prefix' => 'announcement'], function () {
        Route::get('/', [AnnouncementController::class, 'getAllAnnouncements']);
        Route::get('/search', [AnnouncementController::class, 'searchAnnouncement']);
        Route::get('/{id}', [AnnouncementController::class, 'getAnnouncement']);
    });

    Route::group(['prefix' => 'events'], function () {
        Route::get('/', [EventController::class, 'getAllEvents']);
        Route::get('/search', [EventController::class, 'searchEvent']);
        Route::get('/{id}', [EventController::class, 'getEvent']);
    });

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', [ProjectsController::class, 'getAllProjects']);
        Route::get('/search', [ProjectsController::class, 'searchProjects']);
        Route::get('/{id}', [ProjectsController::class, 'getProject']);
    });

    Route::group(['prefix' => 'socials'], function(){
        Route::get('/', [SocialsController::class, 'getSocials']);
    });

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', [PagesController::class, 'getAllPages']);
        Route::get('/{idOrSlug}', [PagesController::class, 'getPage']);
    });

    Route::group(['prefix' => 'admin'], function () {

        Route::post('login', [UserController::class, 'login']);
        Route::post('send-reset-password-email', [UserController::class, 'sendResetPasswordEmail']);
        Route::post('reset-password', [UserController::class, 'resetPassword']);


        Route::middleware([JwtMiddleware::class])->group(function () {

            Route::get('/app-info', [UserController::class, 'getAppInfo']);

            Route::post('logout', [UserController::class, 'logout']);
            Route::get('refresh-token', [UserController::class, 'refreshToken']);

            Route::middleware([SuperAdminMiddleware::class])->group(function () {
                Route::group(['prefix' => 'users'], function () {
                    Route::get('/', [UserController::class, 'getAllUsers']);
                    Route::put('/', [UserController::class, 'createUser']);
                    Route::patch('/me', [UserController::class, 'editMe']);
                    Route::patch('/{id}', [UserController::class, 'editUser']);
                    Route::delete('/{id}', [UserController::class, 'deleteUser']);
                    Route::get('/{id?}/{email?}', [UserController::class, 'getUser']);
                });
            });

            Route::group(['prefix' => 'news'], function () {

                Route::group(['prefix' => 'image'], function () {
                    Route::post('/', [NewsController::class, 'uploadImage']);
                    Route::delete('/{link}', [NewsController::class, 'deleteImage']);
                });

                Route::put('/', [NewsController::class, 'createNews']);
                Route::patch('/{idOrSlug}', [NewsController::class, 'editNews']);
                Route::delete('/{idOrSlug}', [NewsController::class, 'deleteNews']);
            });

            Route::group(['prefix' => 'announcement'], function () {

                Route::group(['prefix' => 'image'], function () {
                    Route::post('/', [AnnouncementController::class, 'uploadImage']);
                    Route::delete('/{link}', [AnnouncementController::class, 'deleteImage']);
                });

                Route::put('/', [AnnouncementController::class, 'createAnnouncement']);
                Route::patch('/{idOrSlug}', [AnnouncementController::class, 'updateAnnouncement']);
                Route::delete('/{idOrSlug}', [AnnouncementController::class, 'deleteAnnouncement']);

            });

            Route::group(['prefix' => 'events'], function () {

                Route::group(['prefix' => 'image'], function () {
                    Route::post('/', [EventController::class, 'uploadImage']);
                    Route::delete('/{link}', [EventController::class, 'deleteImage']);
                });

                Route::put('/', [EventController::class, 'createEvent']);
                Route::patch('/{idOrSlug}', [EventController::class, 'updateEvent']);
                Route::delete('/{idOrSlug}', [EventController::class, 'deleteEvent']);

            });

            Route::group(['prefix' => 'projects'], function () {

                Route::group(['prefix' => 'image'], function () {
                    Route::post('/', [ProjectsController::class, 'uploadImage']);
                    Route::delete('/{link}', [ProjectsController::class, 'deleteImage']);
                });

                Route::put('/', [ProjectsController::class, 'createProject']);
                Route::patch('/{idOrSlug}', [ProjectsController::class, 'editProject']);
                Route::delete('/{idOrSlug}', [ProjectsController::class, 'deleteProject']);
            });

            Route::group(['prefix' => 'socials'], function(){
                Route::patch('/', [SocialsController::class, 'updateSocials']);
            });

            Route::group(['prefix' => 'pages'], function () {
                Route::put('/', [PagesController::class, 'createPage']);
                Route::patch('/{idOrSlug}', [PagesController::class, 'editPage']);
                Route::patch('/{idOrSlug}/{direction}', [PagesController::class, 'changeSort']);
                Route::delete('/{idOrSlug}', [PagesController::class, 'deletePage']);
            });

        });
    });

});
