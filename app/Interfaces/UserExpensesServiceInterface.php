<?php

namespace App\Interfaces;

use App\DataTransferObjects\ExpenseDto;
use Illuminate\Http\Request;

interface UserExpensesServiceInterface
{
    public function store(Request $request): ExpenseDto;
    public function destroy(int $id): bool;
    public function update(Request $request, int $id): bool;
}
