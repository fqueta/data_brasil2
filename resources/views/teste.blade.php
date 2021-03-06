@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Pagina de teste</h1>
@stop

@section('content')
<section class="content">
    <div class="row">
        <div id="conteudo" class="col-md-12">

            <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>
                <p>Lorem Ipsum</p>

        </div>
        <a href="" target="_blank" rel="mais">Veja mais</a>
        <tema2 style="display: none">{{base64_encode('<p>{data} - html {nome} </p>')}}</tema2>
         <div id="preload" style="display: none">Carregando...</div>
    </div>


</section>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
<style>
    #conteudo{
        height:200px;
        overflow-y:auto;
    }
</style>
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
    <script>
        function converJsonHTML(res,tema2){
            if(typeof tema2 == 'undefined'){
                tema2 = atob($('tema2').html());
            }
            var ret = '';
            if(res.data){
               var d = res.data;
               for (let i = 0; i < d.length; i++) {
                   //const element = array[i];
                   ret += tema2.replaceAll('{nome}',d[i].file_file_name);
                   ret = ret.replaceAll('{data}',d[i].date);
               }
            }
            //console.log(ret);
            return ret;
        }
        function requisicao(select,url,data){
            if(typeof url == 'undefined'){
                return;
            }
            if(typeof data == 'undefined'){
                data = '';
            }
            $.ajax({
                url:url, //P??gina PHP que seleciona postagens
                type:'GET', // m??todo post, GET ...
                data: data, //seus param??tros
                dataType:'json',
                beforeSend: function(){
                    $('#preload').fadeIn();
                },
                complete: function(){
                    $('#preload').fadeOut("fast");
                },
                success: function(res){ // sucesso de retorno executar fun????o
                    var cont = converJsonHTML(res);
                    $(select).append(cont); // adiciona o resultado na div #conteudo
                } // fim success
            }); // fim ajax
        }
        function paginacaoInfinita(select,limit,page){
            if(typeof select == 'undefined'){
                return;
            }
            if(typeof limit == 'undefined'){
                limit = 20;
            }
            if(typeof page == 'undefined'){
                page = 2;
            }
            //var page = 1;
            $(select).scroll(function() {
                console.log($(this).get(0).scrollHeight);
                if ($(this).scrollTop() + $(this).height() == $(this).get(0).scrollHeight) {
                    requisicao(select,'{{route('teste.ajax')}}?limit='+limit+'&page='+page);
                //$(select).append($(select).html());
                    page++;
                 }
            });

        }
        $(document).ready(function() {
            //paginacaoInfinita('#conteudo');
            var page = 2;
            $('[rel="mais"]').on('click',function(e){
                e.preventDefault();
                requisicao('#conteudo','{{route('teste.ajax')}}?limit=20&page='+page);

                //alert('gora');
                page++;
            });
        });
    </script>
@stop
