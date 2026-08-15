<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // ✅ VALIDATION (jangan diubah)
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        // ✅ AUTH EXCEPTION → ARAHKAN KE LOGIN
        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login');
        }

        // ✅ TOKEN EXPIRED / CSRF (419)
        if ($exception instanceof TokenMismatchException) {
            return redirect()
                ->route('login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // ✅ HTTP EXCEPTION (403, 404, dll)
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();

            if (view()->exists("pages.errors.$status")) {
                return response()->view(
                    "pages.errors.$status",
                    ['exception' => $exception],
                    $status
                );
            }
        }

        // ✅ PURE 500 (benar-benar server error)
        if (view()->exists("pages.errors.500")) {
            return response()->view(
                "pages.errors.500",
                ['exception' => $exception],
                500
            );
        }

        return parent::render($request, $exception);
    }
}
