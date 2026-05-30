<x-admin.layouts.app heading="الرئيسية" description="مركز متابعة سريع للمحتوى والطلبات التي تحتاج إجراء.">
    <section class="dashboard-hero admin-card">
        <div>
            <span class="dashboard-kicker">ملخص اليوم</span>
            <h2>لديك {{ $stats['pendingContacts'] + $stats['pendingConsultations'] }} طلب بانتظار المتابعة</h2>
            <p>ابدأ من الطلبات الجديدة، أو راجع العناصر المحذوفة من السلة قبل حذفها نهائياً.</p>
        </div>
        <div class="actions-row">
            <a class="btn-admin btn-gold" href="{{ route('admin.consultations.index', ['status' => 'pending']) }}">متابعة الاستشارات</a>
            <a class="btn-admin" href="{{ route('admin.contact-requests.index', ['status' => 'pending']) }}">طلبات التواصل</a>
        </div>
    </section>

    <section class="stats-grid">
        <a class="stat-card" href="{{ route('admin.services.index') }}"><span>الخدمات</span><strong>{{ $stats['services'] }}</strong></a>
        <a class="stat-card" href="{{ route('admin.lawyers.index') }}"><span>المحامون</span><strong>{{ $stats['lawyers'] }}</strong></a>
        <a class="stat-card" href="{{ route('admin.faqs.index') }}"><span>الأسئلة الشائعة</span><strong>{{ $stats['faqs'] }}</strong></a>
        <a class="stat-card stat-card--urgent" href="{{ route('admin.contact-requests.index', ['status' => 'pending']) }}"><span>تواصل قيد الانتظار</span><strong>{{ $stats['pendingContacts'] }}</strong></a>
        <a class="stat-card stat-card--urgent" href="{{ route('admin.consultations.index', ['status' => 'pending']) }}"><span>استشارات قيد الانتظار</span><strong>{{ $stats['pendingConsultations'] }}</strong></a>
        <a class="stat-card" href="{{ route('admin.users.index') }}"><span>حسابات الإدارة</span><strong>{{ $stats['users'] }}</strong></a>
    </section>

    <section class="dashboard-panels">
        <div class="admin-card">
            <h2>حالة طلبات التواصل</h2>
            <div class="status-metrics">
                @foreach (\App\Models\ContactRequest::STATUSES as $key => $label)
                    <a href="{{ route('admin.contact-requests.index', ['status' => $key]) }}">
                        <span>{{ $label }}</span>
                        <strong>{{ $contactStatus[$key] ?? 0 }}</strong>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="admin-card">
            <h2>حالة الاستشارات</h2>
            <div class="status-metrics">
                @foreach (\App\Models\Consultation::STATUSES as $key => $label)
                    <a href="{{ route('admin.consultations.index', ['status' => $key]) }}">
                        <span>{{ $label }}</span>
                        <strong>{{ $consultationStatus[$key] ?? 0 }}</strong>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="admin-card">
            <h2>السلة</h2>
            <div class="trash-summary">
                <strong>{{ $stats['trash'] }}</strong>
                <span>عنصر محذوف يمكن استرجاعه</span>
            </div>
            <div class="actions-row">
                <a class="btn-admin" href="{{ route('admin.services.index', ['view' => 'trash']) }}">سلة الخدمات</a>
                <a class="btn-admin" href="{{ route('admin.consultations.index', ['view' => 'trash']) }}">سلة الطلبات</a>
            </div>
        </div>
    </section>

    <section class="grid-2">
        <div class="admin-card dashboard-list-card">
            <h2>آخر طلبات التواصل</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>الاسم</th><th>الهاتف</th><th>الحالة</th></tr></thead>
                    <tbody>
                    @forelse ($latestContacts as $item)
                        <tr>
                            <td><a href="{{ route('admin.contact-requests.show', $item) }}">{{ $item->name }}</a></td>
                            <td dir="ltr">{{ $item->phone }}</td>
                            <td><x-admin.partials.status :status="$item->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">لا توجد طلبات بعد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-card dashboard-list-card">
            <h2>آخر الاستشارات</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>الاسم</th><th>الخدمة</th><th>الحالة</th></tr></thead>
                    <tbody>
                    @forelse ($latestConsultations as $item)
                        <tr>
                            <td><a href="{{ route('admin.consultations.show', $item) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->service }}</td>
                            <td><x-admin.partials.status :status="$item->status" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">لا توجد استشارات بعد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-admin.layouts.app>
