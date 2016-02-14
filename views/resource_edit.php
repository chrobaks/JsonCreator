            <div class="jumbotron">
            <form name="newResourceForm" method="post" action="index.php">
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-10">
                        <h2>Neue Resource eintragen</h2>
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
                
                <?php foreach($view_data['resourcestorage']['resourcekeys'] as $key): ?>
                    
                <div class="row formrow">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3">
                        <strong><?php echo $key; ?> :</strong><?php if($key===$view_data['resourcestorage']['resourceindexkey']){echo(' (Indexkey)');} ?>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="<?php echo $key; ?>" value="" >
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
                    
                <?php endforeach; ?> 
                
                <div class="row formfooter">        
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-10">
                        <input type="submit" value="speichern">
                        <input type="hidden" name="act" value="newresource" >
                        <input type="hidden" name="resourceaction" value="new" >
                        <input type="hidden" name="activestorage" value="<?php echo $view_data['resourcestorage']['jsonfile']; ?>" >
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
            </form>
            </div>