<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response
        ->assertSuccessful()
        ->assertSee('Experience the Sacred')
        ->assertSee('Our Packages')
        ->assertSee('KumbhSnaan.com');
});
