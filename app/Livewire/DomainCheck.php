<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class DomainCheck extends Component
{
    public $domains = '';
    public $results = [];
    public $isLoading = false;

    protected $rules = [
        'domains' => 'required|string|min:3',
    ];

    public function checkDomains()
    {
        $this->validate();
        $this->prepareDomains();
        $this->makeApiRequest();
    }

    private function prepareDomains()
    {
        $this->results = [];
        $this->isLoading = true;
    }

    private function makeApiRequest()
    {
        try {
            $domainList = array_unique(array_filter(
                preg_split('/[\s,]+/', $this->domains)
            ));

            $response = Http::withToken(config('app.api_token'))
                ->timeout(30)
                ->post('/api/check-domains', ['domains' => $domainList]);

            $this->handleResponse($response);
        } catch (\Exception $e) {
            $this->addError('api_error', $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    private function handleResponse($response)
    {
        if ($response->successful()) {
            $this->results = $response->json();
        } else {
            $error = $response->json()['error'] ?? 'Server xatosi';
            $this->addError('api_error', $error);
        }
    }

    public function render()
    {
        return view('livewire.domain-check');
    }
}
