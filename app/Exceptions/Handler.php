<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $e)
    {
        $response = parent::render($request, $e);

        if (
            !app()->environment('local') &&
            boolval($request->header('X-Inertia', false))
        ) {
            $status = $response->getStatusCode();
            return inertia('Error', [
                'status'      => $status,
                'status_text' => Response::$statusTexts[$status] ?? 'Unknown Status Code',
                'message'     => app()->environment('production') ? '' : $e->getMessage(),
            ])
            ->toResponse($request)
            ->setStatusCode($status);
        }

        return $response;
    }
}
