<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeployOpsController extends Controller
{
    public function run(Request $request, string $token): Response
    {
        $expectedToken = (string) config('deploy-ops.token');

        if ($expectedToken === '' || ! hash_equals($expectedToken, $token)) {
            throw new NotFoundHttpException;
        }

        $lines = ['Elixira deploy ops', 'Time: '.now()->toDateTimeString(), ''];

        foreach (config('deploy-ops.commands', []) as $command) {
            $command = trim((string) $command);

            if ($command === '') {
                continue;
            }

            $exitCode = Artisan::call($command);
            $output = trim(Artisan::output());

            $lines[] = '$ php artisan '.$command;
            $lines[] = 'Exit code: '.$exitCode;

            if ($output !== '') {
                $lines[] = $output;
            }

            $lines[] = str_repeat('-', 40);
        }

        $lines[] = 'Done.';

        return response(implode(PHP_EOL, $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
