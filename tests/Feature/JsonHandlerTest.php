<?php

namespace SMartins\Exceptions\Tests\Feature;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SMartins\Exceptions\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use SMartins\Exceptions\Tests\Fixtures\User as Model;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonHandlerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'exceptions']);

        $this->setUpRoutes();
    }

    public function setUpRoutes()
    {
        Route::get('default_exception', function (Request $request) {
            throw new Exception('Test message', 1);
        });

        Route::get('/model_not_found', function (Request $request) {
            Model::findOrFail(1);
        });

        Route::middleware('auth')->get('/authentication', function (Request $request) {
            //
        });

        Route::get('authorization', function (Request $request) {
            throw new AuthorizationException('Forbidden');
        });

        Route::get('validation', function (Request $request) {
            Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'email',
            ])->validate();
        });

        Route::get('bad_request', function (Request $request) {
            throw new BadRequestHttpException('Bad Request');
        });
    }

    public function testThrowsDefaultException()
    {
        $this->json('GET', 'default_exception')
            ->assertStatus(500)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsModelNotFoundException()
    {
        $this->json('GET', 'model_not_found')
            ->assertStatus(404)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsAuthenticationException()
    {
        $this->json('GET', 'authentication')
            ->assertStatus(401)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsAuthorizationException()
    {
        $this->json('GET', 'authorization')
            ->assertStatus(403)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsValidationExceptions()
    {
        $params = ['email' => str_repeat('a', 11)];

        // dd($this->json('GET', 'validation', $params)->json());
        $this->json('GET', 'validation', $params)
            ->assertStatus(400)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsBadRequestHttpException()
    {
        $this->json('GET', 'bad_request')
            ->assertStatus(400)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    public function testThrowsNotFoundHttpException()
    {
        $this->json('GET', 'not_found_route')
            ->assertStatus(404)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    /**
     * The default json response error structure.
     *
     * @return array
     */
    public function defaultErrorStructure()
    {
        return [
            'errors' => [[
                'status',
                'code',
                'title',
                'detail',
                'source' => ['pointer']
            ]],
        ];
    }
}
