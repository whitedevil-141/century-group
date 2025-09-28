<?php
use Src\Models\RealEstate;
$project = RealEstate::find($_GET['id'] ?? 1);
?>
<?php include 'layouts/header.php'; ?>

<div class="container">
  <?php if ($project): ?>
    <h2 class="sec-title"><?= $project->title; ?></h2>
    <p><?= $project->description; ?></p>
    <p class="text-muted"><strong>Location:</strong> <?= $project->location; ?></p>
  <?php else: ?>
    <p>Project not found.</p>
  <?php endif; ?>
</div>

<?php include 'layouts/footer.php'; ?>
