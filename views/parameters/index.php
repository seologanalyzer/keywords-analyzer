<?php add('header'); ?>
<!-- Content -->
<div id="contentwrapper">
  <div class="main_content">
    <div class="row-fluid" style="margin-top:10px;">
      <div class="span12" style="margin:0px;">
        <h3 class="heading">Vos paramètres</h3>
        <form method="post" action="/parameters/record" style="margin:0;">
          Url de votre site : <input type="text" value="<?php echo $controller->getData()->parameters['url']; ?>" name="url" class="span3" style="margin:10px;" />
          <br/>
          Langue de Google : <select name="gg" class="span1" style="margin:10px;">
            <option <?php if ($controller->getData()->parameters['gg'] == 'fr') echo 'selected="selected"'; ?> value="fr">FR</option>
            <option <?php if ($controller->getData()->parameters['gg'] == 'de') echo 'selected="selected"'; ?> value="de">DE</option>
            <option <?php if ($controller->getData()->parameters['gg'] == 'co.uk') echo 'selected="selected"'; ?> value="co.uk">CO.UK</option>
            <option <?php if ($controller->getData()->parameters['gg'] == 'it') echo 'selected="selected"'; ?> value="it">IT</option>
            <option <?php if ($controller->getData()->parameters['gg'] == 'es') echo 'selected="selected"'; ?> value="es">ES</option>

          </select>
          <br/>
          Délai entre deux requêtes : <input type="text" value="<?php echo $controller->getData()->parameters['delay']; ?>" name="delay" class="span1" style="margin:10px;" /> secondes
          <br/>
          Fréquence des positions : <input type="text" value="<?php echo $controller->getData()->parameters['frequency']; ?>" name="frequency" class="span1" style="margin:10px;" /> jours
          <hr/>
          Votre nom : <input type="text" value="<?php echo $controller->getData()->parameters['name']; ?>" class="span3" style="margin:10px;"  /><br/>
          Ancien mot de passe : <input type="text" value="" class="span3" style="margin:10px;"  /><br/>
          Nouveau mot de passe : <input type="text" value="" class="span3" style="margin:10px;"  /><br/>
          Vérification nouveau mot de passe : <input type="text" value="" class="span3" style="margin:10px;"  /><br/>
          <br/><br/>
          <input type="submit" class=" btn btn-success" value="Enregistrer" />
        </form>
      </div>
    </div>

  </div>
</div>
<!-- // Content END -->
<?php add('footer'); ?>
