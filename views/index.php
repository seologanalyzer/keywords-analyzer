<?php add('header'); ?>
<!-- Content -->
<div id="contentwrapper">
  <div class="main_content">
    <!--<div class="row-fluid" style="margin-top:10px;" id="parameters">
      <div class="span12 alert alert-warning" style="margin:0px;">
        Récapitulatif de configuration (Site : <?php echo $controller->getData()->parameters['url']; ?>, Langue : <?php echo strtoupper($controller->getData()->parameters['gg']); ?>, Fréquence : <?php echo $controller->getData()->parameters['frequency']; ?> jours, Délai : <?php echo $controller->getData()->parameters['delay']; ?> secondes)
        <span style="float:right;cursor:pointer;" onclick="$('#parameters').hide()">×</span>
      </div>
    </div>-->
    <div class="row-fluid" style="margin-top:10px;">
      <form method="post" class="span12 alert alert-info" action="/index/addkeyword" style="margin:0;background-color:#007fff;">
        Ajouter un (ou plusieurs) nouveau mot clé:<input type="text" value="" placeholder="seo log analyzer, sla, yolo" name="keyword" class="span3" style="margin:-2px 5px 0 5px;;" /><input type="submit" class="btn btn-inverse" value="Ajouter" style="padding:4px 12px;" />
      </form>
    </div>
    <div class="row-fluid" style="margin-top:0px;">
      <h3 class="heading">Chiffres Clés</h3>
      <div class="span12" style="margin-left:0px!important;">
        <div class="row-fluid">
          <div class="span3 alert alert-success" style="text-align:center;">
            <p style="font-size:30px;"><?php echo $controller->getData()->numbers[0]; ?></p>
            Mots clés en première page
          </div>
          <div class="span3 alert alert-warning" style="text-align:center;">
            <p style="font-size:30px;"><?php echo $controller->getData()->numbers[1]; ?></p>
            Mots clés en page 2 ou 3
          </div>
          <div class="span3 alert alert-danger" style="text-align:center;">
            <p style="font-size:30px;"><?php echo $controller->getData()->numbers[2]; ?></p>
            Mots clés en page 4 ou 5
          </div>
          <div class="span3 alert alert-danger" style="text-align:center;background-color:#000000;">
            <p style="font-size:30px;"><?php echo $controller->getData()->numbers[3]; ?></p>
            Mots clés après la page 5
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid" style="margin-top:0px;">
      <div class="span12" >
        <h3 class="heading">Vos mots clés</h3>
        <table class="table center" style="text-align:center;" id="table">
          <thead>
            <tr>
              <th style="text-align:center;">Mot clé</th>
              <th style="text-align:center;">Recherche Large</th>
              <th style="text-align:center;">Recherche Exact</th>
              <th style="text-align:center;">In Title</th>
              <th style="text-align:center;">Volume Recherche</th>
              <th style="text-align:center;">Competition</th>
              <th style="text-align:center;">Position</th>
              <th style="text-align:center;">Détail</th>
              <th style="text-align:center;">Supprimer</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($controller->getData()->keywords as $keyword): ?>              
              <tr id="keyword_<?php echo $keyword['id_keyword']; ?>">
                <td style="text-align:center;"><?php echo $keyword['keyword']; ?></td>
                <td style="text-align:center;">
                  <!--<?php
                  for ($x = strlen($keyword['request_large']); $x < 12; $x++) {
                    echo '0';
                  }echo $keyword['request_large'];
                  ?>-->
                  <?php echo number_format($keyword['request_large'], 0, ',', ' '); ?>
                </td>
                <td style="text-align:center;">
                  <!--<?php
                  for ($x = strlen($keyword['request_exact']); $x < 12; $x++) {
                    echo '0';
                  }echo $keyword['request_exact'];
                  ?>-->
                  <?php echo number_format($keyword['request_exact'], 0, ',', ' '); ?>
                </td>
                <td style="text-align:center;">
                  <!--<?php
                  for ($x = strlen($keyword['request_title']); $x < 12; $x++) {
                    echo '0';
                  }echo $keyword['request_title'];
                  ?>-->
                  <?php echo number_format($keyword['request_title'], 0, ',', ' '); ?>
                </td>
                <td style="text-align:center;">
                  <!--<?php
                  for ($x = strlen($keyword['search']); $x < 12; $x++) {
                    echo '0';
                  }echo $keyword['search'];
                  ?>-->
                  <?php echo number_format($keyword['search'], 0, ',', ' '); ?>
                </td>
                <td style="text-align:center;"><?php echo ($keyword['competition']); ?></td>
                <td style="text-align:center;"><?php echo ($keyword['position'] == 0) ? '100' : $keyword['position']; ?></td>
                <td style="text-align:center;"><a href="/keyword/show/<?php echo $keyword['id_keyword']; ?>"><button class="btn btn-inverse">Voir</button></a></td>
                <td style="text-align:center;"><button class="btn btn-danger" onclick="deleteKeyword('<?php echo $keyword['id_keyword']; ?>')">Supprimer</button></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- // Content END -->
<script type="text/javascript" src="/lib/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/lib/datatables/extras/TableTools/media/js/TableTools.min.js"></script>
<script>
                  $(document).ready(function() {
                    $('#table').dataTable({
                      "oLanguage": {"sInfoThousands": " "},
                      "sDom": "<'row'<'span6'l><'span6'Tf>r>t<'row'<'span6'i><'span6'p>>",
                      "sPaginationType": "bootstrap",
                      "oTableTools": {
                        "sSwfPath": "/lib/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
                      },
                      "aoColumnDefs": [
                        {"sType": "string", "aTargets": [1, 2, 3, 4]}
                      ]
                    });
                  });
                  function deleteKeyword(id_keyword) {
                    if (confirm('Voulez-vous supprimer ce mot clé')) {
                      $.post("/keyword/delete", {id: id_keyword},
                      function(data) {
                        $.sticky('Mot clé supprimé', {autoclose: 1500, position: "top-right", type: "st-white"});
                        $('#keyword_' + id_keyword).hide();
                      }, "text");
                    }
                  }
</script>
<?php add('footer'); ?>
