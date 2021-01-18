$(document).ready(function(){

  //o evento Ajax ocorrerá quando o usuário clicar no botão
  $("#sincronizar").on('click', function(){
    $("#carregando").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    $.ajax({
      type: 'POST',
      url: '/sincronizar',
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#conteudo").html(data);
        console.log('carregado');
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#carregando").hide();
      });
    });

      //o evento Ajax ocorrerá quando o usuário clicar no botão
  $("#sincRequisicao").on('click', function(){
    $("#carregando").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    $.ajax({
      type: 'POST',
      url: '/sincRequisicao',
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#conteudo").html(data);
        console.log('carregado');
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#carregando").hide();
      });
    });

  //o evento Ajax ocorrerá quando o usuário clicar no botão
  $("#sincPedido").on('click', function(){
    $("#carregando").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    $.ajax({
      type: 'POST',
      url: '/sincPedido',
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#conteudo").html(data);
        console.log('carregado');
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#carregando").hide();
      });
    });


  //o evento Ajax ocorrerá quando o usuário clicar no botão
  $("#numitens").on('change', function(e){

    let numitens = $(e.target).val()
    $("#pergunta").hide();
    e.preventDefault();  //->ativado pois evita o comportamento padrão
    $("#loading").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    $("#numeroitens").attr('value', numitens) //atribui o value de numitens no input hidden do form
    console.log($('#numitens'));
    $.ajax({
      type: 'POST',
      url: '/loadBoxProd',
      data:`numitens=${numitens}`,
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#content").html(data);
        console.log('carregado');
        $(qsj());
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#loading").hide();
      });
    });

    function qsj() {
      $('.dinheiro').mask('#.##0,00', {reverse: true});
      $('.dinheiro').blur(function(){
        //console.log($('.dinheiro'));
        grana = ($(this).val());        
        if(grana.replace(',','.') < 10.00){
             $(this).css({'border-color' : '#F00', 'padding': '2px'});
             alert('O valor unitário não pode ser inferior à R$ 10,00 !');
             $(this).val('');
            }else{
              $(this).css({'border-color' : '#0B610B'});
            }
        });
    };


  $("#filtroRelatorio").on('click', function(e){
    e.preventDefault();
    $("#loading").show();
    $.ajax({
      type: 'POST',
      url: '/buscaRelatorio',
      data: {
        "dtIniCreate":  $("#dtIniCreate").val(),
        "dtFimCreate":  $("#dtFimCreate").val(),
        "solicitante":  $("#solicitante").val(),
        "liberador":    $("#liberador").val(),
        "categoria":    $("#categoria").val(),
        "departamento": $("#departamento").val(),
        "dtIniMov":     $("#dtIniMov").val(),
        "dtFimMov":     $("#dtFimMov").val(),
        "status":       $("#status").val(),
        "classificacao":$("#classificacao").val(),
        "alterador":    $("#alterador").val(),
        "produto1":     $("#produto1").val(),
        "produto2":     $("#produto2").val(),
        "produto3":     $("#produto3").val(),
        "produto4":     $("#produto4").val(),
        "produto5":     $("#produto5").val(),
        "provedor":     $("#provedor").val(),
        "sql":          $("#sql").val(),
        "sql_qtd":      $("#sql_qtd").val(),
        "pagina":       $("#pagina").val(),

      },
      datatype: "html"
    }).done(function(data){
      $("#content").html(data);
      console.log('carregado');
    })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#loading").hide();
      });
  })

  
  $("#pesquisar").on('click', function(){
    //$("#carregando").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    
    $.ajax({
      type: 'POST',
      url: '/searchDays',
      data: {
          "data":        $("#data").val(),
          "user":        $("#user").val(),
          },
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#dadosHoras").html(data);
        console.log('carregado');
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      //.always(function(){
      //  $("#carregando").hide();
      //});
    });


  $("#filtro").on('change', function(e){

    let filtro = $(e.target).val()
    e.preventDefault();  //->ativado pois evita o comportamento padrão
    $("#loading").show(); // exibe o loading na div #loading
    //iniciando a requisição Ajax
    //$("#numeroitens").attr('value', numitens) //atribui o value de numitens no input hidden do form
    console.log($('#filtro'));
    if (filtro == 1) {
      $("#graficos").show();
      $("#rc").hide();
      $("#datas").hide();
      $("#provedor").hide();
      $("#loading").hide();
      $.ajax({
        type: 'POST',
        url: '/loadProvedor',
        data:`filtro=${filtro}`,
        dataType: "html" 
   
      }).done(function(data){     
        $("#content").html(data);        
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#loading").hide();
      });
    }
    if (filtro == 2) {
      $("#graficos").show();
      $("#rc").hide();
      $("#datas").hide();
      $("#provedor").show();
      $("#loading").hide();
    }
    if (filtro == 3) {
      $("#graficos").show();
      $("#rc").show();
      $("#datas").hide();
      $("#provedor").hide();
      $("#loading").hide();
    }
    if (filtro == 4) {
      $("#graficos").show();
      $("#rc").hide();
      $("#datas").show();
      $("#provedor").hide();
      $("#loading").hide();
    }

  });

  $("#searchProvedor").submit(function(e){
    e.preventDefault();  //->ativado pois evita o comportamento padrão
    console.log('entrou');    
    $("#loading").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    
    $.ajax({
      type: 'POST',
      url: '/loadProvedor',
      data: {
          "prov": $("#provedorAjax").val(),
          "filtro": $("#filtroAjaxProvedor").val(),
      },
      dataType: "html"
   
    }).done(function(data){     
        $("#content").html(data);        
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      .always(function(){
        $("#loading").hide();
      });
    });

  $("#searchRC").submit(function(e){
    e.preventDefault();  //->ativado pois evita o comportamento padrão
    console.log('entrou');    
    $("#loading").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    
    $.ajax({
      type: 'POST',
      url: '/loadProvedor',
      data: {
          "codreq": $("#rcAjax").val(),
          "filtro": $("#filtroAjaxRC").val(),
      },
      dataType: "html"
   
    }).done(function(data){     
        $("#content").html(data);
    })
    .fail(function(data){
      alert("Erro na requisição Ajax");
    })
    .always(function(){
      $("#loading").hide();
    });
  });

  $("#searchData").submit(function(e){
    e.preventDefault();  //->ativado pois evita o comportamento padrão
    console.log('entrou');    
    $("#loading").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    
    $.ajax({
      type: 'POST',
      url: '/loadProvedor',
      data: {
          "dt1": $("#date1").val(),
          "dt2": $("#date2").val(),
          "filtro": $("#filtroAjaxData").val(),
      },
      dataType: "html"
   
    }).done(function(data){     
        $("#content").html(data);
    })
    .fail(function(data){
      alert("Erro na requisição Ajax");
    })
    .always(function(){
      $("#loading").hide();
    });
  });

});
