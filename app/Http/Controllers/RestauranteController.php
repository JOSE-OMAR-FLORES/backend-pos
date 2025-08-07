<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Restaurante;

class RestauranteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'usuario' => 'required|string|unique:restaurantes,usuario|max:50',
            'correo' => 'required|email|unique:restaurantes,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $restaurante = Restaurante::create([
            'nombre' => $validated['nombre'],
            'usuario' => $request->usuario,
            'email' => $validated['correo'],
            'telefono' => $validated['telefono'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Restaurante registrado correctamente.',
            'restaurante' => $restaurante
        ], 201);
    }
}

