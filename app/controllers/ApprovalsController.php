<?php

class ApprovalsController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();
        $approvals = Approval::pending();
        $this->render('approvals/index', [
            'approvals' => $approvals,
        ]);
    }

    public function show(string $id): void
    {
        Middleware::requireAdmin();
        $approval = Approval::find((int)$id);
        if (!$approval) {
            redirect('/approvals');
        }
        $this->render('approvals/show', [
            'approval' => $approval,
            'before' => json_decode($approval['before_json'], true),
            'after' => json_decode($approval['after_json'], true),
        ]);
    }

    public function approve(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        $approval = Approval::find((int)$id);
        if (!$approval || $approval['status'] !== 'PENDING') {
            redirect('/approvals');
        }

        $this->applyApproval($approval);
        Approval::setStatus((int)$id, 'APPROVED', Auth::user()['id']);

        $_SESSION['flash_success'] = 'Solicitacao aprovada.';
        redirect('/approvals');
    }

    public function reject(string $id): void
    {
        Middleware::requireAdmin();
        $this->requireCsrf();
        $approval = Approval::find((int)$id);
        if (!$approval || $approval['status'] !== 'PENDING') {
            redirect('/approvals');
        }
        Approval::setStatus((int)$id, 'REJECTED', Auth::user()['id']);
        $_SESSION['flash_success'] = 'Solicitacao rejeitada.';
        redirect('/approvals');
    }

    private function applyApproval(array $approval): void
    {
        $entityType = $approval['entity_type'];
        $entityId = (int)$approval['entity_id'];
        $after = json_decode($approval['after_json'], true) ?: [];

        if ($entityType === 'lead') {
            $services = $after['services'] ?? [];
            Lead::update($entityId, $after, $services);

            $existingContract = RecurringContract::findByLeadId($entityId);
            if ($after['payment_type'] === 'RECURRING') {
                if ($existingContract) {
                    RecurringContract::updateFromLead($entityId, $after);
                } else {
                    RecurringContract::createFromLead($entityId, $after);
                }
            } else {
                if ($existingContract) {
                    RecurringContract::updateStatus((int)$existingContract['id'], 'INACTIVE');
                }
            }
        }

        if ($entityType === 'investment') {
            Investment::update($entityId, $after);
        }

        if ($entityType === 'goal_company') {
            GoalCompany::update($entityId, $after);
        }
    }
}
