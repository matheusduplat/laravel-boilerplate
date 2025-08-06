<?php

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Matheus Duplat"),
 *     @OA\Property(property="email", type="string", format="email", example="matheus@exemplo.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="UserCreateRequest",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Matheus Duplat"),
 *     @OA\Property(property="email", type="string", format="email", example="matheus@exemplo.com"),
 *     @OA\Property(property="password", type="string", format="password", example="senha123")
 * )
 */
