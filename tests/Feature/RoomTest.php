<?php

it('has room page', function () {
    $response = $this->get('/room');

    $response->assertStatus(200);
});
