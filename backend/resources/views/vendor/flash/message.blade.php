<?php
$icon = array(
    'success'   =>'fa-check',
    'warning'   =>'fa-warning',
    'info'      =>'fa-info',
    'danger'    =>'fa-ban',
)
?>
@if (session()->has('flash_notification.message'))
    @if (session()->has('flash_notification.overlay'))
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => session('flash_notification.title'),
            'body'       => session('flash_notification.message')
        ])
    @else
        <div class="callout
                    callout-{{ session('flash_notification.level') }}
                alert alert-dismissable"
        >

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            <h4 style="display: inline;"><i class="icon fa <?=$icon[session('flash_notification.level')];?>"></i> {!! session('flash_notification.message') !!}</h4>

        </div>
    @endif
@endif
