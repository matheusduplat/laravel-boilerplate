<?php

/**
 * @group Usuários
 *
 * Endpoints de usuários (CRUD, com enum, objetos aninhados e arrays).
 */

/**
 * Listar usuários
 *
 * Retorna todos os usuários cadastrados.
 *
 * @authenticated
 * @response 200 array<array{
 *     id: int,
 *     name: string,
 *     sexo: string,
 *     dados: array{state: string},
 *     phone: array<array{number: string}>
 * }>
 */
function list_users() {}
