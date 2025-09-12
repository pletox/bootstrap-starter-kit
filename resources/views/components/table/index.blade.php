@props([
    'border' => false,
    'rounded' => false,
    'hover' => false,
    'striped' => false,
    'flushed' => false,
    'parentClass' => ''
])

<table {{ $attributes->merge([
        'class' =>
            'table display nowrap w-100  text-md' . // text-sm => small
            ($flushed ? 'border-top ' : '') .
            ($border ? 'table-bordered ' : '') .
            ($hover ? 'table-hover ' : '') .
            ($striped ? 'table-striped ' : '')
    ]) }} style="width: 100%;">
    {{ $slot }}
</table>

<style>
    .table.text-md {
        font-size: 14.4px !important;
    }
</style>


