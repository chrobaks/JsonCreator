
<?php if(isset($view_data['error']) && ! empty($view_data['error'])): ?>
            
<div class="alert alert-danger" role="alert">
<?php echo implode('<br>',$view_data['error']); ?>
</div>
        
<?php endif; ?>

 <?php if(isset($view_data['info']) && ! empty($view_data['info'])): ?>

<div class="alert alert-success" role="alert">
<?php echo implode('<br>',$view_data['info']); ?>
</div>
        
<?php endif; ?>