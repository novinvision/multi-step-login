<?php

namespace NovinVision\MultiStepLogin\Requests;

use App\Helpers\Str;
use App\Http\Requests\Request;
use App\Http\Requests\TourSearchRequest;
use App\Models\Location;
use App\Models\Tour;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use NovinVision\ProjectHelper\Core\Jalalian;
use NovinVision\ProjectHelper\Rules\JalalianDate;

class AuthenticateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function setNextUrl($url): void
    {
        $this->session()->put('next_url', $url);
    }

    public function nextUrl($default = ''): string
    {
        $nextUrl = $this->session()->get('next_url');
        $this->session()->forget('next_url');
        return urldecode($nextUrl) ?: $default;
    }

    public function usernameColumns(): array
    {
        return config('multi-step-login.username_columns');
    }

    public function userModel(): Model
    {
        return new (config('multi-step-login.model'));
    }

    public function getUserWithUsername(string $username)
    {
        $query = $this->userModel()->newQuery();

        foreach ($this->usernameColumns() as $column) {
            $query->orWhere($column, $username);
        }

        return $query->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() != 'post') return [];

        return [
            'username' => 'required|numeric',
        ];
    }
}
