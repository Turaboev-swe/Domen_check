<?php

namespace App\Http\Controllers;

use App\DTO\DomainCheckResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DomainCheckController extends Controller
{
    public function checkDomains(Request $request)
    {
        if (!$request->bearerToken() || $request->bearerToken() !== config('app.api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $domains = $request->input('domains');
        if (is_string($domains)) {
            $domains = preg_split('/[\s,]+/', $domains);
        }

        $validator = Validator::make(['domains' => $domains], [
            'domains' => 'required|array|min:1',
            'domains.*' => 'required|string|regex:/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $results = [];
        foreach ($validator->validated()['domains'] as $domain) {
            $results[] = $this->checkSingleDomain(strtolower(trim($domain)));
        }

        return response()->json($results);
    }

    private function checkSingleDomain(string $domain): DomainCheckResponse
    {
        $isAvailable = rand(0, 1) === 1;
        $expiryDate = $isAvailable ? null : now()->addYears(rand(1, 5))->format('Y-m-d');

        return new DomainCheckResponse(
            domain: $domain,
            isAvailable: $isAvailable,
            expiryDate: $expiryDate,
        );
    }
}
