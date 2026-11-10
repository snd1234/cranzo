@php
    $product = $product ?? null;
    $selectedCategory = old('category_id', $product->category_id ?? '');
    $selectedSubcategory = old('sub_category_id', $product->sub_category_id ?? '');
    $selectedTernary = old('ternary_category_id', $product->ternary_category_id ?? '');
@endphp
<div class="row g-3">
    <div class="col-lg-8">
        <div class="mb-2">
            <label class="form-label">Product title <span class="text-danger">*</span></label>
            <input type="text" name="product_title" id="product_title" class="form-control @error('product_title') is-invalid @enderror" value="{{ old('product_title', $product->product_title ?? '') }}" required>
        </div>
        <div class="mb-2">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug ?? '') }}">
            <div class="form-text">Leave blank to generate it from the title. The public URL is /product-detail/{slug}.</div>
        </div>
        <div class="mb-2">
            <label class="form-label">SKU <span class="text-danger">*</span></label>
            <input type="text" name="sku_id" class="form-control @error('sku_id') is-invalid @enderror" value="{{ old('sku_id', $product->sku_id ?? '') }}" required>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) $selectedCategory === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Subcategory <span class="text-danger">*</span></label>
                <select name="sub_category_id" id="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror" required>
                    <option value="">Select subcategory</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Ternary category</label>
                <select name="ternary_category_id" id="ternary_category_id" class="form-select @error('ternary_category_id') is-invalid @enderror">
                    <option value="">None</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Regular price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="actual_price" class="form-control @error('actual_price') is-invalid @enderror" value="{{ old('actual_price', $product->actual_price ?? '') }}" required>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Selling price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="discounted_price" class="form-control @error('discounted_price') is-invalid @enderror" value="{{ old('discounted_price', $product->discounted_price ?? '') }}" required>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Discount</label>
                <input type="text" class="form-control" value="{{ $product ? $product->discount_rate.'%' : 'Calculated on save' }}" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-2"><label class="form-label">Length</label><input name="length" class="form-control" value="{{ old('length', $product->length ?? '') }}"></div>
            <div class="col-md-3 mb-2"><label class="form-label">Width</label><input name="width" class="form-control" value="{{ old('width', $product->width ?? '') }}"></div>
            <div class="col-md-3 mb-2"><label class="form-label">Height</label><input name="height" class="form-control" value="{{ old('height', $product->height ?? '') }}"></div>
            <div class="col-md-3 mb-2"><label class="form-label">Unit</label><input name="m_unit" class="form-control" value="{{ old('m_unit', $product->m_unit ?? '') }}"></div>
            <div class="col-md-3 mb-2"><label class="form-label">GST % (optional)</label><input name="gst_rate" class="form-control" value="{{ old('gst_rate', $product->gst_rate ?? '') }}" placeholder="Leave blank if unset"></div>
        </div>
        <div class="mb-2">
            <label class="form-label">Highlights</label>
            <textarea name="product_highlights" id="product_highlights" class="form-control" rows="4">{{ old('product_highlights', $product->product_highlights ?? '') }}</textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="8">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        <h6 class="mt-3">SEO</h6>
        <div class="mb-2"><label class="form-label">Meta title</label><input name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title ?? '') }}" maxlength="255"></div>
        <div class="mb-2"><label class="form-label">Meta keywords</label><input name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}" maxlength="255"></div>
        <div class="mb-2"><label class="form-label">Meta description</label><textarea name="meta_description" class="form-control" rows="3" maxlength="500">{{ old('meta_description', $product->meta_description ?? '') }}</textarea></div>
    </div>
    <div class="col-lg-4">
        <div class="mb-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" @selected(old('status', $product->status ?? 1) == 1)>Active</option>
                <option value="0" @selected(old('status', $product->status ?? 1) == 0)>Inactive</option>
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label">Featured</label>
            <select name="featured_item" class="form-select">
                <option value="0" @selected(old('featured_item', $product->featured_item ?? 0) == 0)>No</option>
                <option value="1" @selected(old('featured_item', $product->featured_item ?? 0) == 1)>Yes</option>
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label">Cash on delivery</label>
            <select name="cod_available" class="form-select">
                <option value="1" @selected(old('cod_available', $product->cod_available ?? 1) == 1)>Available</option>
                <option value="0" @selected(old('cod_available', $product->cod_available ?? 1) == 0)>Not marked</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ $product ? 'Add a color and stock' : 'Color and stock' }}</label>
            <select name="color_id" class="form-select @error('color_id') is-invalid @enderror">
                <option value="">{{ $product ? 'Keep current stock rows' : 'Select color' }}</option>
                @foreach($colors as $color)
                    <option value="{{ $color->color_id }}" @selected(old('color_id') == $color->color_id)>{{ $color->color_title }}</option>
                @endforeach
            </select>
            <input type="number" min="0" name="quantity" class="form-control mt-2 @error('quantity') is-invalid @enderror" placeholder="Quantity" value="{{ old('quantity') }}">
        </div>
        @if($details->isNotEmpty())
            <div class="mb-3">
                <label class="form-label">Current stock</label>
                @foreach($details as $detail)
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text">{{ $detail->color_title ?: 'Color '.$detail->color_id }}</span>
                        <input type="number" min="0" class="form-control" name="quantities[{{ $detail->product_details_id }}]" value="{{ old('quantities.'.$detail->product_details_id, $detail->product_quantity) }}">
                    </div>
                @endforeach
            </div>
        @endif
        <div class="mb-2">
            <label class="form-label">Images</label>
            <input type="file" name="images[]" id="productImages" class="form-control @error('images.*') is-invalid @enderror" accept="image/jpeg,image/png,image/webp" multiple>
            <div class="form-text">JPG, PNG, or WEBP. Up to 6 files, 2 MB each.</div>
            <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>
        @if($images->isNotEmpty())
            <div class="mb-2">
                @foreach($images as $image)
                    <label class="d-flex align-items-center gap-2 mb-2">
                        <input type="checkbox" name="remove_images[]" value="{{ $image->image_id }}">
                        <img src="{{ asset('uploads/products/'.$image->image) }}" alt="" style="width:48px;height:48px;object-fit:cover;" class="border rounded">
                        <span class="small">Remove</span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const subcategories = @json($subcategories);
    const ternaries = @json($ternaryCategories);
    const category = document.getElementById('category_id');
    const subcategory = document.getElementById('sub_category_id');
    const ternary = document.getElementById('ternary_category_id');
    const selectedSub = @json((string) $selectedSubcategory);
    const selectedTernary = @json((string) $selectedTernary);

    function fillSubcategories(preferred) {
        subcategory.innerHTML = '<option value="">Select subcategory</option>';
        subcategories.filter(row => String(row.category_id) === category.value).forEach(row => {
            subcategory.add(new Option(row.name, row.id, false, String(row.id) === String(preferred)));
        });
        fillTernaries();
    }
    function fillTernaries() {
        const current = ternary.value || selectedTernary;
        ternary.innerHTML = '<option value="">None</option>';
        ternaries.filter(row => String(row.sub_category_id) === subcategory.value).forEach(row => {
            ternary.add(new Option(row.ternary_title, row.ternary_id, false, String(row.ternary_id) === String(current)));
        });
    }
    category.addEventListener('change', function () {
        fillSubcategories('');
    });
    subcategory.addEventListener('change', fillTernaries);
    fillSubcategories(selectedSub);

    const title = document.getElementById('product_title');
    const slug = document.getElementById('slug');
    title.addEventListener('input', function () {
        if (!slug.dataset.touched) {
            slug.value = this.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }
    });
    slug.addEventListener('input', function () { this.dataset.touched = '1'; });

    ['#description', '#product_highlights'].forEach(selector => {
        const element = document.querySelector(selector);
        if (element && window.ClassicEditor) {
            ClassicEditor.create(element).catch(() => {});
        }
    });

    const input = document.getElementById('productImages');
    const preview = document.getElementById('imagePreview');
    input.addEventListener('change', function () {
        preview.innerHTML = '';
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const wrap = document.createElement('div');
                wrap.innerHTML = '<img alt="" style="width:64px;height:64px;object-fit:cover" class="border rounded">';
                wrap.querySelector('img').src = event.target.result;
                preview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });

    const form = document.getElementById('product-form');
    form.addEventListener('submit', function () {
        const button = form.querySelector('[type="submit"]');
        button.disabled = true;
        button.textContent = 'Saving...';
    });
});
</script>
