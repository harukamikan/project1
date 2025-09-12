<?php

it('has tweet page', function () {
    $response = $this->get('/tweet');

    $response->assertStatus(200);
});
