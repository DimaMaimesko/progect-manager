<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginTest extends WebTestCase
{
    public function testLoginPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
    }

    public function testLoginFormIsDisplayed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        self::assertSelectorExists('form');
        self::assertSelectorExists('input[name="email"]');
        self::assertSelectorExists('input[name="password"]');
    }

    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $client->submitForm('Sign in', [
            'email' => 'admin@app.test',
            'password' => 'password',
        ]);

        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }

    public function testLoginWithInvalidCredentialsFails(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $client->submitForm('Sign in', [
            'email' => 'admin@app.test',
            'password' => 'wrong-password',
        ]);

        // Symfony redirects back to /login on failure
        self::assertResponseRedirects('/login');
        $client->followRedirect();

        self::assertSelectorExists('.alert-danger, .error, [data-error]');
    }

    public function testUnauthenticatedUserIsRedirectedToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseRedirects('http://localhost/login');
    }


}
