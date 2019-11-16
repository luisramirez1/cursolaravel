<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {


        if($exception instanceof NotFoundHttpException){

            return response()->notFound('Ruta no encontrada');
        }

        if($exception instanceof ModelNotFoundException){

            return response()->notFound('Recurso no encontrado');
        }

        if($exception instanceof ValidationException){

            $errors = $exception->validator->errors();
            return response()->unprocessable('Parametros inválidos',$errors);

        }else if($exception instanceof TokenExpiredException) {

            return response()->unauthorized($exception->getMessage());

        }else if($exception instanceof UnauthorizedHttpException){

            return response()->unauthorized($exception->getMessage());

        }else if($exception instanceof TokenBlacklistedException){

            return response()->unauthorized($exception->getMessage());

        }else if($exception instanceof  TokenInvalidException){

            return response()->badRequest($exception->getMessage());
        }

        else if($exception instanceof  JWTException) {

            return response()->badRequest($exception->getMessage() );
        }



        return parent::render($request, $exception);
    }
}
