@if (session('success'))
    <div class="site-alert site-alert--success">
        <strong>تم بنجاح</strong>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="site-alert site-alert--error">
        <strong>يرجى مراجعة البيانات</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
