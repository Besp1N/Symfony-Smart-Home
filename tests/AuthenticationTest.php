<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Login'); // Sprawdź, czy przycisk logowania jest obecny na stronie logowania
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

        $form['_username'] = 'kacper@user.com';
        $form['_password'] = 'password';

        $client->submit($form);

        $this->assertResponseRedirects('/'); // Załóżmy, że po zalogowaniu użytkownik jest przekierowany na stronę główną
    }
}