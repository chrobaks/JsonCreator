        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?php if($view_data['activelayer'] === 'new'){echo(' class="active"');} ?>><a href="#new" role="tab" data-toggle="tab">Neue Json</a></li>
            <li role="presentation"<?php if($view_data['activelayer'] === 'all'){echo(' class="active"');} ?>><a href="#all" role="tab" data-toggle="tab">Alle Json</a></li>
        </ul>