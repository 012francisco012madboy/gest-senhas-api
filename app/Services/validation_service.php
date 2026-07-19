<?php

namespace App\Services;

class validation_service
{
    public function Name_Validate(string &$name)
    {
        // Remove espaços no começo e no fim
        $name = trim($name);

        // Verifica vazio
        if ($name === "") {
            return response()->json([
                'message' => 'O nome não pode estar vazio ou só com espaços.'
            ], 400);
        }

        // Valida apenas letras e espaços
        if (!preg_match('/^(?!\s*$)[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/', $name)) {
            return response()->json([
                'message' => 'O nome deve conter apenas letras.'
            ], 400);
        }

        // Valida o tamanho de caracteres
        if (mb_strlen($name) > 50) {
            return response()->json([
                'message' => 'O nome excede o número limite de caracteres (50)'
            ], 400);
        }

        // Valida scripts maliciosos
        if ($name !== strip_tags($name)) {
            return response()->json([
                'message' => 'HTML não é permitido.'
            ], 400);
        }

        // Converte tudo para minúsculas
        $name = mb_strtolower($name, 'UTF-8');

        // Coloca a primeira letra de cada palavra em MAIÚSCULA
        $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');

        return true;
    }

    public function Phone_Validate(string $phone)
    {
        if (empty(trim($phone))) {
            return response()->json([
                'message' => 'O telefone é obrigatório.'
            ], 400);
        }

        if (!preg_match('/^[0-9]+$/', $phone)) {
            return response()->json([
                'message' => 'O telefone deve conter apenas números.'
            ], 400);
        }
        
        if (!preg_match('/^9[1-9][0-9]{7}$/', $phone)) {
            return response()->json([
                'message' => "Número de telefone angolano inválido."
            ], 400);
        }

        return true;
    }

    public function Phone_Afrimoney_Validate(string $phone)
    {
        if (empty(trim($phone))) {
            return response()->json([
                'message' => 'O telefone é obrigatório.'
            ], 400);
        }

        if (!preg_match('/^[0-9]+$/', $phone)) {
            return response()->json([
                'message' => 'O telefone deve conter apenas números.'
            ], 400);
        }

        // Africell Angola começa com 95
        if (!preg_match('/^95[0-9]{7}$/', $phone)) {
            return response()->json([
                'message' => 'Número Afrimoney inválido (95XXXXXXX).'
            ], 400);
        }

        return true;
    }

    public function BI_Validate(string &$bi)
    {
        if (trim($bi) === "") {
            return response()->json([
                'message' => 'O bi não pode estar vazio ou só com espaços.'
            ], 400);
        }

        $bi = strtoupper($bi);

        if (!preg_match('/^[0-9]{9}[A-Z]{2}[0-9]{3}$/', $bi)) {
            return response()->json([
                'message' => "Bilhete de identidade inválido."
            ], 400);
        }

        return true;
    }

    public function Email_Validate(string &$email)
    {
        $email = trim($email);

        if ($email === "") {
            return response()->json([
                'message' => 'O email não pode estar vazio ou só com espaços.'
            ], 400);
        }

        $email = strtolower($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'O email é inválido.'
            ], 400);
        }

        // Valida o tamanho de caracteres
        if (mb_strlen($email) > 100) {
            return response()->json([
                'message' => 'O email excede o número limite de caracteres (100)'
            ], 400);
        }

        return true;
    }

    public function Password_Validate(string $password, string $confirmPassword)
    {
        $password = trim($password);
        
        if ($password === "") {
            return response()->json([
                'message' => 'A senha não pode estar vazia ou só com espaços.'
            ], 400);
        }

        if (strlen($password) < 8) {
            return response()->json([
                'message' => 'A senha deve conter no mínimo 8 caracteres.'
            ], 400);
        }

        if ($password !== $confirmPassword) {
            return response()->json([
                'message' => 'Confirmação de senha inválida.'
            ], 400);
        }

        return true;
    }

    public function NIF_Validate(string &$nif)
    {
        if (trim($nif) === "") {
            return response()->json([
                'message' => 'O NIF não pode estar vazio ou só com espaços.'
            ], 400);
        }

        $nif = trim($nif);

        if (!preg_match('/^[0-9]{10}$/', $nif)) {
            return response()->json([
                'message' => "NIF angolano inválido."
            ], 400);
        }

        return true;
    }

    public function Board_Validate(string &$board)
    {
        $board = strtoupper(trim($board));

        if (empty($board)) {
            return response()->json([
                'message' => 'A matrícula é obrigatória.'
            ], 400);
        }
        
        $pattern = '/^[A-Z]{2,3}-\d{2}-\d{2}-[A-Z]{2}$/';

        if (!preg_match($pattern, $board)) {
            return response()->json([
                'message' => 'Formato de matrícula inválido. Use o padrão XX-00-00-XX ou XXX-00-00-XX.'
            ], 400);
        }

        return true;
    }

    public function IdRole_Validate($idRole)
    {
        if (!$idRole) {
            return response()->json([
                'message' => 'O tipo de usuário é obrigatório.'
            ], 400);
        }

        return true;
    }
}
