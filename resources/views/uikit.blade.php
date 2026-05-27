@extends('layouts.developer-docs')

@section('title', 'UI Kit')

@section('actions')
    <x-button color="light" link="{{ route('developer-docs') }}">
        <x-lucide-book-open class="w-4 h-4"/>
        <span>Docs</span>
    </x-button>
    <x-button color="light" data-bs-toggle="modal" data-bs-target="#uikitPreviewModal">
        <x-lucide-eye class="w-4 h-4"/>
        <span>Preview Modal</span>
    </x-button>
    <x-button color="dark" link="{{ route('home') }}">
        <x-lucide-house class="w-4 h-4"/>
        <span>Dashboard</span>
    </x-button>
@endsection

@section('content')
    <div class="d-grid gap-3">
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card label="Foundation" value="Bootstrap 5" icon="lucide-layers" icon-bg="bg-blue-100" icon-class="w-5 h-5 text-blue-700"/>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card label="Components" value="Blade-first" icon="lucide-component" icon-bg="bg-green-100" icon-class="w-5 h-5 text-green-700"/>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card label="Interactions" value="AJAX-ready" icon="lucide-zap" icon-bg="bg-yellow-100" icon-class="w-5 h-5 text-yellow-700"/>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <x-stat-card label="Icons" value="Lucide" icon="lucide-sparkles" icon-bg="bg-gray-100" icon-class="w-5 h-5 text-slate-700"/>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-7">
                <x-card title="Buttons" subtitle="Filled, soft, light, icon, dropdown, and destructive actions." class="h-100">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <x-button color="dark"><x-lucide-plus class="w-4 h-4"/><span>Create</span></x-button>
                        <x-button color="primary"><x-lucide-save class="w-4 h-4"/><span>Save</span></x-button>
                        <x-button color="success"><x-lucide-check class="w-4 h-4"/><span>Approve</span></x-button>
                        <x-button color="warning"><x-lucide-pause class="w-4 h-4"/><span>Hold</span></x-button>
                        <x-button color="danger"><x-lucide-trash-2 class="w-4 h-4"/><span>Delete</span></x-button>
                        <x-button color="light"><x-lucide-filter class="w-4 h-4"/><span>Filter</span></x-button>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <x-button color="dark" size="sm">Small</x-button>
                        <x-button color="dark">Default</x-button>
                        <x-button color="dark" size="lg">Large</x-button>

                        <x-dropdown align="end" color="light" icon="lucide-more-horizontal" text="Actions">
                            <x-dropdown.header>Record actions</x-dropdown.header>
                            <x-dropdown.item icon="lucide-pencil">Edit details</x-dropdown.item>
                            <x-dropdown.item icon="lucide-copy">Duplicate</x-dropdown.item>
                            <x-dropdown.divider/>
                            <x-dropdown.item icon="lucide-archive">Archive</x-dropdown.item>
                        </x-dropdown>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-xl-5">
                <x-card title="Badges & Avatars" subtitle="Compact labels and user identity primitives." class="h-100">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <x-badge color="primary">Primary</x-badge>
                        <x-badge color="success">Active</x-badge>
                        <x-badge color="warning">Pending</x-badge>
                        <x-badge color="danger">Blocked</x-badge>
                        <x-badge color="secondary">Archived</x-badge>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <x-avatar letters="AW" color="dark"/>
                        <x-avatar letters="BS" color="primary"/>
                        <x-avatar letters="JP" color="success" shape="semi"/>
                        <x-avatar letters="UX" color="warning" shape="square"/>
                        <x-avatar letters="AI" color="light"/>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-xl-7">
                <x-card title="Form Controls" subtitle="Inputs, selects, date pickers, toggles, files, and validation states.">
                    <x-form id="uikitForm">
                        <div class="row g-3" x-data="{ categoryId: null }">
                            <div class="col-12 col-md-6">
                                <x-input name="project_name" label="Project name" placeholder="Acme Operations"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-input name="admin_email" type="email" label="Admin email" placeholder="admin@example.com"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-select2 id="uikitStatus" name="status" label="Status" placeholder="Choose status" media>
                                    <option value="draft" data-icon="lucide-file-text" data-subtitle="Not visible to users">Draft</option>
                                    <option value="active" data-icon="lucide-circle-check" data-subtitle="Published and available">Active</option>
                                    <option value="paused" data-icon="lucide-circle-pause" data-subtitle="Temporarily unavailable">Paused</option>
                                </x-select2>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-select2 id="uikitOwner" name="owner" label="Owner" placeholder="Choose owner" media>
                                    <option value="aw" data-avatar="AW" data-subtitle="Product owner" selected>Abhishek Wani</option>
                                    <option value="jp" data-avatar="JP" data-subtitle="Backend engineer">Jordan Patel</option>
                                    <option value="ux" data-avatar="UX" data-subtitle="Design lead">Uma Xavier</option>
                                </x-select2>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-select2
                                    id="uikitCategory"
                                    name="category_id"
                                    label="API category"
                                    placeholder="Search categories"
                                    :api-url="route('categories.options')"
                                    x-model="categoryId"
                                />
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <x-button id="fillApiCategory" type="button" color="light" size="sm">
                                        <x-lucide-wand-sparkles class="w-4 h-4"/>
                                        <span>Fill API Select2</span>
                                    </x-button>
                                    <x-badge color="secondary">ID: <span x-text="categoryId || 'none'"></span></x-badge>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-datepicker id="uikitLaunchDate" name="launch_date" label="Launch date" placeholder="Pick a date"/>
                            </div>
                            <div class="col-12">
                                <x-textarea name="notes" label="Notes" placeholder="Add implementation notes..." rows="3"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <x-file-input id="brandAsset" name="brand_asset" label="Brand asset" preview previewPosition="right"/>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-grid gap-3 pt-md-4">
                                    <x-checkbox name="send_report" label="Send a weekly summary" checked/>
                                    <x-switch name="require_approval" label="Require manager approval" checked/>
                                </div>
                            </div>
                        </div>
                    </x-form>
                </x-card>
            </div>

            <div class="col-12 col-xl-5">
                <x-card title="Cards" subtitle="Use cards for repeated items, forms, and focused panels.">
                    <div class="d-grid gap-3">
                        <div class="border rounded p-3">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium mb-1">Resource module</p>
                                    <p class="text-muted text-sm mb-0">Controller, model, migration, table, form, and AJAX actions.</p>
                                </div>
                                <x-badge color="success" size="sm">Ready</x-badge>
                            </div>
                        </div>

                        <div class="border rounded p-3">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium mb-1">Settings area</p>
                                    <p class="text-muted text-sm mb-0">Profile, password, appearance, and account deletion views.</p>
                                </div>
                                <x-badge color="primary" size="sm">Core</x-badge>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <div class="col-12">
                <x-card title="Push Notification Tester" subtitle="Send a custom PWA push payload and preview the same notification on this device.">
                    <x-form id="uikitPushForm">
                        <div class="row g-3">
                            <div class="col-12 col-lg-4">
                                <x-input name="title" label="Title" value="{{ config('app.name') }}" placeholder="Notification title"/>
                            </div>
                            <div class="col-12 col-lg-8">
                                <x-input name="body" label="Body" value="This is a test push notification." placeholder="Short message shown in the notification"/>
                            </div>
                            <div class="col-12 col-lg-6">
                                <x-input name="url" label="Deep link" value="{{ route('home') }}" placeholder="/home or {{ route('categories.index') }}"/>
                            </div>
                            <div class="col-12 col-lg-6">
                                <x-input name="tag" label="Tag" value="uikit-push-test" placeholder="Used to replace duplicate notifications"/>
                            </div>
                            <div class="col-12 col-lg-6">
                                <x-input name="icon" label="Icon URL" value="{{ asset('pwa/icons/icon-192x192.png') }}" placeholder="/pwa/icons/icon-192x192.png"/>
                            </div>
                            <div class="col-12 col-lg-6">
                                <x-input name="badge" label="Badge URL" value="{{ asset('pwa/icons/icon-96x96.png') }}" placeholder="/pwa/icons/icon-96x96.png"/>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <x-button type="submit" color="dark">
                                        <x-lucide-send class="w-4 h-4"/>
                                        <span>Send Push Test</span>
                                    </x-button>
                                    <x-button type="button" color="light" data-push-fill-categories>
                                        <x-lucide-link class="w-4 h-4"/>
                                        <span>Use Categories Link</span>
                                    </x-button>
                                    <span class="text-muted text-sm" data-push-test-status>Requires notifications to be enabled for this browser.</span>
                                </div>
                            </div>
                        </div>
                    </x-form>
                </x-card>
            </div>

            <div class="col-12">
                <x-card title="Tables" subtitle="Dense, scannable table patterns with horizontal overflow support." body-class="px-0 pb-0">
                    <div class="table-responsive">
                        <x-table class="table-hover mb-0">
                            <thead>
                            <x-table.row>
                                <x-table.header class="ps-3">Module</x-table.header>
                                <x-table.header>Status</x-table.header>
                                <x-table.header>Owner</x-table.header>
                                <x-table.header>Last updated</x-table.header>
                                <x-table.header class="text-end pe-3">Actions</x-table.header>
                            </x-table.row>
                            </thead>
                            <tbody>
                            <x-table.row>
                                <x-table.cell class="ps-3 fw-medium">Categories</x-table.cell>
                                <x-table.cell><x-badge color="success" size="sm">Active</x-badge></x-table.cell>
                                <x-table.cell>Admin</x-table.cell>
                                <x-table.cell class="text-muted">Today</x-table.cell>
                                <x-table.cell class="text-end pe-3">
                                    <x-dropdown align="end" color="light" icon="lucide-ellipsis" buttonClass="btn-sm">
                                        <x-dropdown.item icon="lucide-pencil">Edit</x-dropdown.item>
                                        <x-dropdown.item icon="lucide-download">Export</x-dropdown.item>
                                    </x-dropdown>
                                </x-table.cell>
                            </x-table.row>
                            <x-table.row>
                                <x-table.cell class="ps-3 fw-medium">Profile Settings</x-table.cell>
                                <x-table.cell><x-badge color="primary" size="sm">Core</x-badge></x-table.cell>
                                <x-table.cell>System</x-table.cell>
                                <x-table.cell class="text-muted">Yesterday</x-table.cell>
                                <x-table.cell class="text-end pe-3">
                                    <x-button color="light" size="sm">Open</x-button>
                                </x-table.cell>
                            </x-table.row>
                            </tbody>
                        </x-table>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-xl-6">
                <x-card title="Tabs" subtitle="Static and AJAX-loaded panes with jpTabs.">
                    <ul class="nav nav-tabs" id="uikitTabs">
                        <li class="nav-item">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview-tab-pane" type="button">Overview</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button">Profile AJAX</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button">Contact AJAX</button>
                        </li>
                    </ul>

                    <div class="tab-content border border-top-0 rounded-bottom p-3">
                        <div class="tab-pane fade show active" id="overview-tab-pane">
                            <p class="mb-0 text-sm text-muted">Tabs can keep local content or fetch fragments from named routes.</p>
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane"></div>
                        <div class="tab-pane fade" id="contact-tab-pane"></div>
                    </div>
                </x-card>
            </div>

            <div class="col-12 col-xl-6">
                <x-card title="Rich Text" subtitle="Quill-powered editor wrapper for long-form fields.">
                    <x-richtext
                        id="uikitRichEditor"
                        name="content"
                        placeholder="Write starter kit documentation..."
                    ><p><strong>Write notes</strong>, upload images, and format content here.</p></x-richtext>
                </x-card>
            </div>
        </div>

        <x-modal id="uikitPreviewModal" title="Modal Preview">
            <x-modal.body>
                <p class="mb-0 text-muted">This modal uses the shared Blade modal component and Bootstrap behavior.</p>
            </x-modal.body>
            <x-modal.footer>
                <x-button color="light" data-bs-dismiss="modal">Cancel</x-button>
                <x-button color="dark" data-bs-dismiss="modal">Looks good</x-button>
            </x-modal.footer>
        </x-modal>
    </div>
