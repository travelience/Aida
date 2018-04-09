@if( $alert = hasAlert() )

    <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
    {{ $alert['message'] }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
    </div>


<?php alert_flush() ?>

@endif