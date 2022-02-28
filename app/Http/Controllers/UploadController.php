<?php

namespace App\Http\Controllers;

use App\Models\_upload;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ret['exec'] = false;
        if($request->has('token_produto')){
            $arquivos = _upload::where('token_produto','=',$request->get('token_produto'))->get();
            if($arquivos){
                $ret['exec'] = true;
                $ret['arquivos'] = $arquivos;
            }
        }
        return response()->json($ret);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $filenameWithExt = $file->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $file->getClientOriginalExtension();
        // Filename to store
        //$fileNameToStore= $filename.'_'.time().'.'.$extension;
        $fileNameToStore= $filename.'_'.time().'.'.$extension;
        // Upload Image
        $dados = $request->all();
        $token_produto = $dados['token_produto'];
        $ultimoValor = _upload::where('token_produto','=',$token_produto)->max('ordem');
        $ordem = $ultimoValor ? $ultimoValor : 0;
        $ordem++;
        $pasta = $dados['pasta'];
        $nomeArquivoSavo = $file->storeAs($pasta,$fileNameToStore);
        //$nomeArquivoSavo = $file->move($fileNameToStore,$pasta);
        $exec = false;

        if($nomeArquivoSavo){
            $exec = true;

            $salvar = _upload::create([
                'token_produto'=>$token_produto,
                'pasta'=>$nomeArquivoSavo,
                'ordem'=>$ordem,
                'nome'=>$filenameWithExt,
                'config'=>json_encode(['extenssao'=>$extension])
            ]);
        }
        $lista = _upload::where('token_produto','=',$token_produto)->get();
        return response()->json(['success'=>$nomeArquivoSavo,'salvar'=>$salvar,'exec'=>$exec,'lista'=>$lista]);
    }

    public function storeVarios(Request $request)
    {
        $dados = $request;
        //$dados->file('arquivo')->store('teste');
        //$d = $dados->all();
        //dd($d);
        $token_produto = '123teste';
        $pasta = 'familias';
        $nomeArquivoSavo = [];
        //Qlib::lib_print($dados->allFiles());
        //die();
        $salvar = false;
        for ($i=0; $i < count($dados->allFiles()['arquivo']); $i++) {
            $file = $dados->allFiles()['arquivo'][$i];
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image

            $nomeArquivoSavo['nome'][$i] = $file->storeAs($pasta,$fileNameToStore);
            $salvar[$i] = _upload::create([
                'token_produto'=>$token_produto,
                'pasta'=>$nomeArquivoSavo['nome'][$i],
                'ordem'=>$i
            ]);

        }
        if($salvar){
            return response()->json(['success'=>'Enviados com sucesso']);
        }else{

        }
        //Qlib::lib_print($salvar);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dados = _upload::find($id);
        $dele_file = false;
        if (Storage::exists($dados->pasta))
            $dele_file = Storage::delete($dados->pasta);

        $delete = _upload::where('id',$id)->delete();
        return response()->json(['exec'=>$delete,'dele_file'=>$dele_file]);
    }
}