@endsection

@push('js')
    <script type="module">
        onPageNavigated(() => {
            let uikitForm = useForm('#uikitForm');

            $('#fillApiCategory').off('click.uikitSelect2').on('click.uikitSelect2', function () {
                axios.get(route('categories.options')).then((response) => {
                    const category = response.data.results?.[0];

                    if (category) {
                        uikitForm.fill({
                            category_id: category.id
                        });
                    }
                });
            });

            $('#uikitTabs').jpTabs({
                ajax: {
                    'profile-tab': route('tabs.profile'),
                    'contact-tab': route('tabs.contact')
                }
            });

            $('[data-push-fill-categories]').off('click.uikitPush').on('click.uikitPush', function () {
                $('#uikitPushForm [name="url"]').val(route('categories.index'));
            });

            $('#uikitPushForm').off('submit.uikitPush').on('submit.uikitPush', async function (event) {
                event.preventDefault();

                const $form = $(this);
                const submitButton = event.originalEvent?.submitter;
                const payload = Object.fromEntries(
                    $form.serializeArray()
                        .filter((field) => field.value !== '')
                        .map((field) => [field.name, field.value])
                );

                $form.find('[type="submit"]').prop('disabled', true);
                $('[data-push-test-status]').text('Sending test notification...');

                try {
                    const response = await window.pwaPush.sendTest(payload);
                    await window.pwaPush.showNotification(response.notification || payload);

                    $('[data-push-test-status]').text(`Server accepted ${response.sent} subscription(s).`);
                    toast.success(response.message);
                } catch (error) {
                    const message = error.response?.data?.message || error.message || 'Unable to send notification.';

                    $('[data-push-test-status]').text(message);
                    toast.error(message);
                } finally {
                    $form.find('[type="submit"]').prop('disabled', false);

                    if (submitButton?.id) {
                        window.dispatchEvent(new CustomEvent('button-loading', {
                            detail: {
                                id: submitButton.id,
                                state: false
                            }
                        }));
                    }
                }
            });
        });
    </script>
@endpush
