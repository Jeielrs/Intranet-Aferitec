var ctx1 = document.getElementsByClassName("pie-chart1");
var ctx2 = document.getElementsByClassName("pie-chart2");
var ctx3 = document.getElementsByClassName("pie-chart3");

var data1 = {
    labels : ["Bom", "Médio", "Ruim"],
    datasets : [
      {
          label : "Preço",
          data : [<?php echo $qtdPrecoBom; ?>, <?php echo $qtdPrecoMedio; ?>, <?php echo $qtdPrecoRuim; ?>],
          backgroundColor : [
            "#32CD32",
            "#FFD700",
            "#FF0000"
          ],
          borderColor : [
            "#006400",
            "#DAA520",
            "#B22222"
          ],
          borderWidth : [1, 1, 1]
      }
    ]
};

var data2 = {
    labels : ["Bom", "Médio", "Ruim"],
    datasets : [
      {
          label : "Prazo",
          data : [<?php echo $qtdPrazoBom; ?>, <?php echo $qtdPrazoMedio; ?>, <?php echo $qtdPrazoRuim; ?>],
          backgroundColor : [
            "#32CD32",
            "#FFD700",
            "#FF0000"
          ],
          borderColor : [
            "#006400",
            "#DAA520",
            "#B22222"
          ],
          borderWidth : [1, 1, 1]

      }
    ]
};

var data3 = {
    labels : ["Bom", "Médio", "Ruim"],
    datasets : [
      {
          label : "Qualidade",
          data : [<?php echo $qtdQualidadeBom; ?>, <?php echo $qtdQualidadeMedio; ?>, <?php echo $qtdQualidadeRuim; ?>],
          backgroundColor : [
            "#32CD32",
            "#FFD700",
            "#FF0000"
          ],
          borderColor : [
            "#006400",
            "#DAA520",
            "#B22222"
          ],
          borderWidth : [1, 1, 1]

      }
    ]
};

var options = {
  legend: {
    display : false
  }
}

var chart1 = new Chart(ctx1, {
  type: 'pie',
  data: data1,
  options: options
});

var chart2 = new Chart(ctx2, {
  type: 'pie',
  data: data1,
  options: options
});

var chart3 = new Chart(ctx3, {
  type: 'pie',
  data: data1,
  options: options
});