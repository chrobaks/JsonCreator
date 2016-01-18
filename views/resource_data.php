<?php foreach($view_data['resourcestorage']['resource'] as $rowkey => $rowval): ?>
        <div class="jumbotron">
            <div class="row tap-btn">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                
                <p><strong><?php echo $rowkey; ?></strong> (IndexkeyID)</p>
                
                <div class="row tap-data">
                <form action="index.php" method="post" name="<?php echo $rowkey; ?>Form">
                    
                <?php foreach($rowval as $key => $val): ?>
                    
                    <div class="row formrow">
                    
                        <div class="col-md-1">&nbsp;</div>
                        
                        <div class="col-md-3">
                            <strong><?php echo $key; ?> :</strong>
                        </div>
                        
                        <div class="col-md-7">
                            <input type="text" name="<?php echo $key; ?>" value="<?php echo $val; ?>" >
                        </div>
                        
                        <div class="col-md-1">&nbsp;</div>
                    
                    </div>
                    
                <?php endforeach; ?>
                    
                    <div class="row formfooter"> 
                        <div class="col-md-1">&nbsp;</div>
                        <div class="col-md-10">
                            <input type="submit" value="speichern" >&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a href="index.php?delete=<?php echo $rowkey; ?>&activestorage=<?php echo $view_data['resourcestorage']['jsonfile']; ?>" target="_self" title="Resource löschen" >Resource löschen</a>
                            <input type="hidden" name="<?php echo $view_data['resourcestorage']['resourceindexkey']; ?>" value="<?php echo $rowkey; ?>" >
                            <input type="hidden" name="resourceaction" value="edit" >
                            <input type="hidden" name="activestorage" value="<?php echo $view_data['resourcestorage']['jsonfile']; ?>" >
                        </div>
                        <div class="col-md-1">&nbsp;</div>
                    </div>
                </form>
                </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
<?php endforeach; ?>