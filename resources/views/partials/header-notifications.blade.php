@php
    $notificationData = $headerNotifications ?? ['count' => 0, 'items' => []];
    $notificationItems = collect($notificationData['items'] ?? []);
    $notificationCount = (int) ($notificationData['count'] ?? 0);
    $notificationDropdownId = $notificationDropdownId ?? 'headerNotifications';
@endphp

<div class="notification-dropdown" id="{{ $notificationDropdownId }}" data-read-url="{{ route('notifications.read') }}">
    <button type="button" class="notification-btn" onclick="toggleHeaderNotifications('{{ $notificationDropdownId }}')" title="Notifikasi" aria-label="Notifikasi">
        <i class="fas fa-bell"></i>
        @if($notificationCount > 0)
            <span class="notification-badge">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
        @endif
    </button>

    <div class="notification-menu">
        <div class="notification-menu-header">
            <div>
                <strong>Notifikasi</strong>
                <span>{{ $notificationCount > 0 ? $notificationCount . ' update baru' : 'Semua sudah dibaca' }}</span>
            </div>
            <i class="fas fa-bell"></i>
        </div>

        <div class="notification-list">
            @forelse($notificationItems as $item)
                <a href="{{ $item['url'] ?? '#' }}" class="notification-item">
                    <span class="notification-icon {{ $item['tone'] ?? 'primary' }}">
                        <i class="fas {{ $item['icon'] ?? 'fa-bell' }}"></i>
                    </span>
                    <span class="notification-copy">
                        <strong>{{ $item['title'] ?? 'Update baru' }}</strong>
                        <small>{{ $item['description'] ?? 'Ada aktivitas terbaru.' }}</small>
                        <em>{{ $item['time'] ?? '' }}</em>
                    </span>
                </a>
            @empty
                <div class="notification-empty">
                    <i class="fas fa-check-circle"></i>
                    <strong>Belum ada notifikasi</strong>
                    <span>Update pesanan, stok, dan aktivitas akan muncul di sini.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>
