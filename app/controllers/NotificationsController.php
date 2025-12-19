<?php

class NotificationsController extends Controller
{
    public function index(): void
    {
        Middleware::requireEmployeeOrAdmin();
        $userId = Auth::user()['id'];
        $role = Auth::user()['role'];
        if ($role === 'ADMIN' && view_as_employee()) {
            $role = 'EMPLOYEE';
        }
        $notifications = Notification::listForUser($userId, $role);
        $this->render('notifications/index', [
            'notifications' => $notifications,
        ]);
    }

    public function read(string $id): void
    {
        Middleware::requireEmployeeOrAdmin();
        $this->requireCsrf();
        Notification::markRead((int)$id, Auth::user()['id']);
        redirect('/notifications');
    }
}
