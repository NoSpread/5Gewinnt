<div id="profile" class="menu-overlay" onload=loadInfo();>
    <div class="container">
        <div class="menu">
            <div class="menu-close">
                <button class="mdi mdi-24px mdi-close"></button>
            </div>
            <div class="profile-header">
                <div class="row">
                    <div class="col">
                        <img class="profile-image" src="../res/upimages/<?php echo $avatarpath; ?>" alt="avatar">
                    </div>
                    <div class="col">
                        <div class="row text-left">
                            <div class="col t-48px">
                                <?php echo $username; ?>
                            </div>
                            <div class="w-100"></div>
                            <div id="rank" class="col"></div>
                            <div class="w-100"></div>
                            <div class="col">
                                Name: <?php echo $name; ?>
                            </div>
                            <div class="w-100"></div>
                            <div class="col">
                                Age: <?php echo $age; ?>
                            </div>
                            <div class="w-100"></div>
                            <div class="col">
                                Gender: <?php echo $gender; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-content">
                <div class="row">
                    <div class="col">
                        <i class="t-48px mdi mdi-trophy"></i>
                        <div class="t-48px" id='wins'></div>
                        <div>WINS</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-flash"></i>
                        <div class="t-48px" id='losses'></div>
                        <div>LOSSES</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-script"></i>
                        <div class="t-48px" id='ties'></div>
                        <div>TIES</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-gamepad-variant"></i>
                        <div class="t-48px" id='games'></div>
                        <div>GAMES</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function loadInfo() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var stats = JSON.parse(this.responseText);

			document.getElementById('wins').textContent = stats.wins;
			document.getElementById('losses').textContent = stats.losses;
			document.getElementById('ties').textContent = stats.ties;
			document.getElementById('name').textContent = stats.name;

            if (stats.total > 0) {
                var rank = stats.wins / stats.total * 100;
                var deviation = 50 / Math.sqrt(stats.total);
                document.getElementById('rank').textContent = 'RANK ' + rank.toFixed(0) + ' \u00B1' + deviation.toFixed(1);
            } else {
                document.getElementById('rank').textContent = 'RANK ???';
            }
		}

	};
	xhttp.open('GET', '../res/php/player_statistics.php', true);
	xhttp.send();
}
loadInfo();
</script>
