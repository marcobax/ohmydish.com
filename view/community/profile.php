<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="fancy mb-2 text-center">Mijn profiel</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            Voornaam: <?php echo $user['first_name'] ?><br>
            Achternaam: <?php echo $user['last_name'] ?><br>
            Lid geworden op: <?php echo $user['created']; ?>
        </div>
    </div>
</div>
