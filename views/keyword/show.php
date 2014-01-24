<?php add('header'); ?>
<!-- Content -->
<div id="contentwrapper">
  <div class="main_content">
    <div class="row-fluid" style="margin-top:10px;" id="parameters">
      <h3 class="heading">Informations sur le mot clé: <b><?php echo $controller->getData()->keyword['keyword']; ?></b></h3>
      <div class="span12" style="margin:0px;">
        <table class="table center table-bordered" style="text-align:center;">
          <thead>
            <tr>
              <th style="text-align:center; background-color: #ff7518;" colspan="3">Google Search</th>
              <th style="text-align:center; background-color: #3fb618;;" colspan="2">Google Adwords</th>
              <th style="text-align:center; background-color: #e60033;;">SLA</th>
            </tr>
            <tr>
              <th style="text-align:center;">Recherche Large</th>
              <th style="text-align:center;">Recherche Exact</th>
              <th style="text-align:center;">In Title</th>
              <th style="text-align:center;">Volume Recherche</th>
              <th style="text-align:center;">Competition</th>
              <th style="text-align:center;">Indice</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="text-align:center;font-size:25px;"><?php echo number_format($controller->getData()->keyword['request_large'], 0, ',', ' '); ?></td>
              <td style="text-align:center;font-size:25px;"><?php echo number_format($controller->getData()->keyword['request_exact'], 0, ',', ' '); ?></td>
              <td style="text-align:center;font-size:25px;"><?php echo number_format($controller->getData()->keyword['request_title'], 0, ',', ' '); ?></td>
              <td style="text-align:center;font-size:25px;"><?php echo number_format($controller->getData()->keyword['search'], 0, ',', ' '); ?></td>
              <td style="text-align:center;font-size:25px;"><?php echo $controller->getData()->keyword['competition']; ?></td>
              <td style="text-align:center;font-size:25px;"><?php echo $controller->getData()->indice; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row-fluid" style="margin-top:10px;">
      <div id="graphposition" class="span12" style="margin:0px; min-height:300px;">

      </div>
    </div>
    <div class="row-fluid" style="margin-top:10px;">
      <div class="span6" style="margin:0px;">
        <h3 class="heading" style="text-align:center;">Performances</h3>
        <div class="alert alert-success" style="font-size:20px;">Position la plus haute : <?php echo min($controller->getData()->positions); ?> </div>
        <div class="alert alert-danger" style="font-size:20px;">Position la plus basse : <?php echo max($controller->getData()->positions); ?> </div>
        <div class="alert alert-info" style="font-size:20px;">Evolution sur les 30 derniers jours : + <?php
          reset($controller->getData()->positions);
          echo (current($controller->getData()->positions) - end($controller->getData()->positions))
          ?> places </div>
      </div>
      <div class="span6" style="margin:0px;">
        <h3 class="heading" style="text-align:center;"> TOP 3 Concurrents</h3>
        <table class="table center table-bordered" style="text-align:center;margin-left:10px;">
          <thead>
            <tr>
              <th style="width:10%;text-align:center;">Position</th>
              <th style="width:45%;text-align:center;">Url</th>
              <th style="width:45%;text-align:center;">Title</th>
              <th>PR</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 0; ?>
            <?php foreach ($controller->getData()->concurrents as $concurrent): ?>
              <?php if ($i < 3 && strpos($concurrent['url'], $controller->getData()->url) === false): ?>
                <tr>
                  <td style="width:10%;text-align:center;"><?php echo $concurrent['position']; ?></td>
                  <td style="width:45%;"><?php echo (strlen($concurrent['url']) > 40) ? substr($concurrent['url'], 0, 40).'(...)' : $concurrent['url']; ?></td>
                  <td style="width:45%;"><?php echo (strlen($concurrent['title']) > 40) ? substr($concurrent['url'], 0, 40).'(...)' : $concurrent['title']; ?></td>
                  <td style="text-align:center;">0</td>
                </tr>
                <?php $i++; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="row-fluid" style="margin-top:10px;">
      <div class="span12" style="margin:0px;">
        <h3 class="heading" style="text-align:center;">Top 100</h3>
        <table class="table center table-bordered" style="text-align:center;">
          <thead>
            <tr>
              <th style="width:10%;text-align:center;">Position</th>
              <th style="width:45%;text-align:center;">Url</th>
              <th style="width:45%;text-align:center;">Title</th>
              <th>PR</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($controller->getData()->concurrents as $concurrent): ?>
              <tr>
                <td style="width:10%;text-align:center;"><?php echo $concurrent['position']; ?></td>
                <td style="width:45%;"><?php echo (strlen($concurrent['url']) > 85) ? substr($concurrent['url'], 0, 85).'(...)' : $concurrent['url']; ?></td>
                <td style="width:45%;"><?php echo $concurrent['title']; ?></td>
                <td style="text-align:center;">0</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script>
  $(function() {
    $('#graphposition').highcharts({
      chart: {
        type: 'spline'
      },
      colors: ['#ff7518'],
      title: {
        text: 'Position sur les 30 derniers jours',
        x: -20 //center
      },
      xAxis: {
        type: 'datetime'
      },
      yAxis: {
        title: {
          text: 'Position'
        },
        reversed: true,
        plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }],
        max: 100,
        min: 1
      },
      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        borderWidth: 0
      },
      series: [{
          name: 'Position',
          data: [<?php
            foreach ($controller->getData()->positions as $k => $v) {
              $xpl = explode('-', $k);
              echo "[Date.UTC(" . $xpl[0] . "," . ($xpl[1] - 1) . "," . $xpl[2] . ")," . $v . "],";
            }
            ?>]
        }]
    });
  });


</script>
<?php add('footer'); ?>