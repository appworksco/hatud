<div class="wrapper">
    <input type="checkbox" id="btn" hidden>
    <label for="btn" class="menu-btn">
        <i class="bi bi-arrow-bar-right"></i>
        <i class="bi bi-arrow-bar-left"></i>
    </label>
    <nav id="sidebar">
        <div class="title text-center py-5 ps-2">
            <img src="https://ui-avatars.com/api/?name=<?= $fullname ?>" class="rounded-circle" alt="<?= $fullname ?>">
            <h6 class="mt-2" style="font-size: .9rem;"><?= $fullname ?> <br> <?= $mobileNumber ?></h6>
        </div>
        <ul class="list-items p-0">
            <li class="ps-3"><a href="location.php"><i class="bi bi-map"></i> Map</a></li>
            <li class="ps-3"><a href="bookings.php"><i class="bi bi-journal-bookmark"></i> Bookings</a></li>
            <li class="ps-3"><a href="logout.php"><i class="bi bi-arrow-return-left"></i> Logout</a></li>
        </ul>
    </nav>
</div>