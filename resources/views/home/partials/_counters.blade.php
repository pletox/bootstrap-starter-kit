<div class="row g-3 mb-3" data-home-counters data-url="{{ route('home.counters') }}">
    <div class="col-12 col-md-4">
        <x-card class="h-100" data-home-counter="total" aria-busy="true">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-sm mb-1">Categories</p>
                    <h3 class="mb-0 d-none" data-counter-value></h3>
                    <div class="line-loader mt-3" data-counter-loader aria-hidden="true"></div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded bg-gray-100 w-10 h-10">
                    <x-lucide-database class="w-5 h-5 text-slate-600"/>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-md-4">
        <x-card class="h-100" data-home-counter="active" aria-busy="true">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-sm mb-1">Active Records</p>
                    <h3 class="mb-0 d-none" data-counter-value></h3>
                    <div class="line-loader mt-3" data-counter-loader aria-hidden="true"></div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded bg-green-100 w-10 h-10">
                    <x-lucide-circle-check class="w-5 h-5 text-green-700"/>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-md-4">
        <x-card class="h-100" data-home-counter="inactive" aria-busy="true">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-sm mb-1">Inactive Records</p>
                    <h3 class="mb-0 d-none" data-counter-value></h3>
                    <div class="line-loader mt-3" data-counter-loader aria-hidden="true"></div>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded bg-yellow-100 w-10 h-10">
                    <x-lucide-circle-pause class="w-5 h-5 text-yellow-700"/>
                </div>
            </div>
        </x-card>
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
