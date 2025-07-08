<x-modal id="productModal" title="Create Equipment" size="lg">
    <x-form id="productForm">

        <x-modal.body class="space-y-3">
            <input type="hidden" name="id" id="id">

            <div class="row">
                <div class="col-lg-6">
                    <x-select id="category_id" name="category_id" label="Select Category"
                              placeholder="Select Category">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="col-lg-6">
                    <x-input name="name" id="name" label="Name" placeholder="Enter Name"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <x-input name="serial_number" id="serial_number" label="Serial Number" placeholder="Enter Serial Number" />
                </div>
                <div class="col-lg-6">
                    <x-input name="brand" id="brand" label="Brand" placeholder="Enter Brand" />
                </div>
            </div>
            <div class="row">
               <div class="col-lg-6">
                   <x-input name="model" id="model" label="Model" placeholder="Enter Model" />
               </div>
               <div class="col-lg-6">
                   <x-input name="purchase_date" id="purchase_date" label="Purchase Date" type="date" />
               </div>
           </div>
            <div class="row">
               <div class="col-lg-6">
                   <x-input name="warranty_expiry" id="warranty_expiry" label="Warranty Expiry" type="date" />
               </div>
               <div class="col-lg-6">
                   <x-input name="photo" id="photo" label="Upload Photo" type="file" accept="image/*" />
               </div>
           </div>
            <x-textarea id="description" name="description" label="Enter Description"
                        placeholder="Enter Description"/>

            <!-- Attribute wrapper -->
            <div id="attributeWrapper" class="mb-4">
                <label class="form-label fw-bold mb-2">Equipment Attributes</label>

                <div id="attributeRows">
                    <!-- Rows will be appended here via JS -->
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addAttributeBtn">
                    <i class="bi bi-plus-circle"></i> Add Attribute
                </button>
            </div>


        </x-modal.body>

        <x-modal.footer>
            <x-button color="secondary" data-bs-dismiss="modal">Cancel</x-button>
            <x-button color="dark" type="submit">Submit</x-button>
        </x-modal.footer>
    </x-form>

</x-modal>
