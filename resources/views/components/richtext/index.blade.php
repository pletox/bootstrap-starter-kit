@props([
    'id' => 'quill-' . Str::random(6),
    'name' => 'content',
    'mention' => false,
])

<div class="w-100">
    <input type="hidden" id="{{ $id }}-input" name="{{ $name }}">
    <div id="{{ $id }}" class="border rounded"></div>

    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<script>
    function initQuill(id, mention) {

        let options = {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['image', 'link']
                ],
            }
        };

        if (mention) {
            options.modules.mention = {
                mentionDenotationChars: ["@"],
                allowedChars: /^[A-Za-z\s]*$/,
                source: function (searchTerm, renderList) {
                    fetch('{{ route('utilities.richtext.mention') }}?search=' + searchTerm)
                        .then(res => res.json())
                        .then(data => renderList(data))
                        .catch(err => console.error(err));
                }
            };
        }



        let editor = new Quill('#' + id, options);

        // File Upload (Optional)

        editor.getModule('toolbar').addHandler('image', function () {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                let file = input.files[0];
                let formData = new FormData();
                formData.append('file', file);

                let response = await fetch('{{ route('utilities.richtext.upload') }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });

                let data = await response.json();
                if (data.location) {
                    let range = editor.getSelection();
                    editor.insertEmbed(range.index, 'image', data.location);
                }
            };
        });

        // Update hidden input on content change
        editor.on('text-change', () => {
            document.querySelector('#' + id + '-input').value = editor.root.innerHTML;
        });
    }


    document.addEventListener('livewire:navigated', function () {
        initQuill(@json($id), @json($mention));
    });
</script>
