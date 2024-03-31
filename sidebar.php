<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="index.php">Toko Sumber Jaya</a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="index.php">Toko Sumber Jaya</a>
  </div>
  <ul class="sidebar-menu" style="overflow-y: auto;">
    <li class="menu-header">Dashboard</li>
    <li class="active"><a class="nav-link" href="index.php"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
    <li class="menu-header">Transaksi</li>
    <li><a class="nav-link" href="transaksi.php"><i class="fas fa-wallet"></i><span>Transaksi</span></a></li>
    <li><a class="nav-link" href="riwayat.php"><i class="fas fa-wallet"></i><span>Riwayat Transaksi</span></a></li>

    <?php if ($_SESSION['role'] === 'Admin') : ?>
      <li class="menu-header">Master Items</li>
      <li><a class="nav-link" href="kategori.php"><i class="fas fa-wallet"></i><span>Kategori</span></a></li>
      <li><a class="nav-link" href="item.php"><i class="fas fa-wallet"></i><span>Item</span></a></li>
      <li><a class="nav-link" href="item_terjual.php"><i class="fas fa-wallet"></i><span>Item Terjual</span></a></li>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'Admin') : ?>
      <li class="menu-header">Master Opname</li>
      <li><a class="nav-link" href="inventory.php"><i class="fas fa-wallet"></i><span>Inventory</span></a></li>
      <li><a class="nav-link" href="opname.php"><i class="fas fa-wallet"></i><span>Stock Opname</span></a></li>
      <li><a class="nav-link" href="piutang.php"><i class="fas fa-wallet"></i><span>Piutang</span></a></li>
    <?php endif; ?>
    <li class="menu-header">Master</li>
    <!-- <li><a class="nav-link" href="member.php"><i class="fas fa-wallet"></i><span>Member</span></a></li> -->
    <li><a class="nav-link" href="pelanggan.php"><i class="fas fa-wallet"></i><span>Daftar Pelanggan</span></a></li>
    <?php if ($_SESSION['role'] === 'Admin') : ?>
      <li><a class="nav-link" href="sales.php"><i class="fas fa-wallet"></i><span>Daftar Sales</span></a></li>
    <?php endif; ?>
  </ul>
</aside>