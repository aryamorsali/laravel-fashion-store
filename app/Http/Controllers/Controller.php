<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Coza Store API Documentation",
    description: "Coza Store API Documentation",
)]

#[OA\Schema(
    schema: '401ResponseSchema',
    title: 'Unauthenticated Response',

    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
    ]

)]

#[OA\Schema(
    schema: '403ResponseSchema',
    title: 'Unauthorized Response',

    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized.'),
    ]
)]

#[OA\Schema(
    schema: '404ResponseSchema',
    title: 'Not Found Response',

    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'No result found.'),
    ]
)]

#[OA\Schema(
    schema: '422ResponseSchema',
    title: 'Validation Error Response',
    type: 'object',
    properties: [
        new OA\Property(property: 'status', type: 'string', example: 'error'),
        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(property: 'errors', type: 'object', description: 'Validation errors'),
    ]
)]

#[OA\Schema(
    schema: '429ResponseSchema',
    title: 'Too Many Requests Response',

    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Too many requests (throttled)'),
    ]

)]

abstract class Controller
{
    //
}
