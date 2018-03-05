<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use URL;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];
    
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException)
        {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if(in_array('getStatusCode',get_class_methods($e)))
        {
            if ($e->getStatusCode() == 404)
            {
                return response()->view('errors.general', [
                    'previous_url' => URL::previous(),
                    'page_title'   => 'Not Found',
                    'maker'        => 'Carmen Bravo A.',
                    'code'         => 404,
                ], 404);
            }
    
            if ($e->getStatusCode() == 501)
            {
                return response()->view('errors.general', [
                    'previous_url' => URL::previous(),
                    'page_title'   => 'You dont have autorization!',
                    'maker'        => 'Carmen Bravo',
                    'code'         => 501,
                ], 501);
            }
        }

        return parent::render($request, $e);
        
    }
}
