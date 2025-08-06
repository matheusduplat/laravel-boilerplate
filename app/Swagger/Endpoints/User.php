<?php


/**
 * @OA\Get(
 *     path="/user",
 *     summary="Listar usuários",
 *     tags={"Usuários"},
 *     security={{"sanctumAuth":{}}},
 * 
 *     @OA\Response(
 *         response=200,
 *         description="Lista de usuários",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/user/store",
 *     summary="Criar usuário",
 *     tags={"Usuários"},
 *     security={{"sanctumAuth":{}}},
 * 
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuário criado com sucesso",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 * 
 *      @OA\Response(
 *              response=422,
 *              description="Erro de validação",
 *          @OA\JsonContent(
 *              type="object",
 *               @OA\Property(property="message", type="string", example="The given data was invalid."),
 *               @OA\Property(
 *                 type="array",
 *                 @OA\Items(type="string", example="")
 *             )
 *     )
 * )
 * )
 */
