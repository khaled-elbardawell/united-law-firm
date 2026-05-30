@php
    $labels = $labels ?? ['pending' => 'قيد الانتظار', 'following' => 'جاري المتابعة', 'completed' => 'مكتملة'];
@endphp
<span class="badge {{ $status }}">{{ $labels[$status] ?? $status }}</span>
