<x-admin.layouts.app :heading="$post->exists ? 'تعديل مقال' : 'إضافة مقال'" description="اكتب المحتوى عبر TinyMCE وتحكم بالسيو والتصنيفات والوسوم.">
    <section class="admin-card">
        <form method="POST" action="{{ $post->exists ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($post->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="field"><label>عنوان المقال</label><input name="title" value="{{ old('title', $post->title) }}" required></div>
                <div class="field">
                    <label>عنوان الرابط (Slug)</label>
                    <input name="slug" dir="ltr" value="{{ old('slug', $post->slug) }}" placeholder="contract-disputes">
                    <small class="admin-muted-text">يؤثر على رابط المقال في الموقع. استخدم أحرفاً إنجليزية صغيرة وأرقاماً وشرطة فقط. إذا تركته فارغاً سيتم توليده تلقائياً من عنوان المقال.</small>
                </div>
                <div class="field"><label>التصنيف</label><select name="blog_category_id"><option value="">بدون تصنيف</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected((string) old('blog_category_id', $post->blog_category_id) === (string) $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="field"><label>الحالة</label><select name="status">@foreach (\App\Models\BlogPost::STATUSES as $key => $label)<option value="{{ $key }}" @selected(old('status', $post->status ?: 'draft') === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="field"><label>تاريخ النشر</label><input name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"></div>
                <div class="field">
                    <label>الصورة الرئيسية</label>
                    <input name="featured_image_file" type="file" accept="image/*">
                    @if ($post->featured_image)
                        <div class="settings-logo-row">
                            <img src="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : asset($post->featured_image) }}" alt="{{ $post->title }}">
                            <small>اترك الحقل فارغا للاحتفاظ بالصورة الحالية.</small>
                        </div>
                    @else
                        <small class="admin-muted-text">اختياري. إذا لم ترفع صورة سيظهر تصميم افتراضي.</small>
                    @endif
                </div>
                <div class="field full"><label>ملخص قصير</label><textarea name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea></div>
                <div class="field full"><label>الوسوم</label><select name="tag_ids[]" multiple size="6">@foreach ($tags as $tag)<option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tag_ids', $post->tags?->pluck('id')->all() ?? [])))>{{ $tag->name }}</option>@endforeach</select><small class="admin-muted-text">يمكن اختيار أكثر من وسم بالضغط على Ctrl / Cmd.</small></div>
                <div class="field full"><label>المحتوى</label><textarea class="tinymce-editor" name="content" >{{ old('content', $post->content) }}</textarea></div>
            </div>

            <section class="admin-card settings-section" style="margin: 18px 0;">
                <div class="settings-section-head"><div><span>SEO</span><h2>إعدادات السيو للمقال</h2></div></div>
                <div class="form-grid">
                    <div class="field"><label>Meta Title</label><input name="meta_title" value="{{ old('meta_title', $post->meta_title) }}"></div>
                    <div class="field"><label>OG Image URL</label><input name="og_image" dir="ltr" value="{{ old('og_image', $post->og_image) }}"></div>
                    <div class="field full"><label>Meta Description</label><textarea name="meta_description" rows="3">{{ old('meta_description', $post->meta_description) }}</textarea></div>
                    <div class="field full"><label>Meta Keywords</label><textarea name="meta_keywords" rows="2">{{ old('meta_keywords', $post->meta_keywords) }}</textarea></div>
                    <label class="check-field full"><input type="checkbox" name="is_indexable" value="1" @checked(old('is_indexable', $post->is_indexable ?? true))> السماح للأرشفة index,follow</label>
                </div>
            </section>

            <div class="actions-row">
                <button class="btn-admin btn-gold" type="submit">حفظ المقال</button>
                <a class="btn-admin btn-muted" href="{{ route('admin.blog-posts.index') }}">رجوع</a>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            directionality: 'rtl',
            language: 'ar',
            height: 520,
            menubar: true,
            plugins: 'advlist autolink lists link image media table code fullscreen preview directionality searchreplace wordcount',
            toolbar: 'undo redo | blocks | bold italic underline | alignright aligncenter alignleft alignjustify | bullist numlist | link image media table | ltr rtl | preview code fullscreen',
            images_upload_handler: (blobInfo) => new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.readAsDataURL(blobInfo.blob());
            }),
            automatic_uploads: true,
            image_title: true,
            file_picker_types: 'image',
            content_style: 'body{font-family:Tajawal,Tahoma,Arial,sans-serif;line-height:1.9;font-size:16px;} img{max-width:100%;height:auto;}'
        });
    </script>
</x-admin.layouts.app>
