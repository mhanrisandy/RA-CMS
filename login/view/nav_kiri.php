    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php?module=home" class="nav-link">Home</a>
      </li>
      
    </ul>
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3" method=GET action="index.php">
      <div class="input-group input-group-sm">
        <input type="hidden"name="module" value="data">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" name="kata">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>