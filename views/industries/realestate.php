<?php
use Src\Models\RealEstate;
$projects = RealEstate::all();
?>
<?php include 'layouts/header.php'; ?>

<div class="container">
  <h2 class="sec-title">Our Real Estate Projects</h2>
  <p class="sec-text">Explore premium developments by CENTRUY GROUP, crafted with innovation and quality.</p>

  <div class="row">
    <?php foreach ($projects as $p): ?>
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-body">
            <h4 class="card-title"><?= $p->title; ?></h4>
            <p class="card-text"><?= $p->description; ?></p>
            <p class="text-muted"><i class="bi bi-geo-alt"></i> <?= $p->location; ?></p>
            <a href="/realestate/show/<?= $p->id; ?>" class="btn btn-primary">View Project</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'layouts/footer.php'; ?>
