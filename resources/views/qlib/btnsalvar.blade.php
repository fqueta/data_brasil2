
@php
    if(isset($config['ac']) && $config['ac']=='alt'){
        $_GET['redirect'] = isset($_GET['redirect']) ? $_GET['redirect'] : route($config['route'].'.index').'?idCad='.$value['id'];

    }
@endphp
<div class="col-md-12 div-salvar bg-light">
        <button type="button" btn-volter="true" href="{{route($config['route'].'.index')}}" onclick="btVoltar($(this))" redirect="{{@$_GET['redirect']}}" class="btn btn-outline-secondary"><i class="fa fa-chevron-left"></i> Voltar</button>
        @if (isset($config['ac']) && $config['ac']=='alt')
            <a href="{{route($config['route'].'.create')}}" class="btn btn-default"> <i class="fas fa-plus"></i> Novo cadastro</a>
        @endif
        <button type="submit" btn="permanecer" class="btn btn-primary">Salvar e permanecer</button>
        <button type="submit" btn="sair"  class="btn btn-outline-primary">Salvar e Sair <i class="fa fa-chevron-right"></i></button>
</div>
<script>
    function btVoltar(obj){
        var href = obj.attr('href'),redirect = obj.attr('redirect');
        if(redirect){
            window.location = redirect;
        }else{
            window.location = href;
        }
    }
</script>
