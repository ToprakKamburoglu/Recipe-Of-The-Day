<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

requireRole([1, 2]);

$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

require_once "header.php"; 
?>

<div class="row my-4">
    <div class="col-md-12">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fs-3 fw-bold primary-text">
                <i class="fas fa-envelope me-2"></i>Inbox Messages
            </h2>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-muted">All Received Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($messages) > 0): ?>
                                <?php foreach ($messages as $msg): ?>
                                    <tr>
                                        <td class="ps-4 text-muted small" style="width: 150px;">
                                            <i class="far fa-clock me-1"></i>
                                            <?= date('d M Y, H:i', strtotime($msg['created_at'])) ?>
                                        </td>
                                        
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($msg['name']) ?></div>
                                            <div class="text-muted small">
                                                <?= htmlspecialchars($msg['email']) ?>
                                            </div>
                                        </td>

                                        <td class="fw-bold text-secondary">
                                            <?= htmlspecialchars($msg['subject']) ?>
                                        </td>

                                        <td style="max-width: 300px;">
                                            <div class="text-muted text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($msg['message']) ?>
                                            </div>
                                        </td>

                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#msgModal<?= $msg['id'] ?>">
                                                <i class="fas fa-eye"></i> Read
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="msgModal<?= $msg['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold"><?= htmlspecialchars($msg['subject']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3 border-bottom pb-2">
                                                        <strong>From:</strong> <?= htmlspecialchars($msg['name']) ?> <br>
                                                        <span class="text-muted small"><?= htmlspecialchars($msg['email']) ?></span><br>
                                                        <small class="text-muted">Date: <?= date('d M Y, H:i', strtotime($msg['created_at'])) ?></small>
                                                    </div>
                                                    <div class="p-3 bg-light rounded text-dark">
                                                        <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-primary btn-sm" style="background-color: var(--main-bg-color);"">
                                                        <i class="fas fa-reply"></i> Reply
                                                    </a>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-light-gray"></i>
                                        <p>No messages found.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>