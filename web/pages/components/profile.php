<div class="menu-overlay" onload=loadInfo();>
    <div class="container">
        <div class="menu">
            <div class="menu-close">
                <button class="mdi mdi-24px mdi-close"></button>
            </div>
            <div class="profile-header">
                <div class="t-48px" id='name'>$username</div>
                <div>RANK #$rank</div>
            </div>
            <div class="profile-content">
                <div class="row">
                    <div class="col">
                        <i class="t-48px mdi mdi-trophy"></i>
                        <div class="t-48px" id='wins'>$wins</div>
                        <div>WINS</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-flash"></i>
                        <div class="t-48px" id='losses'>$losses</div>
                        <div>LOSSES</div>
                    </div>
                    <div class="col">
                        <i class="t-48px mdi mdi-script"></i>
                        <div class="t-48px" id='ties'>$ties</div>
                        <div>TIES</div>
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
			var wins = document.getElementById('wins');
			var losses = document.getElementById('losses');
			var ties = document.getElementById('ties');
			var name = document.getElementById('name');
			
			wins.textContent = stats['wins'];
			losses.textContent = stats['losses'];
			ties.textContent = stats['ties'];
			name.textContent = stats['name'];
			// rank tier logic maybe
		}
			
	};
	xhttp.open('GET', '../res/php/player_statistics.php', true);
	xhttp.send();
}
loadInfo();
</script>