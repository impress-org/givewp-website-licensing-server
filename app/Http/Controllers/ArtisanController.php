<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;

/**
 * Class App
 *
 * Handles the app commands for the artisan endpoints
 *
 * @package App\Http\Controllers\Artisan
 */
class ArtisanController extends Controller
{
    /**
     * Runs the app:fresh command to return the application to a vanilla state
     *
     * @param  Request  $request
     *
     * @return Response
     * @throws ValidationException
     * @since 0.3.0
     *
     */
    public function fresh(Request $request): Response
    {
        $this->validate($request, ['check' => 'required|string']);

        if ('make it fresh' !== $request->input('check')) {
            return \response('', 404);
        }

        Artisan::call('app:fresh --yes');

        if (config('app.debug')) {
            $output = sprintf(
                'Artisan output:<br>-------<br>%1$s',
                nl2br(Artisan::output())
            );
        } else {
            $output = 'Done';
        }

        return \response($output);
    }
}
