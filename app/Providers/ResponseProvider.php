<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ServiceProvider;
use Log;

class ResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data) {
            return Response::json($data,200);
        });

        Response::macro('badRequest', function ($message) {
            return Response::json(['error'=>$message],400);
        });

        Response::macro('unauthorized', function ($reason) {
            return Response::json(['error'=>$reason],401);
        });


        Response::macro('forbidden', function ($reason) {
            return Response::json(['error'=>$reason],403);
        });

        Response::macro('notFound', function ($reason) {
            return Response::json(['error'=>$reason],404);
        });


        Response::macro('unprocessable', function ($message, $errors=[]) {


            if($errors instanceof MessageBag){

                $err = [];
                foreach ($errors->toArray() as $error) {
                    $err[] = $error[0];
                }
                $errors = $err;
            }
            Log::info("Error - Mensaje: $message - Errors: ".json_encode($errors));

            return Response::json(['message'=>$message,'errors'=>$errors],422);
        });

        Response::macro('tooManyAttempts', function ($reason) {
            return Response::json(['error'=>$reason],423);
        });
    }
}
