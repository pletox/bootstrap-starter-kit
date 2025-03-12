<form action="{{ $attributes->get('action', '') }}"
      method="{{ strtoupper($attributes->get('method', 'POST')) !== 'GET' ? 'POST' : 'GET' }}"
      x-data
      x-on:reset="() => {
          $el.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          $el.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
      }"
    {{ $attributes->whereDoesntStartWith(['action', 'method'])->merge(['class' => '']) }}>

    @csrf
    @if (!in_array(strtoupper($attributes->get('method', 'POST')), ['GET', 'POST']))
        @method($attributes->get('method'))
    @endif

    {{ $slot }}
</form>
