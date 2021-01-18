document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br',
        plugins: ['interaction', 'dayGrid'],
        //defaultDate: '2019-04-12',
        editable: true,
        eventLimit: true,
        events: 'list_eventos.php',
        extraParams: function () {
            return {
                cachebuster: new Date().valueOf()
            };
        },
        selectable: true,
        select: function (info) {
            //alert('Início do evento: ' + info.start.toLocaleDateString());
            $('#visualizar #start').val(info.start.toLocaleDateString());
            $('#visualizar #start').text(info.start.toLocaleDateString());
            $('#visualizar').modal('show');
        }
    });

    calendar.render();


  //o evento Ajax ocorrerá quando o usuário clicar no botão
  $("#mostrar").on('click', function(){
    //$("#carregando").show(); // exibe o loading na div #carregando
    //iniciando a requisição Ajax
    let data = $("#start").val();
    console.log(data);
    $.ajax({
      type: 'POST',
      url: '/showTimes',
      data:`data=${data}`,
      dataType: "html"
      //success: dados => {console.log(dados)},
      //error: erro => {console.log(erro)} 
   
    }).done(function(data){
     
        $("#retornoAjax").html(data);
        console.log('carregado');
      })
      .fail(function(data){
        alert("Erro na requisição Ajax");
      })
      //.always(function(){
      //  $("#carregando").hide();
      //});
    });
    
});