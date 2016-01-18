
            <div class="jumbotron">
            <form name="jsonstorageForm" method="post" action="index.php">
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-10">
                        <h2>Alle Json-Dateien</h2>
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
                <div class="row formrow">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3">
                        <strong>Datei-Auswahl :</strong>
                    </div>
                    <div class="col-md-7">
                        <select name="activestorage">
                            <option value=""> - keine Auswahl - </option>
                            <?php foreach($view_data['jsonstorage']['jsons'] as $key): ?>
                                <?php $slctd = ''; if ($key===$view_data['resourcestorage']['jsonfile']) {$slctd = ' selected="selected"';} ?>
                                <option value="<?php echo $key; ?>"<?php echo $slctd; ?>><?php echo $key; ?></option>
                                
                            <?php endforeach; ?> 
                            
                        </select>
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
                <div class="row formfooter">        
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-10">
                        <input type="submit" value="Datei zeigen">&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" onclick="document.location.href = 'index.php?deletefile=' + document.jsonstorageForm.activestorage.value.replace(/\.json$/,'')" title="Resource löschen" >Datei löschen</a>
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
            </form>
            </div>