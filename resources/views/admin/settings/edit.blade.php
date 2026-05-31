<x-admin.layouts.app
    title="إعدادات الموقع"
    heading="إعدادات الموقع"
    description="تحكم ببيانات الهوية، التواصل، السوشيال ميديا، الأرقام الثابتة، والنصوص التي تظهر في الواجهة."
>
    <x-slot name="actions">
        <a class="btn-admin" href="{{ route('home') }}" target="_blank">عرض الموقع</a>
    </x-slot>

    @php
        $groupLabels = [
            'general' => 'هوية الموقع',
            'contact' => 'بيانات التواصل والموقع',
            'social' => 'روابط السوشيال ميديا',
            'home' => 'نصوص الصفحة الرئيسية',
            'about' => 'نصوص صفحة عن المكتب',
            'stats' => 'أرقام الإحصائيات',
            'seo' => 'إعدادات SEO الافتراضية',
        ];
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="settings-stack">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $items)
            <section class="admin-card settings-section">
                <div class="settings-section-head">
                    <div>
                        <span>إعدادات</span>
                        <h2>{{ $groupLabels[$group] ?? $group }}</h2>
                    </div>
                </div>

                <div class="form-grid">
                    @foreach ($items as $setting)
                        @php
                            $key = $setting['key'];
                            $type = $setting['type'];
                            $value = old($key, $setting['value']);
                        @endphp

                        <div class="field {{ $type === 'textarea' || $key === 'site_logo' ? 'full' : '' }}">
                            <label for="{{ $key === 'site_logo' ? 'site_logo_file' : $key }}">{{ $setting['label'] }}</label>

                            @if ($key === 'site_logo')
                                <div class="settings-logo-row">
                                    @if ($value)
                                        <img src="{{ str_starts_with($value, 'http') ? $value : asset($value) }}" alt="الشعار الحالي">
                                    @endif
                                    <div>
                                        <input id="site_logo_file" name="site_logo_file" type="file" accept="image/*">
                                        <small>ارفع صورة الشعار فقط. إذا تركت الحقل فارغا سيبقى الشعار الحالي كما هو.</small>
                                    </div>
                                </div>
                                @error('site_logo_file')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            @elseif ($type === 'textarea')
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="4">{{ $value }}</textarea>
                            @else
                                <input
                                    id="{{ $key }}"
                                    name="{{ $key }}"
                                    type="{{ in_array($type, ['email', 'url'], true) ? $type : 'text' }}"
                                    value="{{ $value }}"
                                    @if ($type === 'url') dir="ltr" @endif
                                >
                            @endif

                            @if ($key !== 'site_logo')
                                @error($key)
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="settings-actions">
            <button class="btn-admin btn-gold" type="submit">حفظ الإعدادات</button>
            <a class="btn-admin btn-muted" href="{{ route('admin.dashboard') }}">العودة للرئيسية</a>
        </div>
    </form>
</x-admin.layouts.app>
