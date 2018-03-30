<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FlattenException;
use Session;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $exception = FlattenException::create($e);
        $statusCode = $exception->getStatusCode($exception);

        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }elseif ($e instanceof TokenMismatchException) {
            Session::flash('messageRed', "Your session ended. Please log back in.");
            return redirect('/login');
        }elseif ($e instanceof FatalErrorException) {
            return response()->view('errors.500');
        }elseif (in_array($statusCode, array(403, 500, 503, 504, 505))) {
            return response()->view('errors.'.$statusCode);
        }else{
            Session::flash('messageRed', "Yourog back in.");
            return parent::render($request, $e);
        }
    }
}
