<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\PengadaanBarang;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Send notification to department admins when a new pengadaan is submitted.
     */
    public function notifyDepartmentAdminsOnNewPengadaan(PengadaanBarang $pengadaan)
    {
        // Get admin users from the same department
        $departmentAdmins = User::where('departemen_id', $pengadaan->departemen_id)
            ->whereIn('role', ['admin', 'super_admin'])
            ->where('id', '!=', $pengadaan->user_id) // Don't notify the creator
            ->get();

        foreach ($departmentAdmins as $admin) {
            $this->createNotification([
                'user_id' => $admin->id,
                'pengadaan_id' => $pengadaan->id,
                'created_by' => $pengadaan->user_id,
                'type' => 'pengajuan_baru',
                'title' => 'Pengajuan Baru Memerlukan Approval',
                'message' => "Pengajuan pengadaan '{$pengadaan->keterangan}' dari {$pengadaan->user->name} telah disubmit dan memerlukan approval Anda.",
                'data' => [
                    'pengadaan_kode' => $pengadaan->kode_pengadaan,
                    'pengadaan_status' => $pengadaan->status,
                    'total_estimasi' => $pengadaan->total_estimasi,
                    'departemen' => $pengadaan->departemen,
                    'pemohon' => $pengadaan->user->name,
                    'tanggal_submit' => $pengadaan->updated_at->format('d/m/Y H:i')
                ]
            ]);
        }
    }

    /**
     * Send notification when pengadaan status is updated (for feedback to requester).
     */
    public function notifyRequesterOnStatusUpdate(PengadaanBarang $pengadaan, $oldStatus, $newStatus)
    {
        // Only notify for approved/rejected status
        if (!in_array($newStatus, ['approved', 'rejected'])) {
            return;
        }

        // Don't notify if the requester is the one who approved/rejected
        if ($pengadaan->user_id == Auth::id()) {
            return;
        }

        $statusText = $newStatus === 'approved' ? 'Disetujui' : 'Ditolak';
        $statusColor = $newStatus === 'approved' ? 'success' : 'danger';

        $title = "Pengajuan Anda {$statusText}";
        $message = "Pengajuan pengadaan '{$pengadaan->keterangan}' Anda telah {$statusText} oleh " . (Auth::user()->name ?? 'Admin');

        if ($newStatus === 'rejected' && $pengadaan->catatan_approval) {
            $message .= ". Catatan: " . $pengadaan->catatan_approval;
        }

        $this->createNotification([
            'user_id' => $pengadaan->user_id,
            'pengadaan_id' => $pengadaan->id,
            'created_by' => Auth::id(),
            'type' => 'status_feedback',
            'title' => $title,
            'message' => $message,
            'data' => [
                'pengadaan_kode' => $pengadaan->kode_pengadaan,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'status_color' => $statusColor,
                'approved_by' => Auth::user()->name ?? 'Admin',
                'tanggal_approval' => now()->format('d/m/Y H:i'),
                'catatan_approval' => $pengadaan->catatan_approval
            ]
        ]);
    }

    /**
     * Create a notification.
     */
    private function createNotification(array $data)
    {
        return Notification::create($data);
    }

    /**
     * Get notifications for a user with pagination.
     */
    public function getUserNotifications($userId, $perPage = 10)
    {
        return Notification::forUser($userId)
            ->with(['pengadaan', 'creator'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification && !$notification->is_read) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Get unread notifications count for a user.
     */
    public function getUnreadCount($userId)
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Delete old notifications (older than 30 days).
     */
    public function cleanupOldNotifications()
    {
        return Notification::where('created_at', '<', now()->subDays(30))->delete();
    }
}
