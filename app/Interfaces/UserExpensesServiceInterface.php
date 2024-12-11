<?php

namespace App\Interfaces;

use App\Models\UserExpense;
use Illuminate\Http\Request;

interface UserExpensesServiceInterface
{
    public function store(Request $request): UserExpense;
    public function destroy(int $id): bool;
    public function update(Request $request, int $id): bool;
}
