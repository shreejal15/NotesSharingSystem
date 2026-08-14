<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $note_id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        // Sets status to approved AND stores which admin approved it
        $stmt = $conn->prepare("UPDATE notes SET status = 'approved', approved_by = ? WHERE id = ?");
        $stmt->bind_param("ii", $admin_id, $note_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE notes SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: pending_notes.php");
    exit();
}

$query = "
    SELECT notes.id, notes.title, notes.subject, notes.course, notes.semester, notes.file_name, users.fullname 
    FROM notes 
    JOIN users ON notes.user_id = users.id 
    WHERE notes.status = 'pending' 
    ORDER BY notes.created_at DESC
";
$pending_notes = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Notes - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="sidebar">
    <h2>The<br>Learning Hub</h2>
    <a href="admin.php">Dashboard</a>
    <a href="pending_notes.php" class="active">Pending Notes</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h1>Pending Notes Approval</h1>
    </div>

    <div class="recent">
        <table>
            <tr>
                <th>Student</th>
                <th>Title</th>
                <th>Subject</th>
                <th>File</th>
                <th>Action</th>
            </tr>
            <?php if ($pending_notes && $pending_notes->num_rows > 0): ?>
                <?php while ($note = $pending_notes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($note['title']); ?></td>
                        <td><?php echo htmlspecialchars($note['subject']); ?></td>
                        <td>
                            <a href="uploads/notes/<?php echo htmlspecialchars($note['file_name']); ?>" target="_blank" class="view">View File</a>
                        </td>
                        <td>
                            <a href="pending_notes.php?action=approve&id=<?php echo $note['id']; ?>" class="approve">Approve</a>
                            <a href="pending_notes.php?action=reject&id=<?php echo $note['id']; ?>" class="reject" onclick="return confirm('Reject this note?')">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No pending notes to review!</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>