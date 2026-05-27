<div class="row g-3 mb-3" data-home-counters data-url="{{ route('home.counters') }}">
    <div class="col-12 col-md-4">
        <x-stat-card
            label="Categories"
            icon="lucide-database"
            loading
            data-home-counter="total"
            aria-busy="true"
        />
    </div>

    <div class="col-12 col-md-4">
        <x-stat-card
            label="Active Records"
            icon="lucide-circle-check"
            icon-bg="bg-green-100"
            icon-class="w-5 h-5 text-green-700"
            loading
            data-home-counter="active"
            aria-busy="true"
        />
    </div>

    <div class="col-12 col-md-4">
        <x-stat-card
            label="Inactive Records"
            icon="lucide-circle-pause"
            icon-bg="bg-yellow-100"
            icon-class="w-5 h-5 text-yellow-700"
            loading
            data-home-counter="inactive"
            aria-busy="true"
        />
    </div>
</div>

<script type="module">
    $(function () {
        const $counters = $('[data-home-counters]');

        if (!$counters.length || $counters.data('loaded') === true) {
            return;
        }

        $counters.data('loaded', true);

        axios.post($counters.data('url'))
            .then((response) => {
                const values = response.data.counters || {};
                const formatter = new Intl.NumberFormat();

                $counters.find('[data-home-counter]').each(function () {
                    const $card = $(this);
                    const key = $card.data('home-counter');
                    const value = values[key] ?? 0;

                    $card.find('[data-counter-value]').text(formatter.format(value)).removeClass('d-none');
                    $card.find('[data-counter-loader]').addClass('d-none');
                    $card.removeClass('border-danger').attr('aria-busy', 'false');
                });
            })
            .catch(() => {
                $counters.find('[data-home-counter]').each(function () {
                    const $card = $(this);

                    $card.find('[data-counter-value]').text('').addClass('d-none');
                    $card.find('[data-counter-loader]').addClass('d-none');
                    $card.addClass('border-danger').attr('aria-busy', 'false');
                });

                toast.error('Dashboard counters could not be loaded.');
            });
    });
</script>
