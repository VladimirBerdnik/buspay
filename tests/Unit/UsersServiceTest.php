<?php

namespace Tests\Unit;

use App\Domain\Dto\UserData;
use App\Domain\EntitiesServices\UserEntityService;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mockery;
use Saritasa\LaravelRepositories\Exceptions\RepositoryException;
use Tests\TestCase;

class UsersServiceTest extends TestCase
{
    /**
     * Test that model filled with user data during store method.
     *
     * @return void
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function testStoreMethod(): void
    {
        Validator::shouldReceive('validate')->andReturnTrue();
        Log::shouldReceive('debug')->andReturnTrue();

        $usersRepository = Mockery::mock(UserRepository::class);

        $usersRepository->shouldReceive('create')->andReturnUsing(function (User $user) {
            return $user;
        });

        $usersService = new UserEntityService(Mockery::mock(ConnectionInterface::class), $usersRepository);
        $userData = new UserData([
            UserData::FIRST_NAME => 'first',
            UserData::LAST_NAME => 'last',
            UserData::PASSWORD => 'password',
            UserData::EMAIL => 'email',
            UserData::ROLE_ID => 1,
            UserData::COMPANY_ID => 2,
        ]);

        $user = $usersService->store($userData);
        foreach ($userData->toArray() as $attribute => $value) {
            if ($attribute === UserData::PASSWORD) {
                // Password attribute should not be checked by just comparing as HASH of password are stored in model
                $this->assertTrue(password_verify($value, $user->{$attribute}));
            } else {
                $this->assertEquals($value, $user->{$attribute});
            }
        }
    }

    /**
     * Test that model filled with user data during update method. Password is optional and sometimes can be skipped.
     *
     * @dataProvider updateMethodTestData
     * @return void
     *
     * @throws RepositoryException
     * @throws ValidationException
     */
    public function testUpdateMethod(UserData $userData): void
    {
        Validator::shouldReceive('validate')->andReturnTrue();
        Log::shouldReceive('debug')->andReturnTrue();

        $usersRepository = Mockery::mock(UserRepository::class);

        $usersRepository->shouldReceive('save')->andReturnUsing(function (User $user) {
            return $user;
        });

        $usersService = new UserEntityService(Mockery::mock(ConnectionInterface::class), $usersRepository);

        $originalPassword = '123456';
        $user = new User([User::PASSWORD => $originalPassword]);

        $user = $usersService->update($user, $userData);
        foreach ($userData->toArray() as $attribute => $value) {
            if ($attribute === UserData::PASSWORD) {
                // Password attribute should not be checked by just comparing as HASH of password are stored in model
                if (!$value) {
                    $this->assertTrue(
                        password_verify($originalPassword, $user->{$attribute}),
                        'Password should be the same as before update when empty password passed'
                    );
                } else {
                    $this->assertTrue(
                        password_verify($value, $user->{$attribute}),
                        'Password was passed, it should be updated'
                    );
                }
            } else {
                $this->assertEquals($value, $user->{$attribute});
            }
        }
    }

    public function updateMethodTestData(): array
    {
        return [
            'full user data' => [
                new UserData([
                    UserData::FIRST_NAME => 'first',
                    UserData::LAST_NAME => 'last',
                    UserData::PASSWORD => 'password',
                    UserData::EMAIL => 'email',
                    UserData::ROLE_ID => 1,
                    UserData::COMPANY_ID => 2,
                ]),
            ],
            'user data with empty password' => [
                new UserData([
                    UserData::FIRST_NAME => 'first',
                    UserData::LAST_NAME => 'last',
                    UserData::PASSWORD => '',
                    UserData::EMAIL => 'email',
                    UserData::ROLE_ID => 1,
                    UserData::COMPANY_ID => 2,
                ]),
            ],
        ];
    }
}
