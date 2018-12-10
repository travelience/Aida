<?php
    $styles = [
        'success' => 'text-white bg-green',
        'danger' => 'text-white  bg-red',
        'warning' => 'text-white bg-yellow-dark',
        'info' => 'text-white bg-blue',
        'default' => 'text-white bg-grey-dark'
    ];
?>
@if( $alert = hasAlert() )

<div class="p-4 text-center {{ $styles[$alert['type']] }}">
    {{ $alert['message'] }}
</div>


<?php alert_flush() ?>

@endif