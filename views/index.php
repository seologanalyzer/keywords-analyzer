<?php add('header'); ?>
<!-- Content -->
<div id="contentwrapper">
  <div class="main_content">
    <div class="row-fluid" style="margin-top:10px;">
      <div class="span12 alert alert-success" style="margin:0px;">
        <form method="post" action="/index/addkeyword" style="margin:0;">
          Ajouter un nouveau mot clé:<input type="text" value="" placeholder="seo log analyzer" name="keyword" class="span3" style="margin:10px;" /><input type="submit" value="Ajouter" />
        </form>
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
              <th style="text-align:center;">Position</th>
              <th style="text-align:center;">Supprimer</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($controller->getData()->keywords as $keyword): ?>              
              <tr id="keyword_<?php echo $keyword['id_keyword']; ?>">
                <td style="text-align:center;"><?php echo $keyword['keyword']; ?></td>
                <td style="text-align:center;"><?php echo number_format($keyword['request_large'], 0, ',', ' '); ?></td>
                <td style="text-align:center;"><?php echo number_format($keyword['request_exact'], 0, ',', ' '); ?></td>
                <td style="text-align:center;"><?php echo number_format($keyword['request_title'], 0, ',', ' '); ?></td>
                <td style="text-align:center;"><?php echo number_format($keyword['search'], 0, ',', ' '); ?></td>
                <td style="text-align:center;"><a href="/keyword/<?php echo $keyword['id_keyword']; ?>"><button class="btn btn-success">Voir</button></a></td>
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
    function deleteKeyword(id_keyword){
      if(confirm('Voulez-vous supprimer ce mot clé')){
        $.post("/keyword/delete", {id: id_keyword},
        function(data) {
          $.sticky('Mot clé supprimé', {autoclose: 1500, position: "top-right", type: "st-white"});
          $('#keyword_' + id_keyword).hide();
        }, "text");
      }
    }
</script>
<?php add('footer'); ?>
