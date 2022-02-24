<?php

namespace Database\Factories;

use App\Models\Escolaridade;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamiliaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->getGender();
        return [
            'token'=>uniqid(),
            'area_alvo' => $this->getArea(),
            'loteamento'=> $this->getData('loteamento'),
            'id_loteamento'=>0,
            'matricula'=>$this->getData('matricula'),
            'quadra'=> $this->getData('quadra'),
            'lote'=> $this->getData('quadra'),
            'nome_completo'=>$this->faker->name($gender),
            //'cpf'=>'',
            'nome_conjuge'=>$this->faker->name($gender),
            //'cpf_conjuge'=>'',
            'telefone'=>'',
            'escolaridade'=>rand(1,10),
            'estado_civil'=>rand(1,10),
            'situacao_profissional'=>$this->getData('situacao_profissional'),
            'qtd_membros'=>rand(1,10),
            'idoso'=>$this->getData('idoso'),
            'crianca_adolescente'=>$this->getData('crianca_adolescente'),
            'bcp_bolsa_familia'=>'',
            'renda_familiar'=>(double)$this->getData('renda_familiar'),
            'doc_imovel'=>$this->getData('doc_imovel'),
            'endereco'=>'',
            'numero'=>'',
            'bairro'=>'',
            'cidade'=>'',
            'autor'=>1,
            'config'=>'',
            'obs'=>$this->getData('obs'),
            'excluido'=>'n',
            'reg_excluido'=>'',
            'deletado'=>'n',
            'reg_deletado'=>'',
        ];
    }
    private function getArea() : string {
        $area = ['1','4','6'];
        shuffle($area);
        return $area[0];
    }
    private function getData($campo=false) : string {
        if(isset($campo)){
            if($campo=='quadra'){
                $arr = ['70.1','70.4','80.6'];
            }elseif($campo=='loteamento'){
                $arr = ['Camponesa e adjacências','Camponesa e adjacências2','Camponesa e adjacências3'];
            }elseif($campo=='idoso'||$campo=='crianca_adolescente'){
                $arr = ['s','n'];
            }elseif($campo=='renda_familiar'){
                $arr = ['1202.00','2150.56','3300.48'];
            }elseif($campo=='doc_imovel'){
                $arr = ['Escritura Pública de Contrato de Aforamento','Não','Sim'];
            }elseif($campo=='matricula'){
                $arr = ['625','628','745'];
            }elseif($campo=='situacao_profissional'){
                $arr = ['Aposentado(a)','Profissional liberal','Domestico(a)'];
            }elseif($campo=='obs'){
                $arr = ['','Teste de uma observação','não encontrado'];
            }
            shuffle($arr);
            return $arr[0];
        }
    }
    private function getGender() : string {
        $genders = ['male','female'];
        shuffle($genders);
        return $genders[0];
    }

}
