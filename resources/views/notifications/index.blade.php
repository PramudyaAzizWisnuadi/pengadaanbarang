@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-bell me-2"></i>
                            Notifikasi Pengajuan
                            @if (auth()->user()->unreadNotificationsCount() > 0)
                                <span class="badge bg-danger ms-2">{{ auth()->user()->unreadNotificationsCount() }}</span>
                            @endif
                        </h4>
                        <div>
                            @if (auth()->user()->unreadNotificationsCount() > 0)
                                <button type="button" class="btn btn-sm btn-outline-primary" id="mark-all-read">
                                    <i class="bi bi-check-all me-1"></i>
                                    Tandai Semua Dibaca
                                </button>
                            @endif
                            <a href="{{ route('pengadaan.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($notifications->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    <div class="list-group-item {{ !$notification->is_read ? 'bg-light border-start border-primary border-3' : '' }}"
                                        data-notification-id="{{ $notification->id }}">
                                        <div class="d-flex w-100 justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <h6 class="mb-0 me-2">
                                                        @switch($notification->type)
                                                            @case('pengajuan_baru')
                                                                <i class="bi bi-plus-circle text-primary me-1"></i>
                                                            @break

                                                            @case('status_update')
                                                                @if ($notification->data && isset($notification->data['new_status']))
                                                                    @if ($notification->data['new_status'] === 'approved')
                                                                        <i class="bi bi-check-circle text-success me-1"></i>
                                                                    @elseif($notification->data['new_status'] === 'rejected')
                                                                        <i class="bi bi-x-circle text-danger me-1"></i>
                                                                    @else
                                                                        <i class="bi bi-arrow-repeat text-info me-1"></i>
                                                                    @endif
                                                                @else
                                                                    <i class="bi bi-arrow-repeat text-info me-1"></i>
                                                                @endif
                                                            @break

                                                            @default
                                                                <i class="bi bi-bell text-secondary me-1"></i>
                                                        @endswitch
                                                        {{ $notification->title }}
                                                    </h6>
                                                    @if (!$notification->is_read)
                                                        <span class="badge bg-primary">Baru</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 text-muted">{{ $notification->message }}</p>

                                                @if ($notification->data && isset($notification->data['approval_comment']) && $notification->data['approval_comment'])
                                                    <div class="alert alert-warning alert-sm py-2 mb-2">
                                                        <small>
                                                            <strong>Catatan:</strong>
                                                            {{ $notification->data['approval_comment'] }}
                                                        </small>
                                                    </div>
                                                @endif

                                                @if ($notification->pengadaan)
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>Kode Pengadaan:</strong>
                                                            <a href="{{ route('pengadaan.show', $notification->pengadaan) }}"
                                                                class="text-decoration-none">
                                                                {{ $notification->pengadaan->kode_pengadaan }}
                                                            </a>
                                                        </small>
                                                        @if ($notification->data && isset($notification->data['total_estimasi']))
                                                            <br><small class="text-muted">
                                                                <strong>Total Estimasi:</strong>
                                                                Rp
                                                                {{ number_format($notification->data['total_estimasi'], 0, ',', '.') }}
                                                            </small>
                                                        @endif
                                                        @if ($notification->data && isset($notification->data['new_status']))
                                                            <br><small class="text-muted">
                                                                <strong>Status:</strong>
                                                                <span
                                                                    class="badge {{ $notification->data['status_color'] ?? 'bg-secondary' }}">
                                                                    {{ ucfirst($notification->data['new_status']) }}
                                                                </span>
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                        @if ($notification->creator)
                                                            • oleh {{ $notification->creator->name }}
                                                        @endif
                                                    </small>

                                                    <div class="btn-group" role="group">
                                                        @if ($notification->pengadaan)
                                                            <a href="{{ route('pengadaan.show', $notification->pengadaan) }}"
                                                                class="btn btn-sm btn-outline-primary"
                                                                onclick="markAsRead({{ $notification->id }})">
                                                                <i class="bi bi-eye me-1"></i>
                                                                Lihat Detail
                                                            </a>
                                                        @endif

                                                        @if (!$notification->is_read)
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-success mark-read-btn"
                                                                data-id="{{ $notification->id }}">
                                                                <i class="bi bi-check me-1"></i>
                                                                Tandai Dibaca
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">Tidak ada notifikasi</h5>
                                <p class="text-muted">Anda akan menerima notifikasi ketika ada pengajuan baru atau perubahan
                                    status.</p>
                                <a href="{{ route('pengadaan.index') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Lihat Pengadaan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark single notification as read
            document.querySelectorAll('.mark-read-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.dataset.id;
                    markAsRead(notificationId);
                });
            });

            // Mark all notifications as read
            const markAllBtn = document.getElementById('mark-all-read');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function() {
                    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
                        fetch('{{ route('notifications.mark-all-as-read') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    location.reload();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat menandai notifikasi');
                            });
                    }
                });
            }
        });

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const notificationElement = document.querySelector(
                        `[data-notification-id="${notificationId}"]`);
                        if (notificationElement) {
                            notificationElement.classList.remove('bg-light', 'border-start', 'border-primary',
                                'border-3');
                            const badgeElement = notificationElement.querySelector('.badge.bg-primary');
                            if (badgeElement) {
                                badgeElement.remove();
                            }
                            const markReadBtn = notificationElement.querySelector('.mark-read-btn');
                            if (markReadBtn) {
                                markReadBtn.remove();
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
