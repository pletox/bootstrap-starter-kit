@props([
    'id' => 'quill-' . Str::random(6),
    'name' => 'content',
    'upload' => true, // Enable file uploads
    'mention' => false, // Enable mentions
])

<div class="w-100">
    <input type="hidden" id="{{ $id }}-input" name="{{ $name }}">
    <div id="{{ $id }}" class="border rounded"></div>

    {{-- ✅ Bootstrap error handling --}}
    @error($name)
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>


<script>
    document.addEventListener('livewire:navigated', function () {
        let quill = new window.Quill('#{{ $id }}', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'align': []}],
                    ['image', 'link']
                ],
                @if($mention)
                mention: {
                    allowedChars: /^[A-Za-z\s]*$/,
                    mentionDenotationChars: ["@"],
                    source: function (searchTerm, renderList) {
                        fetch('{{ route('utilities.richtext.mention') }}?search=' + searchTerm)
                            .then(res => res.json())
                            .then(data => renderList(data))
                            .catch(err => console.error(err));
                    }
                }
                @endif

            }
        });

        // Save content to hidden input field
        quill.on('text-change', function () {
            document.querySelector('#{{ $id }}-input').value = quill.root.innerHTML;
        });

        // File Upload (Optional)
        @if($upload)
        quill.getModule('toolbar').addHandler('image', function () {
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
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                });

                let data = await response.json();
                if (data.location) {
                    let range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', data.location);
                }
            };
        });
        @endif
    });
</script>
