<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest');
    }*/

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'numeroDoc' => 'required|string|min:6|unique:users',
        ],[
        'unique' => ':attribute já existente',
    ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        /*if($data['tipoDoc'] == "CPF"){
            $data['numeroDoc'] = $this->ajeitarCPF($data['numeroDoc']);
            $cpfValido = $this->validarCPF($data['numeroDoc']);
            if ($cpfValido == false){
                return redirect()->back()->with('message', "CPF Inválido!");
            }
        }
        dd($data);*/
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tipoDoc' => $data['tipoDoc'],
            'numeroDoc' => $data['numeroDoc'],
            'idGrupo' => $data['idGrupo'],
            'status' => 'Ativo',
        ]);
    }

    public function ajeitarCPF($cpf){
        $elemento = explode(".",$cpf);
        $primeiraParte = $elemento[0];
        $segundaParte = $elemento[1];
        $terceiraParte = $elemento[2];

        $elemento2 = explode("-", $terceiraParte);
        $terceiraParte = $elemento2[0];
        $quartaParte = $elemento2[1];
        
        $resultado = $primeiraParte . $segundaParte . $terceiraParte . $quartaParte;
        
        return $resultado;
    }

    public function validarCPF($cpf) {
 
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }
}
