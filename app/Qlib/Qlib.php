<?php
namespace App\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class Qlib
{
  public function lib_print($data){
      if(is_array($data) || is_object($data)){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
      }else{
        echo $data;
      }
  }
  static function dtBanco($data) {
			$data = trim($data);
			if (strlen($data) != 10)
			{
				$rs = false;
			}
			else
			{
				$arr_data = explode("/",$data);
				$data_banco = $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
				$rs = $data_banco;
			}
			return $rs;
	}
  static function dataExibe($data) {
			$val = trim(strlen($data));
			$data = trim($data);$rs = false;
			if($val == 10){
					$arr_data = explode("-",$data);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco;
			}
			if($val == 19){
					$arr_inic = explode(" ",$data);
					$arr_data = explode("-",$arr_inic[0]);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco."-".$arr_inic[1] ;
			}

			return $rs;
	}
  static function lib_json_array($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = $json;
		}elseif(!empty($json) && Qlib::isJson($json)&&!is_array($json)){
			$ret = json_decode($json,true);
		}
		return $ret;
	}
	public static function lib_array_json($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = json_encode($json,JSON_UNESCAPED_UNICODE);
		}
		return $ret;
	}

    static function isJson($string) {
		$ret=false;
		if (is_object(json_decode($string)) || is_array(json_decode($string)))
		{
			$ret=true;
		}
		return $ret;
	}
  static function Meses($val=false){
  		$mese = array('01'=>'JANEIRO','02'=>'FEVEREIRO','03'=>'MARÇO','04'=>'ABRIL','05'=>'MAIO','06'=>'JUNHO','07'=>'JULHO','08'=>'AGOSTO','09'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
  		if($val){
  			return $mese[$val];
  		}else{
  			return $mese;
  		}
	}
  static function totalReg($tabela, $condicao = false,$debug=false){
			//necessario
			$sql = "SELECT COUNT(*) AS totalreg FROM {$tabela} $condicao";
			if($debug)
				 echo $sql.'<br>';
			//return $sql;
			$td_registros = DB::select($sql);
			if(isset($td_registros[0]->totalreg) && $td_registros[0]->totalreg > 0){
				return $td_registros[0]->totalreg;
			}else
				return 0;
	}
  static function zerofill( $number ,$nroDigo=6, $zeros = null ){
		$string = sprintf( '%%0%ds' , is_null( $zeros ) ?  $nroDigo : $zeros );
		return sprintf( $string , $number );
	}
  static function encodeArray($arr){
			$ret = false;
			if(is_array($arr)){
				$ret = base64_encode(json_encode($arr));
			}
			return $ret;
	}
  static function decodeArray($arr){
			$ret = false;
			if($arr){
				//$ret = base64_encode(json_encode($arr));
				$ret = base64_decode($arr);
				$ret = json_decode($ret,true);

			}
			return $ret;
	}
    static function qForm($config=false){
        if(isset($config['type'])){
            $config['campo'] = isset($config['campo'])?$config['campo']:'teste';
            $config['label'] = isset($config['label'])?$config['label']:false;
            $config['placeholder'] = isset($config['placeholder'])?$config['placeholder']:false;
            $config['selected'] = isset($config['selected']) ? $config['selected']:false;
            $config['tam'] = isset($config['tam']) ? $config['tam']:'12';
            $config['col'] = isset($config['col']) ? $config['col']:'md';
            $config['event'] = isset($config['event']) ? $config['event']:false;
            $config['ac'] = isset($config['ac']) ? $config['ac']:'cad';
            $config['option_select'] = isset($config['option_select']) ? $config['option_select']:true;
            $config['label_option_select'] = isset($config['label_option_select']) ? $config['label_option_select']:'Selecione';
            $config['option_gerente'] = isset($config['option_gerente']) ? $config['option_gerente']:false;
            $config['class'] = isset($config['class']) ? $config['class'] : false;
            $config['style'] = isset($config['style']) ? $config['style'] : false;
            $config['class_div'] = isset($config['class_div']) ? $config['class_div'] : false;

            return view('qlib.campos_form',['config'=>$config]);
        }else{
            return false;
        }
    }
    static function sql_array($sql, $ind, $ind_2, $ind_3 = '', $leg = '',$type=false){
        $table = DB::select($sql);
        $userinfo = array();
        if($table){
            //dd($table);
            for($i = 0;$i < count($table);$i++){
                $table[$i] = (array)$table[$i];
                if($ind_3 == ''){
                    $userinfo[$table[$i][$ind_2]] =  $table[$i][$ind];
                }elseif(is_array($ind_3) && isset($ind_3['tab'])){
                    /*É sinal que o valor vira de banco de dados*/
                    $sql = "SELECT ".$ind_3['campo_enc']." FROM `".$ind_3['tab']."` WHERE ".$ind_3['campo_bus']." = '".$table[$i][$ind_2]."'";
                    $userinfo[$table[$i][$ind_2]] = $sql;
                }else{
                    if($type){
                        if($type == 'data'){
                            /*Tipo de campo exibe*/
                            $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . Qlib::dataExibe($table[$i][$ind_3]);
                        }
                    }else{
                        $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . $table[$i][$ind_3];
                    }
                }
            }
        }

        return $userinfo;
    }
    static function formatMensagem($config=false){
        if($config){
            $config['mens'] = isset($config['mens']) ? $config['mens'] : false;
            $config['color'] = isset($config['color']) ? $config['color'] : false;
            $config['time'] = isset($config['time']) ? $config['time'] : 4000;
            return view('qlib.format_mensagem', ['config'=>$config]);
        }else{
            return false;
        }
	}
}
