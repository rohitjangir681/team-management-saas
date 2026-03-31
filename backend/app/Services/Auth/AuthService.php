<?php 
namespace App\Services\Auth;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data)
    {
        DB::beginTransaction();
        
        try {
            $company = Company::create([
                'name' => $data['company_name']
            ]);
        
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'company_id' => $company->id,
            ]);

            $user->companies()->attach($company->id, [
                'role_id' => 1
            ]);

            DB::commit();

            return [
                'user' => $user
            ];

        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        

    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            throw ValidationException::withMessages([
                 'email' => ['Invalid email or password.'],
            ]);
        }

        return ['user' => $user];
        
    }
    
}
